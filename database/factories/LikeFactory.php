<?php

namespace Database\Factories;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfRecipe;
use App\Models\CommentToItem;
use App\Models\CommentToRecipe;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $likeable_type = $this->faker->randomElement(['ArticleOfRecipe', 'ArticleOfItem', 'Review', 'CommentToRecipe', 'CommentToItem']);
    $likeable_id = null;

    if ($likeable_type === "ArticleOfRecipe") {
      $likeable_id = ArticleOfRecipe::factory();
    } elseif ($likeable_type === "ArticleOfItem") {
      $likeable_id = ArticleOfItem::factory();
    } elseif ($likeable_type === "Review") {
      $likeable_id = Review::factory();
    } elseif ($likeable_type === "CommentToRecipe") {
      $likeable_id = CommentToRecipe::factory();
    } elseif ($likeable_type === "CommentToItem") {
      $likeable_id = CommentToItem::factory();
    }

    return [
      'user_id' => User::factory(),
      'likeable_id' => $likeable_id,
      'likeable_type' => $likeable_type
    ];
  }
}
