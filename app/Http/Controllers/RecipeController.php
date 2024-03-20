<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfRecipe;
use App\Models\ArticleOfRecipeTag;
use App\Models\CommentToRecipe;
use App\Models\Material;
use App\Models\RecipeStep;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
   * タグ・ワード検索
   */
  public function search(Request $request)
  {
    Log::debug($request);
    $keyword = $request->search;
    if (!$keyword || $keyword == "null") {
      Log::debug("ワードなし");
      $articles = ArticleOfRecipe::with('user')->orderBy('number_of_likes', 'desc')->paginate(20);
      return response()->json($articles, 200);
    } else {
      $searchedArticles = ArticleOfRecipe::orWhereRaw("title &@~ ?", [$keyword])->get();
      $searchedMaterials = Material::orWhereRaw("name &@~ ?", [$keyword])->get();
      $searchedSteps = RecipeStep::orWhereRaw("text &@~ ?", [$keyword])->get();

      $articleIds = $searchedArticles->pluck('id')->toArray();
      $articleIdsFromMaterials = $searchedMaterials->pluck('article_id')->toArray();
      $articleIdsFromSteps = $searchedSteps->pluck('article_id')->toArray();

      $uniqueIds = array_unique(array_merge($articleIds, $articleIdsFromMaterials, $articleIdsFromSteps));
      $uniqueSearchedArticles = ArticleOfRecipe::with('user')->whereIn('id', $uniqueIds)->orderBy('number_of_likes', 'desc')->paginate(20);
      // $uniqueSearchedArticles = ArticleOfRecipe::with('user', 'materials', 'recipeSteps', 'commentsToRecipe', 'tags')->whereIn('id', $uniqueIds)->orderBy('number_of_likes', 'desc')->paginate(20);
      return response()->json($uniqueSearchedArticles, 200);
    }
  }

  /**
   * 個別記事情報取得
   */
  public function get(string $id)
  {
    $article = ArticleOfRecipe::with('user', 'materials', 'recipeSteps', 'commentsToRecipe', 'tags')->where('id', $id)->first();

    $commentsWithUserName = [];
    foreach ($article->commentsToRecipe as $comment) {
      $user = User::find($comment->user_id);
      $commentsWithUserName[] = [
        "id" => $comment->id,
        "userName" => $user->name,
        "userIcon" => $user->icon,
        "text" => $comment->text,
        "likes" => $comment->number_of_likes
      ];
    };

    $response = [
      "article" => $article,
      "comments" => $commentsWithUserName
    ];

    return response()->json($response, 200);
  }

  /**
   * 新規投稿保存
   */
  public function store(Request $request)
  {
    $vege_types = [];
    foreach ($request->values['vege_type'] as $type => $value) {

      if ($value) {
        $vege_types[$type] = 1;
      } else {
        $vege_types[$type] = 0;
      }
    }

    $article = ArticleOfRecipe::create([
      "user_id" => 1,
      "title" => $request->values["title"],
      "thumbnail" => $request->thumbnailUrl,
      "cooking_time" => $request->values["time"],
      "servings" => $request->values["servings"],
      "vegan" => $vege_types['vegan'],
      "oriental_vegetarian" => $vege_types['oriental_vegetarian'],
      "ovo_vegetarian" => $vege_types['ovo_vegetarian'],
      "pescatarian" => $vege_types['pescatarian'],
      "lacto_vegetarian" => $vege_types['lacto_vegetarian'],
      "pollo_vegetarian" => $vege_types['pollo_vegetarian'],
      "fruitarian" => $vege_types['fruitarian'],
      "other_vegetarian" => $vege_types['other_vegetarian'],
    ]);

    $stepsData = [];
    foreach ($request->values["steps"] as $order => $step) {
      $stepsData[] = RecipeStep::create([
        "article_of_recipe_id" => $article->id,
        "order" => $order + 1,
        "text" => $step["text"],
        "image" => $request->stepImageUrls[$order],
      ]);
    };

    $materialsData = [];
    for ($i = 0; $i < count($request->values["materials"]); $i++) {
      $materialsData[] = Material::create([
        "article_of_recipe_id" => $article->id,
        "name" => $request->values["materials"][$i]["material"],
        "quantity" => $request->values["materials"][$i]['quantity'],
        "unit" => $request->values["materials"][$i]['unit'],
      ]);
    }

    $tags = $request->values['tags'];

    $tagsData = [];
    $articleTagsData = [];
    foreach ($tags as $tag) {
      if ($tag !== null) {
        $tag_data = Tag::firstOrCreate(['name' => $tag["tag"]]);
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
   * Display the specified resource.
   */
  // public function show(string $id)
  // {
  //   //
  // }

  /**
   * 記事更新
   */
  public function update(Request $request, string $id)
  {
    Log::debug($request);
    $article = ArticleOfRecipe::with('user', 'materials')->where('id', $id)->first();

    $article->title = $request->values["title"];
    $article->cooking_time = $request->values["cooking_time"];
    $article->servings = $request->values["servings"];
    $article->thumbnail_path = $request->values["thumbnail"]["thumbnail_path"];
    $article->thumbnail_url = $request->values["thumbnail"]["thumbnail_url"];
    $article->vegan = $request->values["vegeTags"]["vegan"];
    $article->oriental_vegetarian = $request->values["vegeTags"]["oriental_vegetarian"];
    $article->ovo_vegetarian = $request->values["vegeTags"]["ovo_vegetarian"];
    $article->pescatarian = $request->values["vegeTags"]["pescatarian"];
    $article->lacto_vegetarian = $request->values["vegeTags"]["lacto_vegetarian"];
    $article->pollo_vegetarian = $request->values["vegeTags"]["pollo_vegetarian"];
    $article->fruitarian = $request->values["vegeTags"]["fruitarian"];
    $article->other_vegetarian = $request->values["vegeTags"]["other_vegetarian"];
    Log::debug("第１ステップ完了");

    $newMaterials = $request->values["materials"];
    $oldMaterials = $article->materials;
    $maxMaterialsNum = max(count($newMaterials), count($oldMaterials));
    $materialsData = [];
    for ($i = 0; $i < $maxMaterialsNum; $i++) {
      // Log::debug($newMaterials[$i]);
      // Log::debug($oldMaterials[$i]);

      if (isset($newMaterials[$i]) && isset($oldMaterials[$i]) && isset($newMaterials[$i]["id"]) && $newMaterials[$i]["id"] === $oldMaterials[$i]["id"]) {
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
    $newSteps = $request->values["recipe_step"];
    $stepsData = [];
    Log::debug($oldSteps);

    foreach ($oldSteps as $oldStep) {
      $oldStep->delete();
    }
    Log::debug("第３ステップ完了");

    for ($i = 0; $i < count($request->values["recipe_step"]["step_order_text"]); $i++) {
      if (isset($newSteps["stepImages"][$i]["image_path"])) {
        $stepsData[] = RecipeStep::create([
          "article_of_recipe_id" => $article->id,
          "order" => $newSteps["step_order_text"][$i]["order"],
          "image_path" => $newSteps["stepImages"][$i]["image_path"],
          "image_url" => $newSteps["stepImages"][$i]["image_url"],
          "text" => $newSteps["step_order_text"][$i]["text"],
        ]);
      } else {
        $stepsData[] = RecipeStep::create([
          "article_of_recipe_id" => $article->id,
          "order" => $newSteps["step_order_text"][$i]["order"],
          "image_path" => "",
          "image_url" => "",
          "text" => $newSteps["step_order_text"][$i]["text"],
        ]);
      }
    }
    Log::debug("第４ステップ完了");

    $newTags = $request->values['tags'];
    $tagsData = [];
    $articleTagsData = [];
    $article_tags = ArticleOfRecipeTag::where(['article_of_recipe_id' => $article->id])->get();

    if ($article_tags != null) {
      foreach ($article_tags as $article_tag) {
        $article_tag->delete();
      }
    }
    Log::debug("第５ステップ完了");

    foreach ($newTags as $newTag) {
      if ($newTag["name"] !== null) {
        $tag_data = Tag::firstOrCreate(['name' => $newTag["name"]]);
        $tagsData[] = $tag_data;

        $articleTagsData[] = ArticleOfRecipeTag::firstOrCreate([
          'article_of_recipe_id' => $article->id,
          'tag_id' => $tag_data->id
        ]);
      }
    }
    Log::debug("最終ステップ完了");
    Log::debug($article);

    $article->push();

    return response()->json([$article, $stepsData, $tagsData, $articleTagsData]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
