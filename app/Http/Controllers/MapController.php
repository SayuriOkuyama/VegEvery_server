<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
  public function get(string $id): JsonResponse
  {
    $restaurant = Restaurant::where('place_id', $id)->first();
    $reviews = Review::where("restaurant_id", $restaurant->id)->get();

    $reviewWithUser = [];
    foreach ($reviews as $review) {
      $user = User::find($review->user_id);
      $menus = Menu::where("review_id", $review->id)->get();
      $reviewWithUser[] = [
        "id" => $review->id,
        "user_id" => $review->user_id,
        "userName" => $user->name,
        "userIcon" => $user->icon_url,
        "userIcon_path" => $user->icon_storage_path,
        "thumbnail_url" => $review->thumbnail_url,
        "thumbnail_path" => $review->thumbnail_path,
        "stars" => $review->star,
        "text" => $review->text,
        "likes" => $review->number_of_likes,
        "menus" => $menus
      ];
    };

    $response = [
      "restaurant" => $restaurant,
      "reviews" => $reviewWithUser
    ];

    return response()->json($response);
  }

  public function search(Request $request): JsonResponse
  {
    Log::debug($request);

    $restaurants = Restaurant::whereIn('place_id', $request->id)->get();
    Log::debug($restaurants);

    $restaurantIds = [];
    $vegeTags = [];
    if (count($restaurants) > 0) {
      foreach ($restaurants as $restaurant) {
        $restaurantIds[] = $restaurant->place_id;
        $vegeTags[$restaurant->place_id] = [
          $restaurant->vegan,
          $restaurant->oriental_vegetarian,
          $restaurant->ovo_vegetarian,
          $restaurant->pescatarian,
          $restaurant->lacto_vegetarian,
          $restaurant->pollo_vegetarian,
          $restaurant->fruitarian,
          $restaurant->other_vegetarian,
        ];
      }
    }

    $response = [
      "restaurantIds" => $restaurantIds,
      "vegeTags" => $vegeTags
    ];
    Log::debug($response);

    return response()->json($response);
  }

  public function store(Request $request): JsonResponse
  {
    Log::debug($request);

    $restaurant = Restaurant::where("place_id", $request->restaurant["place_id"])->first();

    if (!$restaurant) {
      Log::debug($request->restaurant["name"]);

      $restaurant = Restaurant::create([
        "name" => $request->restaurant["name"],
        "place_id" => $request->restaurant["place_id"],
        'latitude' => $request->restaurant["latitude"],
        'longitude' => $request->restaurant["longitude"],
        'star' => 0,
      ]);
    }

    Log::debug("ステップ１完了");

    $path = Storage::putFile('reviews', $request->file('thumbnail'));
    $url = "https://static.vegevery.my-raga-bhakti.com/" . $path;

    $reviewData = Review::create([
      "restaurant_id" => $restaurant->id,
      "user_id" => Auth::id(),
      'star' => $request->stars,
      'thumbnail_path' => $path,
      'thumbnail_url' => $url,
      'number_of_likes' => 0,
      'text' => $request->text,
    ]);

    Log::debug("ステップ２完了");

    $types = [
      "vegan" => $restaurant->vegan,
      'oriental_vegetarian' => $restaurant->oriental_vegetarian,
      'ovo_vegetarian' =>  $restaurant->ovo_vegetarian,
      'pescatarian' => $restaurant->pescatarian,
      'lacto_vegetarian' =>  $restaurant->lacto_vegetarian,
      'pollo_vegetarian' => $restaurant->pollo_vegetarian,
      'fruitarian' => $restaurant->fruitarian,
      'other_vegetarian' =>  $restaurant->other_vegetarian,
    ];

    Log::debug("ステップ３完了");

    $menus = [];
    foreach ($request->menus as $menu) {
      $menus[] = Menu::create([
        'review_id' => $reviewData->id,
        "name" => $menu["name"],
        "price" => $menu["price"],
        "vegan" => $menu["vege_type"]["vegan"] === "true" ? true : false,
        'oriental_vegetarian' => $menu["vege_type"]["oriental_vegetarian"] === "true" ? true : false,
        'ovo_vegetarian' => $menu["vege_type"]["ovo_vegetarian"] === "true" ? true : false,
        'pescatarian' => $menu["vege_type"]["pescatarian"] === "true" ? true : false,
        'lacto_vegetarian' =>  $menu["vege_type"]["lacto_vegetarian"] === "true" ? true : false,
        'pollo_vegetarian' => $menu["vege_type"]["pollo_vegetarian"] === "true" ? true : false,
        'fruitarian' => $menu["vege_type"]["fruitarian"] === "true" ? true : false,
        'other_vegetarian' =>  $menu["vege_type"]["other_vegetarian"] === "true" ? true : false,
      ]);
      Log::debug("ステップ４完了");

      // 新たなベジタイプがあったらレストランのタイプも更新
      if ($menu) {
        foreach ($types as $type => $value) {
          if ($menu["vege_type"][$type] === "true") {
            Log::debug($restaurant[$type]);
            $restaurant[$type] = true;
          };
        }
      }
    }
    Log::debug("ステップ５完了");


    // レストラン平均評価数を更新
    $count = Restaurant::count("star");
    $sum = Restaurant::sum("star");
    $average = ($sum + $request->stars) / ($count + 1);
    // 最も近い0.5に丸める
    $average = round($average * 2) / 2;

    // 小数点以下1桁まで表示
    $average = number_format($average, 1);
    Log::debug("average: $average");

    $restaurant->star = floatval($average);

    // タイプも更新
    if (isset($menu)) {
      foreach ($types as $type => $value) {
        if ($menu["vege_type"][$type] === "true") {
          Log::debug($restaurant[$type]);
          $restaurant[$type] = true;
        };
      }
    }

    $restaurant->save();

    Log::debug("ステップ６完了");


    $response = [
      "restaurantData" => $restaurant,
      "reviewData" => $reviewData,
      "menusData" => $menus,
    ];

    return response()->json($response);
  }

  public function delete(string $id): JsonResponse
  {
    $review = Review::find($id);

    Menu::where("review_id", $id)->delete();

    $restaurantId = $review->restaurant_id;
    $review->delete();

    Log::debug("ステップ１完了");

    $reviews = Review::where("restaurant_id", $restaurantId)->get();
    $restaurant = Restaurant::find($restaurantId);

    Log::debug("ステップ２完了");

    if ($reviews->isEmpty()) {
      $restaurant->delete();

      Log::debug("ステップ２完了(レストラン削除)");
    } else {
      // レストラン平均評価数を更新
      $count = Restaurant::count("star");
      $sum = Restaurant::sum("star");
      $average = $sum / $count;
      // 最も近い0.5に丸める
      $average = round($average * 2) / 2;

      // 小数点以下1桁まで表示
      $average = number_format($average, 1);
      Log::debug("average: $average");

      $restaurant->star = $average;

      $types = [
        "vegan",
        'oriental_vegetarian',
        'ovo_vegetarian',
        'pescatarian',
        'lacto_vegetarian',
        'pollo_vegetarian',
        'fruitarian',
        'other_vegetarian',
      ];

      // true が１個もなかったら false に更新
      foreach ($types as $type) {
        $result = Menu::whereHas('review', function ($query) use ($restaurantId) {
          $query->where('restaurant_id', $restaurantId);
        })->where($type, true)->first();

        if (!$result) {
          $restaurant[$type] = false;
        }
      }

      $restaurant->save();
      Log::debug("ステップ２完了(レストラン更新)");
    }

    $response = [
      "message" => "削除しました"
    ];

    return response()->json($response);
  }
}
