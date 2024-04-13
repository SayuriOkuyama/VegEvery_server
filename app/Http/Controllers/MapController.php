<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
  public function store(Request $request)
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
      ]);
      Log::debug("ステップ４完了");

      // 新たなベジタイプがあったらレストランのタイプも更新
      foreach ($types as $type => $value) {
        if ($menu["vege_type"][$type] === "true") {
          Log::debug($restaurant[$type]);
          $restaurant[$type] = true;
        };
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

    $restaurant->star = $average;

    $restaurant->save();

    Log::debug("ステップ６完了");


    $response = [
      "restaurantData" => $restaurant,
      "reviewData" => $reviewData,
      "menusData" => $menus,
    ];

    return response()->json($response);
  }
}
