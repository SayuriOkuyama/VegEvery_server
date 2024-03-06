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
    return [
      'order' => fake()->numberBetween($min = 1, $max = 5),
      'image' => fake()->imageUrl($width = 300, $height = 300),
      'text' => fake()->realText($maxNbChars = 100),
      'article_of_recipe_id' => ArticleOfRecipe::factory()
    ];
  }
}
