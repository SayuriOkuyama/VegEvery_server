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
      'vegan' => fake()->randomElement([1, 2]),
      'oriental_vegetarian' => fake()->randomElement([1, 2]),
      'ovo_vegetarian' => fake()->randomElement([1, 2]),
      'pescatarian' => fake()->randomElement([1, 2]),
      'lacto_vegetarian' => fake()->randomElement([1, 2]),
      'pollo_vegetarian' => fake()->randomElement([1, 2]),
      'fruitarian' => fake()->randomElement([1, 2]),
      'other_vegetarian' => fake()->randomElement([1, 2]),
      'user_id' => User::factory()
    ];
  }
}
