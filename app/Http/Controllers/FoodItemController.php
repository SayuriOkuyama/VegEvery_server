<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfItemTag;
use App\Models\CommentToItem;
use App\Models\Item;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FoodItemController extends Controller
{
  /**
   * 人気順 にレシピ記事を返す
   */
  public function index(Request $request)
  {
    $page = $request->page;
    if ($page === 'top') {
      $articles = ArticleOfItem::with('user')->orderBy('number_of_likes', 'desc')->take(6)->get();
      return response()->json($articles, 200);
    } else {
      $articles = ArticleOfItem::with('user')->orderBy('number_of_likes', 'desc')->paginate(20);
      return response()->json($articles, 200);
    }
  }

  public function search(Request $request)
  {
    $vegeTag = $request->vegeTag;
    $articles = ArticleOfItem::with('user')->where([$vegeTag => 1])->orderBy('number_of_likes', 'desc')->paginate(20);
    return response()->json($articles, 200);
  }

  public function get(string $id)
  {
    $article = ArticleOfItem::with('user', 'items', 'reports', 'commentsToItem', 'tags')->where('id', $id)->first();

    $commentsWithUserName = [];
    foreach ($article->commentsToItem as $comment) {
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
    Log::debug($request);
    $vege_types = [];
    foreach ($request->vege_type as $type => $value) {

      if ($value) {
        $vege_types[$type] = true;
      } else {
        $vege_types[$type] = false;
      }
    }
    Log::debug("ステップ１完了");

    $article = ArticleOfItem::create([
      "user_id" => 1,
      "title" => $request->title,
      "thumbnail_path" => $request->thumbnail["thumbnail_path"],
      "thumbnail_url" => $request->thumbnail["thumbnail_url"],
      "vegan" => $vege_types['vegan'],
      "oriental_vegetarian" => $vege_types['oriental_vegetarian'],
      "ovo_vegetarian" => $vege_types['ovo_vegetarian'],
      "pescatarian" => $vege_types['pescatarian'],
      "lacto_vegetarian" => $vege_types['lacto_vegetarian'],
      "pollo_vegetarian" => $vege_types['pollo_vegetarian'],
      "fruitarian" => $vege_types['fruitarian'],
      "other_vegetarian" => $vege_types['other_vegetarian'],
    ]);
    Log::debug("ステップ２完了");


    $stepsData = [];
    for ($i = 0; $i < count($request->recipe_step["step_order_text"]); $i++) {
      if (isset($request->recipe_step["stepImages"][$i]["image_path"])) {
        $stepsData[] = Report::create([
          "article_of_recipe_id" => $article->id,
          "order" => $request->recipe_step["step_order_text"][$i]["order"],
          "image_path" => $request->recipe_step["stepImages"][$i]["image_path"],
          "image_url" => $request->recipe_step["stepImages"][$i]["image_url"],
          "text" => $request->recipe_step["step_order_text"][$i]["text"],
        ]);
      } else {
        $stepsData[] = Report::create([
          "article_of_recipe_id" => $article->id,
          "order" => $request->recipe_step["step_order_text"][$i]["order"],
          "image_path" => "",
          "image_url" => "",
          "text" => $request->recipe_step["step_order_text"][$i]["text"],
        ]);
      }
    }
    Log::debug("ステップ３完了");

    $materialsData = [];
    for ($i = 0; $i < count($request->materials); $i++) {
      $materialsData[] = Item::create([
        "article_of_recipe_id" => $article->id,
        "name" => $request->materials[$i]["material"],
        "quantity" => $request->materials[$i]['quantity'],
        "unit" => $request->materials[$i]['unit'],
      ]);
    }

    $tagsData = [];
    $articleTagsData = [];
    foreach ($request->tags as $tag) {
      if ($tag !== null) {
        $tag_data = Tag::firstOrCreate(['name' => $tag["tag"]]);
        $tagsData[] = $tag_data;

        $articleTagsData[] = ArticleOfItem::create([
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
    // 仮にユーザー１とする
    $user = User::find(1);
    $commentData = CommentToItem::create([
      "article_of_recipe_id" => $id,
      "user_id" => $user->id,
      "text" => $request->text
    ]);
    Log::debug($commentData);

    $commentWithUserName = [
      "id" => $commentData->id,
      "userName" => $user->name,
      "userIcon" => $user->icon,
      "text" => $commentData->text,
      "likes" => $commentData->number_of_likes
    ];
    Log::debug($commentWithUserName);

    return response()->json($commentWithUserName);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  // public function update(Request $request, string $id)
  // {
  //   Log::debug($request);
  //   $article = ArticleOfItem::with('user')->where('id', $id)->first();

  //   $article->title = $request->values["title"];
  //   $article->thumbnail_path = $request->values["thumbnail"]["thumbnail_path"];
  //   $article->thumbnail_url = $request->values["thumbnail"]["thumbnail_url"];
  //   $article->vegan = $request->values["vegeTags"]["vegan"];
  //   $article->oriental_vegetarian = $request->values["vegeTags"]["oriental_vegetarian"];
  //   $article->ovo_vegetarian = $request->values["vegeTags"]["ovo_vegetarian"];
  //   $article->pescatarian = $request->values["vegeTags"]["pescatarian"];
  //   $article->lacto_vegetarian = $request->values["vegeTags"]["lacto_vegetarian"];
  //   $article->pollo_vegetarian = $request->values["vegeTags"]["pollo_vegetarian"];
  //   $article->fruitarian = $request->values["vegeTags"]["fruitarian"];
  //   $article->other_vegetarian = $request->values["vegeTags"]["other_vegetarian"];

  //   $newMaterials = $request->values["items"];
  //   $oldMaterials = $article->materials;
  //   $maxMaterialsNum = max(count($newMaterials), count($oldMaterials));
  //   $materialsData = [];
  //   for ($i = 0; $i < $maxMaterialsNum; $i++) {
  //     if (isset($newMaterials[$i]) && isset($oldMaterials[$i]) && isset($newMaterials[$i]["id"]) && $newMaterials[$i]["id"] === $oldMaterials[$i]["id"]) {
  //       $oldMaterials[$i]->name = $newMaterials[$i]["name"];
  //       $oldMaterials[$i]->quantity = $newMaterials[$i]["quantity"];
  //       $oldMaterials[$i]->unit = $newMaterials[$i]["unit"];
  //     } else {
  //       if (isset($newMaterials[$i])) {
  //         Log::debug("新しい材料あり");
  //         $materialsData[] = Material::create([
  //           "article_of_recipe_id" => $article->id,
  //           "name" => $newMaterials[$i]["name"],
  //           "quantity" => $newMaterials[$i]['quantity'],
  //           "unit" => $newMaterials[$i]['unit'],
  //         ]);
  //       }
  //       if (isset($oldMaterials[$i])) {
  //         $oldMaterials[$i]->delete();
  //       }
  //     }
  //   }

  //   $oldSteps = RecipeStep::where('article_of_recipe_id', $article->id)->get();
  //   $newSteps = $request->values["recipe_step"];
  //   $stepsData = [];
  //   Log::debug($oldSteps);

  //   foreach ($oldSteps as $oldStep) {
  //     $oldStep->delete();
  //   }

  //   for ($i = 0; $i < count($request->values["recipe_step"]["step_order_text"]); $i++) {
  //     if (isset($newSteps["stepImages"][$i]["image_path"])) {
  //       $stepsData[] = RecipeStep::create([
  //         "article_of_recipe_id" => $article->id,
  //         "order" => $newSteps["step_order_text"][$i]["order"],
  //         "image_path" => $newSteps["stepImages"][$i]["image_path"],
  //         "image_url" => $newSteps["stepImages"][$i]["image_url"],
  //         "text" => $newSteps["step_order_text"][$i]["text"],
  //       ]);
  //     } else {
  //       $stepsData[] = RecipeStep::create([
  //         "article_of_recipe_id" => $article->id,
  //         "order" => $newSteps["step_order_text"][$i]["order"],
  //         "image_path" => "",
  //         "image_url" => "",
  //         "text" => $newSteps["step_order_text"][$i]["text"],
  //       ]);
  //     }
  //   }

  //   $newTags = $request->values['tags'];
  //   $tagsData = [];
  //   $articleTagsData = [];
  //   $article_tags = ArticleOfRecipeTag::where(['article_of_recipe_id' => $article->id])->get();

  //   if ($article_tags != null) {
  //     foreach ($article_tags as $article_tag) {
  //       $article_tag->delete();
  //     }
  //   }

  //   foreach ($newTags as $newTag) {
  //     if ($newTag["name"] !== null) {
  //       $tag_data = Tag::firstOrCreate(['name' => $newTag["name"]]);
  //       $tagsData[] = $tag_data;

  //       $articleTagsData[] = ArticleOfRecipeTag::firstOrCreate([
  //         'article_of_recipe_id' => $article->id,
  //         'tag_id' => $tag_data->id
  //       ]);
  //     }
  //   }

  //   $article->push();

  //   return response()->json([$article, $stepsData, $tagsData, $articleTagsData]);
  // }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
