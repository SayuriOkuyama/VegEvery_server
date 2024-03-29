<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleOfItem>
 */
class ArticleOfItemFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $thumbnail_path = fake()->randomElement([
      "items/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg",
      "items/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg",
      "items/thumbnail/victoria-shes-4MEL9XS-3JQ-unsplash.jpg"
    ]);
    if ($thumbnail_path === "items/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg") {
      $thumbnail_url =
        "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/" .
        "public/VegEvery-backet/items/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg";
    } elseif ($thumbnail_path === "items/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg") {
      $thumbnail_url =
        "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/" .
        "public/VegEvery-backet/items/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg";
    } else {
      $thumbnail_url =
        "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/" .
        "public/VegEvery-backet/items/thumbnail/victoria-shes-4MEL9XS-3JQ-unsplash.jpg";
    }
    return [
      'title' => fake()->realText($maxNbChars = 20),
      'thumbnail_path' => $thumbnail_path,
      'thumbnail_url' => $thumbnail_url,
      'number_of_likes' => fake()->numberBetween($min = 0, $max = 500),
      'vegan' => fake()->randomElement([true, false]),
      'oriental_vegetarian' => fake()->randomElement([true, false]),
      'ovo_vegetarian' => fake()->randomElement([true, false]),
      'pescatarian' => fake()->randomElement([true, false]),
      'lacto_vegetarian' => fake()->randomElement([true, false]),
      'pollo_vegetarian' => fake()->randomElement([true, false]),
      'fruitarian' => fake()->randomElement([true, false]),
      'other_vegetarian' => fake()->randomElement([true, false]),
      'user_id' => User::factory()
    ];
  }
}
