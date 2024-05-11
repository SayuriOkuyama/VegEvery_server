<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArticleOfItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
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
      'where_to_buy' => fake()->realText(10),
      'price' => fake()->numberBetween(100, 5000),
      'article_of_item_id' => ArticleOfItem::factory()
    ];
  }
}
