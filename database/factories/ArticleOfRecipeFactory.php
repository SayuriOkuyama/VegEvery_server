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
    return [
      'title' => fake()->realText($maxNbChars = 20),
      'thumbnail' => fake()->imageUrl($width = 300, $height = 300),
      'cooking_time' => fake()->randomElement([10, 15, 20, 30, 40, 50, 60]),
      'number_of_likes' => fake()->numberBetween($min = 0, $max = 500),
      'servings' => fake()->numberBetween($min = 1, $max = 4),
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
