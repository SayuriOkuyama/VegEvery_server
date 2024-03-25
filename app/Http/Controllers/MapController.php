<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
  public function store(Request $request)
  {
    Log::debug($request);

    // $data = Restaurant::create([
    //   "name" => $request->place_id,
    //   "place_id" => $request->place_id,
    //   'latitude' => $request->latitude,
    //   'longitude' => $request->longitude,
    //   'star' => 0,
    //   'vegan' => $request->vege_type["vegan"],
    //   'oriental_vegetarian' => $request->vege_type["oriental_vegetarian"],
    //   'ovo_vegetarian' => $request->vege_type["ovo_vegetarian"],
    //   'pescatarian' => $request->vege_type["pescatarian"],
    //   'lacto_vegetarian' => $request->vege_type["lacto_vegetarian"],
    //   'pollo_vegetarian' => $request->vege_type["pollo_vegetarian"],
    //   'fruitarian' => $request->vege_type["fruitarian"],
    //   'other_vegetarian' => $request->vege_type["other_vegetarian"],
    // ]);

    return response()->json($request);
  }
}
