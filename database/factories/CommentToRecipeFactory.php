<?php

namespace Database\Factories;

use App\Models\ArticleOfRecipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentToRecipe>
 */
class CommentToRecipeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'number_of_likes' => fake()->numberBetween($min = 0, $max = 500),
      'text' => fake()->realText($maxNbChars = 100),
      'article_of_recipe_id' => ArticleOfRecipe::factory(),
      'user_id' => User::factory()
    ];
  }
}
