<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'display_name' => fake()->sentence($nbWords = 2, $variableNbWords = true),
      'star' => fake()->numberBetween($min = 1, $max = 5),
      'thumbnail' => fake()->imageUrl($width = 300, $height = 300),
      'text' => fake()->realText($maxNbChars = 300),
      'number_of_likes' => fake()->numberBetween($min = 0, $max = 500),
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
