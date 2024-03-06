<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArticleOfItem;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentToItem>
 */
class CommentToItemFactory extends Factory
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
      'article_of_item_id' => ArticleOfItem::factory(),
      'user_id' => User::factory()
    ];
  }
}
