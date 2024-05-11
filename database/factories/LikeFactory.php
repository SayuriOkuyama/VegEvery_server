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
    $likeableType = $this->faker->randomElement([
      'ArticleOfRecipe',
      'ArticleOfItem',
      'Review',
      'CommentToRecipe',
      'CommentToItem'
    ]);
    $likeableId = null;

    if ($likeableType === "ArticleOfRecipe") {
      $likeableId = ArticleOfRecipe::factory();
    } elseif ($likeableType === "ArticleOfItem") {
      $likeableId = ArticleOfItem::factory();
    } elseif ($likeableType === "Review") {
      $likeableId = Review::factory();
    } elseif ($likeableType === "CommentToRecipe") {
      $likeableId = CommentToRecipe::factory();
    } elseif ($likeableType === "CommentToItem") {
      $likeableId = CommentToItem::factory();
    }

    return [
      'user_id' => User::factory(),
      'likeable_id' => $likeableId,
      'likeable_type' => $likeableType
    ];
  }
}
