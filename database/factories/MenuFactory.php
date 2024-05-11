<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->realText(10),
      'price' => fake()->numberBetween(100, 5000),
      'review_id' => Review::factory(),
      'vegan' => fake()->randomElement([true, false]),
      'oriental_vegetarian' => fake()->randomElement([true, false]),
      'ovo_vegetarian' => fake()->randomElement([true, false]),
      'pescatarian' => fake()->randomElement([true, false]),
      'lacto_vegetarian' => fake()->randomElement([true, false]),
      'pollo_vegetarian' => fake()->randomElement([true, false]),
      'fruitarian' => fake()->randomElement([true, false]),
      'other_vegetarian' => fake()->randomElement([true, false]),
    ];
  }
}
