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

    return response()->json($article, 200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $thumbnail = $request->thumbnail;
    $stepImages = $request->stepImages;

    Log::debug($thumbnail);
    Log::debug($stepImages);

    $thumbnail = $request->file("thumbnail");
    $stepImage = $request->file("stepImages");
    Log::debug($thumbnail);
    Log::debug($stepImage);

    $article = ArticleOfRecipe::create([
      "user_id" => 1,
      "title" => $request->title,
      "thumbnail" => $request->thumbnail,
      "cooking_time" => $request->time,
      "servings" => $request->servings,
      "vegan" => $request['vege_type']['vegan'],
      "oriental_vegetarian" => $request['vege_type']['oriental_vegetarian'],
      "ovo_vegetarian" => $request['vege_type']['ovo_vegetarian'],
      "pescatarian" => $request['vege_type']['pescatarian'],
      "lacto_vegetarian" => $request['vege_type']['lacto_vegetarian'],
      "pollo_vegetarian" => $request['vege_type']['pollo_vegetarian'],
      "fruitarian" => $request['vege_type']['fruitarian'],
      "other_vegetarian" => $request['vege_type']['other_vegetarian'],
    ]);

    foreach ($request["steps"] as $order => $step) {
      $stepData = RecipeStep::create([
        "article_of_recipe_id" => $article->id,
        "order" => $order + 1,
        "text" => $step["text"],
        "image" => $request->stepImages[$order][$order],
      ]);
    };

    for ($i = 0; $i < count($request["materials"]); $i++) {
      $materials = Material::create([
        "article_of_recipe_id" => $article->id,
        "name" => $request["materials"][$i]["material"],
        "quantity" => $request["materials"][$i]['quantity'],
        "unit" => $request["materials"][$i]['unit'],
      ]);
    }

    $tags = $request['tags'];

    foreach ($tags as $tag) {
      if ($tag !== null) {
        $tag_data = Tag::firstOrCreate(['name' => $tag["tag"]]);

        $article_tag = ArticleOfRecipeTag::create([
          'article_of_recipe_id' => $article->id,
          'tag_id' => $tag_data->id
        ]);
      }
    }

    $supabaseUrl = env('SUPABASE_URL');
    $apiKey = env('SUPABASE_APIKEY');
    $bucketName = env('SUPABASE_BUCKET');


    // $filepath = $thumbnail->getClientOriginalName();
    // $response = Http::withHeaders([
    //   'Authorization' => 'Bearer ' . $apiKey,
    // ])
    //   ->attach('file', $filepath)
    //   ->post("{$supabaseUrl}/storage/v1/object/{$bucketName}/recipes/thumbnail", [
    //     'file' => fopen('/path/to/file', 'r')
    //   ]);

    // $fileURL = $response->json()['url'];

    // return response()->json($fileURL);
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
