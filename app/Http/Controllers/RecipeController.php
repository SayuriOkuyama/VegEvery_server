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
use Illuminate\Support\Facades\Http;

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
   * Store a newly created resource in storage.
   */
  public function search(Request $request)
  {
    $vegeTag = $request->vegeTag;
    $articles = ArticleOfRecipe::with('user')->where([$vegeTag => 1])->orderBy('number_of_likes', 'desc')->paginate(20);
    return response()->json($articles, 200);
  }

  /**
   * Show the form for creating a new resource.
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
   * Store a newly created resource in storage.
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
  public function show(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
