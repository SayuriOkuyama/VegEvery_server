<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'name' => "-コメダ珈琲店 南鴨宮店",
        'place_id' => "ChIJc7EbuqOlGWARFlPcoQG-ItU",
        'latitude' => "35.2713635",
        'longitude' => "139.1798268",
        'star' => 4,
        'vegan' => true,
        'oriental_vegetarian' => true,
        'ovo_vegetarian' => true,
        'pescatarian' => true,
        'lacto_vegetarian' => true,
        'pollo_vegetarian' => true,
        'fruitarian' => true,
        'other_vegetarian' => true,
      ],
      [
        'name' => "吟味亭ともや",
        'place_id' => "ChIJYRFjybylGWARTWgOPWrx5Fg",
        'latitude' => "35.2745971",
        'longitude' => "139.1785744",
        'star' => 2,
        'vegan' => false,
        'oriental_vegetarian' => false,
        'ovo_vegetarian' => false,
        'pescatarian' => true,
        'lacto_vegetarian' => false,
        'pollo_vegetarian' => true,
        'fruitarian' => false,
        'other_vegetarian' => 0,
      ],
      [
        'name' => "三松",
        'place_id' => "ChIJuxbQhKOlGWARN4_mE9D31ic",
        'latitude' => "35.2714623",
        'longitude' => "139.1798268",
        'star' => 3,
        'vegan' => false,
        'oriental_vegetarian' => false,
        'ovo_vegetarian' => true,
        'pescatarian' => true,
        'lacto_vegetarian' => false,
        'pollo_vegetarian' => true,
        'fruitarian' => false,
        'other_vegetarian' => 0,
      ],
    ];

    foreach ($data as $restaurantData) {
      Restaurant::create($restaurantData);
    }
  }
}
