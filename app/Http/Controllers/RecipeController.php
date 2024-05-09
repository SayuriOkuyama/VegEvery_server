<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfRecipe;
use App\Models\ArticleOfRecipeTag;
use App\Models\BookshelfArticleOfRecipe;
use App\Models\CommentToRecipe;
use App\Models\Like;
use App\Models\Material;
use App\Models\RecipeStep;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;

class RecipeController extends Controller
{
  /**
   * 人気順 にレシピ記事を返す
   */
  public function index(Request $request)
  {
    $page = $request->page;
    if ($page === 'top') {
      $articles = ArticleOfRecipe::with('user')->orderBy('number_of_likes', 'desc')->take(6)->get();
      return response()->json($articles, 200);
    } else {
      $articles = ArticleOfRecipe::with('user')->orderBy('number_of_likes', 'desc')->paginate(20);
      return response()->json($articles, 200);
    }
  }

  /**
   * ワード検索
   */
  public function search(Request $request)
  {
    $keyword = $request->search;
    $vegeTag = $request->type;
    Log::debug($request);
    Log::debug($keyword);
    Log::debug($vegeTag);
    if (!$keyword || $keyword == "null") {
      if ($vegeTag !== "null" || !$vegeTag) {
        $articles = ArticleOfRecipe::with('user')->where([$vegeTag => true])
          ->orderBy('updated_at', 'desc')->paginate(20);
        return response()->json($articles, 200);
      } else {
        $articles = ArticleOfRecipe::with('user')->where(["vegan" => true])
          ->orderBy('updated_at', 'desc')->paginate(20);
        return response()->json($articles, 200);
      }
    } else {
      $searchedArticles = ArticleOfRecipe::orWhereRaw("title &@~ ?", [$keyword])->get();
      $searchedMaterials = Material::orWhereRaw("name &@~ ?", [$keyword])->get();
      $searchedSteps = RecipeStep::orWhereRaw("text &@~ ?", [$keyword])->get();
      $searchedTags = Tag::orWhereRaw("name &@~ ?", [$keyword])
        ->with("articlesOfRecipe")->get();

      $articleIds = $searchedArticles->pluck('id')->toArray();
      $articleIdsFromMaterials = $searchedMaterials->pluck('article_id')->toArray();
      $articleIdsFromSteps = $searchedSteps->pluck('article_id')->toArray();
      $articleIdsFromTags = $searchedTags->pluck('id')->toArray();

      $uniqueIds = array_unique(array_merge(
        $articleIds,
        $articleIdsFromMaterials,
        $articleIdsFromSteps,
        $articleIdsFromTags
      ));
      $uniqueSearchedArticles = ArticleOfRecipe::with('user')->whereIn('id', $uniqueIds)
        ->where([$vegeTag => true])->orderBy('updated_at', 'desc')->paginate(20);
      return response()->json($uniqueSearchedArticles, 200);
    }
  }

  /**
   * 個別記事情報取得
   */
  public function get(string $id)
  {
    $article = ArticleOfRecipe::with(
      'user',
      'materials',
      'recipeSteps',
      'commentsToRecipe',
      'tags'
    )->where('id', $id)->first();

    $commentsWithUserName = [];
    foreach ($article->commentsToRecipe as $comment) {
      $user = User::find($comment->user_id);
      $commentsWithUserName[] = [
        "id" => $comment->id,
        "user_id" => $comment->user_id,
        "userName" => $user->name,
        "userIcon" => $user->icon_url,
        "userIconPath" => $user->icon_storage_path,
        "text" => $comment->text,
        "likes" => $comment->number_of_likes
      ];
    };

    $likes = Like::where('likeable_id', $id)->where('likeable_type', 'ArticleOfRecipe')->get();

    $response = [
      "article" => $article,
      "comments" => $commentsWithUserName,
      "likes" => $likes
    ];
    return response()->json($response, 200);
  }

  /**
   * 新規投稿保存
   */
  public function store(Request $request)
  {
    Log::debug($request);

    $path = Storage::putFile('recipes/thumbnail', $request->file('thumbnail'));
    Log::debug($path);

    $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;
    Log::debug($url);

    $article = ArticleOfRecipe::create([
      "user_id" => Auth::id(),
      "title" => $request->title,
      "thumbnail_path" => $path,
      "thumbnail_url" => $url,
      "cooking_time" => $request->time,
      "servings" => $request->servings,
      "vegan" => $request->vegan === "true" ? true : false,
      "oriental_vegetarian" => $request->oriental_vegetarian === "true" ? true : false,
      "ovo_vegetarian" => $request->ovo_vegetarian === "true" ? true : false,
      "pescatarian" => $request->pescatarian === "true" ? true : false,
      "lacto_vegetarian" => $request->lacto_vegetarian === "true" ? true : false,
      "pollo_vegetarian" => $request->pollo_vegetarian === "true" ? true : false,
      "fruitarian" => $request->fruitarian === "true" ? true : false,
      "other_vegetarian" => $request->other_vegetarian === "true" ? true : false,
    ]);
    Log::debug("ステップ２完了");

    $stepsData = [];

    for ($i = 0; $i < count($request->steps); $i++) {
      Log::debug($request->steps[$i]);
      $path = "";
      $url = "";
      if (isset($request->steps[$i]["image"])) {
        Log::debug($request->file('steps.' . $i . '.image'));

        $path = Storage::putFile('recipes/step_images', $request->file('steps.' . $i . '.image'));
        $url =  "https://static.vegevery.my-raga-bhakti.com/" . $path;
      }
      $stepsData[] = RecipeStep::create([
        "article_of_recipe_id" => $article->id,
        "order" => $request->steps[$i]["order"],
        "image_path" => $path,
        "image_url" => $url,
        "text" => $request->steps[$i]["text"],
      ]);
    }
    Log::debug("ステップ３完了");

    $materialsData = [];
    foreach ($request->materials as $material) {
      $materialsData[] = Material::create([
        "article_of_recipe_id" => $article->id,
        "name" => $material["name"],
        "quantity" => $material['quantity'],
        "unit" => $material['unit'],
      ]);
    }

    Log::debug("ステップ４完了");

    $tagsData = [];
    $articleTagsData = [];
    Log::debug($request->tags);
    foreach ($request->tags as $tag) {
      Log::debug($tag["name"]);
      if ($tag["name"] !== "") {

        $tag_data = Tag::firstOrCreate(['name' => $tag["name"]]);
        $tagsData[] = $tag_data;

        $articleTagsData[] = ArticleOfRecipeTag::create([
          'article_of_recipe_id' => $article->id,
          'tag_id' => $tag_data->id
        ]);
      }
    }

    $response = [
      "article" => $article,
      "stepsData" => $stepsData,
      "materialsData" => $materialsData,
      "tagsData" => $tagsData,
      "articleTagsData" => $articleTagsData
    ];

    return response()->json($response);
  }

  /**
   * コメント投稿
   */
  public function commentStore(Request $request, string $id)
  {
    Log::debug($request);
    Log::debug($id);

    $user = User::find($request->user_id);
    $commentData = CommentToRecipe::create([
      "article_of_recipe_id" => $id,
      "user_id" => $user->id,
      "text" => $request->text
    ]);
    Log::debug($commentData);

    $commentWithUserName = [
      "id" => $commentData->id,
      "userName" => $user->name,
      "userIcon" => $user->icon_url,
      "text" => $commentData->text,
      "likes" => 0
    ];
    Log::debug($commentWithUserName);

    return response()->json($commentWithUserName);
  }

  /**
   * 記事更新
   */
  public function update(Request $request, string $id)
  {
    Log::debug('recipe-update');
    Log::debug($request);
    $article = ArticleOfRecipe::with('user', 'materials')->where('id', $id)->first();

    $path = "";
    $url = "";

    if (!$request->thumbnail_path) {
      $path = Storage::putFile('recipes/thumbnail', $request->file('thumbnail_newFile'));
      $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;
    } else {
      $path = $request->thumbnail_path;
      $url = $request->thumbnail_url;
    }

    $article->title = $request->title;
    $article->cooking_time = $request->time;
    $article->servings = $request->servings;
    $article->thumbnail_path = $path;
    $article->thumbnail_url = $url;
    $article->vegan = $request->vegan === "true" ? true : false;
    $article->oriental_vegetarian = $request->oriental_vegetarian === "true" ? true : false;
    $article->ovo_vegetarian = $request->ovo_vegetarian === "true" ? true : false;
    $article->pescatarian = $request->pescatarian === "true" ? true : false;
    $article->lacto_vegetarian = $request->lacto_vegetarian === "true" ? true : false;
    $article->pollo_vegetarian = $request->pollo_vegetarian === "true" ? true : false;
    $article->fruitarian = $request->fruitarian === "true" ? true : false;
    $article->other_vegetarian = $request->other_vegetarian === "true" ? true : false;
    $article->push();
    Log::debug("第一ステップ完了");
    Log::debug($article);

    $newMaterials = $request->materials;
    $oldMaterials = $article->materials;

    Log::debug("↓oldMaterials");
    Log::debug($oldMaterials);
    Log::debug("↓newMaterials");
    Log::debug($newMaterials);
    $maxMaterialsNum = max(count($newMaterials), count($oldMaterials));
    $materialsData = [];
    for ($i = 0; $i < $maxMaterialsNum; $i++) {
      if (
        isset($newMaterials[$i]) && isset($oldMaterials[$i]) && isset($newMaterials[$i]["id"])
        && $newMaterials[$i]["id"] === $oldMaterials[$i]["id"]
      ) {
        $oldMaterials[$i]->name = $newMaterials[$i]["name"];
        $oldMaterials[$i]->quantity = $newMaterials[$i]["quantity"];
        $oldMaterials[$i]->unit = $newMaterials[$i]["unit"];
      } else {
        if (isset($newMaterials[$i])) {
          Log::debug("新しい材料あり");
          $materialsData[] = Material::create([
            "article_of_recipe_id" => $article->id,
            "name" => $newMaterials[$i]["name"],
            "quantity" => $newMaterials[$i]['quantity'],
            "unit" => $newMaterials[$i]['unit'],
          ]);
        }
        if (isset($oldMaterials[$i])) {
          $oldMaterials[$i]->delete();
        }
      }
    }
    Log::debug("第２ステップ完了");


    $oldSteps = RecipeStep::where('article_of_recipe_id', $article->id)->get();
    $newSteps = $request->steps;
    $stepsData = [];
    Log::debug("↓oldSteps");
    Log::debug($oldSteps);
    Log::debug("↓newSteps");
    Log::debug($newSteps);

    foreach ($oldSteps as $oldStep) {
      $oldStep->delete();
    }
    Log::debug("ここからnewSteps");

    if ($newSteps) {
      for ($i = 0; $i < count($newSteps); $i++) {
        $path = "";
        $url = "";

        // 新規ファイルあり
        if (isset($newSteps[$i]["file"]) && $newSteps[$i]["file"] != "undefined") {
          Log::debug("ファイルあり");

          $path = Storage::putFile('recipes/step_images', $request->file('steps.' . $i . '.file'));
          $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;

          // 画像なし
        } elseif (!isset($newSteps[$i]["file"]) && (!isset($newSteps[$i]["url"]) || $newSteps[$i]["url"] === "")) {
          $path = "";
          $url = "";

          // 前回の画像をそのまま使う
        } else {
          $path = $newSteps[$i]["path"];
          $url = $newSteps[$i]["url"];
        }

        $stepsData[] = RecipeStep::create([
          "article_of_recipe_id" => $article->id,
          "order" => $newSteps[$i]["order"],
          "image_path" => $path,
          "image_url" => $url,
          "text" => $newSteps[$i]["text"],
        ]);
      }
    }
    Log::debug($stepsData);
    Log::debug("第3ステップ完了");

    $tagsData = [];
    $articleTagsData = [];
    $article_tags = ArticleOfRecipeTag::where(['article_of_recipe_id' => $article->id])->get();

    if ($article_tags != null) {
      foreach ($article_tags as $article_tag) {
        $article_tag->delete();
      }
    }

    if ($request->tags) {
      foreach ($request->tags as $newTag) {
        Log::debug($newTag);
        if ($newTag !== null) {
          $tag_data = Tag::firstOrCreate(['name' => $newTag]);
          $tagsData[] = $tag_data;

          $articleTagsData[] = ArticleOfRecipeTag::firstOrCreate([
            'article_of_recipe_id' => $article->id,
            'tag_id' => $tag_data->id
          ]);
        }
      }
    }
    Log::debug("第4ステップ完了");


    $response = [
      "article" => $article,
      "stepsData" => $stepsData,
      "materialsData" => $materialsData,
      "tagsData" => $tagsData,
      "articleTagsData" => $articleTagsData
    ];

    return response()->json($response);
  }

  /**
   * 投稿削除
   */
  public function delete(string $id)
  {
    Log::debug($id);
    RecipeStep::where("article_of_recipe_id", $id)->delete();
    Log::debug("1完了");
    Material::where("article_of_recipe_id", $id)->delete();
    Log::debug("2完了");
    ArticleOfRecipeTag::where("article_of_recipe_id", $id)->delete();
    Log::debug("3完了");
    CommentToRecipe::where("article_of_recipe_id", $id)->delete();
    Log::debug("4完了");
    BookshelfArticleOfRecipe::where("article_of_recipe_id", $id)->delete();
    Log::debug("5完了");
    ArticleOfRecipe::find($id)->delete();
    Log::debug("6完了");
    Like::where(["likeable_type" => "ArticleOfRecipe", "likeable_id" => $id])->delete();
    Log::debug("削除しました");
    return response()->json("削除しました");
  }

  /**
   * コメント削除
   */
  public function commentDelete(Request $request)
  {
    Log::debug($request);
    CommentToRecipe::find($request->id)->delete();
    return response()->json("削除しました");
  }
}
