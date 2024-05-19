<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfRecipe;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
  public function update(Request $request, string $id): JsonResponse
  {
    Log::debug($request);
    $like = !$request->like;

    if ($like) {
      $newLikeData = Like::create([
        "user_id" => $request->user_id,
        "likeable_type" => $request->likeable_type,
        "likeable_id" => $request->likeable_id,
      ]);

      $request->likeable_type === "ArticleOfRecipe"
        ? $article = ArticleOfRecipe::find($request->likeable_id)
        : $article = ArticleOfItem::find($request->likeable_id);
      Log::debug($article);
      $article->number_of_likes++;
      $article->save();
      Log::debug($article);

      $response = [
        "user_id" => $newLikeData->user_id,
        "likeable_type" => $newLikeData->likeable_type,
        "likeable_id" => $newLikeData->likeable_id,
        "like" => true,
        "number_of_likes" => $article->number_of_likes
      ];
      Log::debug($response);
      return response()->json($response);
    }

    Like::find($request->id)->delete();

    $request->likeable_type === "ArticleOfRecipe"
      ? $article = ArticleOfRecipe::find($request->likeable_id)
      : $article = ArticleOfItem::find($request->likeable_id);

    $article->number_of_likes--;
    $article->save();

    $response = [
      "like" => false,
      "number_of_likes" => $article->number_of_likes
    ];

    return response()->json($response);
  }
}
