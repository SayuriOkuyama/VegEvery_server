<?php

namespace Database\Factories;

use App\Models\ArticleOfRecipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecipeStep>
 */
class RecipeStepFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $image_path = fake()->randomElement([
      "recipes/step_image/brooke-lark-kXQ3J7_2fpc-unsplash.jpg",
      "recipes/step_image/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg",
      "recipes/step_image/victoria-shes-4MEL9XS-3JQ-unsplash.jpg"
    ]);
    if ($image_path === "recipes/step_image/brooke-lark-kXQ3J7_2fpc-unsplash.jpg") {
      $image_url =
        "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/" .
        "public/VegEvery-backet/recipes/step_image/brooke-lark-kXQ3J7_2fpc-unsplash.jpg";
    } elseif ($image_path === "recipes/step_image/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg") {
      $image_url =
        "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/" .
        "public/VegEvery-backet/recipes/step_image/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg";
    } else {
      $image_url =
        "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/" .
        "public/VegEvery-backet/recipes/step_image/victoria-shes-4MEL9XS-3JQ-unsplash.jpg";
    }
    return [
      'order' => fake()->unique()->randomNumber(3),
      'image_path' => $image_path,
      'image_url' => $image_url,
      'text' => fake()->realText($maxNbChars = 100),
      'article_of_recipe_id' => ArticleOfRecipe::factory()
    ];
  }
}
