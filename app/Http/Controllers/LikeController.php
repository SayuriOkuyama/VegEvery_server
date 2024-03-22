<?php

namespace App\Http\Controllers;

use App\Models\ArticleOfRecipe;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
  public function update(Request $request, string $id)
  {
    Log::debug($request);
    $like = !$request->like;

    if ($like) {
      $newLikeData = Like::create([
        "user_id" => $request->user_id,
        "likeable_type" => $request->likeable_type,
        "likeable_id" => $request->likeable_id,
      ]);

      $article = ArticleOfRecipe::find($request->likeable_id);
      $article->number_of_likes++;
      $article->save();

      $response = [
        "user_id" => $newLikeData->user_id,
        "likeable_type" => $newLikeData->likeable_type,
        "likeable_id" => $newLikeData->likeable_id,
        "like" => true,
        "number_of_likes" => $article->number_of_likes
      ];
      Log::debug($response);
      return response()->json($response);
    } else {
      Like::find($request->id)->delete();

      $article = ArticleOfRecipe::find($request->likeable_id);
      $article->number_of_likes--;
      $article->save();

      $response = [
        "like" => false,
        "number_of_likes" => $article->number_of_likes
      ];

      return response()->json($response);
    }
  }
}
