<?php

namespace Database\Factories;

use App\Models\ArticleOfItem;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleOfItemTag>
 */
class ArticleOfItemTagFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $articleOfItemIds  = ArticleOfItem::pluck('id')->all();
    $tagIDs  = Tag::pluck('id')->all();

    return [
      'article_of_item_id' => fake()->randomElement($articleOfItemIds),
      'tag_id' => fake()->randomElement($tagIDs)
    ];
  }
}
