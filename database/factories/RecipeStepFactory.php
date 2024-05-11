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
    $imagePath = fake()->randomElement([
      "recipes/step_images/brooke-lark-kXQ3J7_2fpc-unsplash.jpg",
      "recipes/step_images/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg",
      "recipes/step_images/victoria-shes-4MEL9XS-3JQ-unsplash.jpg"
    ]);

    $imageUrl = "https://static.vegevery.my-raga-bhakti.com/" . $imagePath;

    return [
      'order' => fake()->unique()->randomNumber(3),
      'image_path' => $imagePath,
      'image_url' => $imageUrl,
      'text' => fake()->realText(100),
      'article_of_recipe_id' => ArticleOfRecipe::factory()
    ];
  }
}
