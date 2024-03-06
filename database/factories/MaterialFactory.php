<?php

namespace Database\Factories;

use App\Models\ArticleOfRecipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->word(),
      'quantity' => fake()->numberBetween($min = 1, $max = 100),
      'unit' => fake()->randomElement(['杯（小さじ）', '杯（大さじ）', '本', '枚', '個', '片', 'カップ', 'cc', 'mL', 'L', 'g', 'mg']),
      'article_of_recipe_id' => ArticleOfRecipe::factory()
    ];
  }
}
