<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleOfRecipe>
 */
class ArticleOfRecipeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $thumbnail_path = fake()->randomElement(["recipes/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg", "recipes/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg", "recipes/thumbnail/victoria-shes-4MEL9XS-3JQ-unsplash.jpg"]);
    if ($thumbnail_path === "recipes/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg") {
      $thumbnail_url = "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/public/VegEvery-backet/recipes/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg";
    } elseif ($thumbnail_path === "recipes/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg") {
      $thumbnail_url = "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/public/VegEvery-backet/recipes/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg";
    } else {
      $thumbnail_url = "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/public/VegEvery-backet/recipes/thumbnail/victoria-shes-4MEL9XS-3JQ-unsplash.jpg";
    }

    return [
      'title' => fake()->realText($maxNbChars = 20),
      'thumbnail_path' => $thumbnail_path,
      'thumbnail_url' => $thumbnail_url,
      'cooking_time' => fake()->randomElement([10, 15, 20, 30, 40, 50, 60]),
      'number_of_likes' => fake()->numberBetween($min = 0, $max = 500),
      'servings' => fake()->randomElement(["1", "2", "3", "4"]),
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
