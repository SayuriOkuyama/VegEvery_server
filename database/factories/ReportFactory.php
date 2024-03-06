<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArticleOfItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
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
      'article_of_item_id' => ArticleOfItem::factory()
    ];
  }
}
