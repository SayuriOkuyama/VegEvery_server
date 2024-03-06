<?php

namespace Database\Factories;

use App\Models\ArticleOfItem;
use App\Models\Bookshelf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookshelfArticleOfItem>
 */
class BookshelfArticleOfItemFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $bookshelfIds = Bookshelf::pluck('id')->all();
    $articleOfItemIds  = ArticleOfItem::pluck('id')->all();

    return [
      'bookshelf_id' => fake()->randomElement($bookshelfIds),
      'article_of_item_id' => fake()->randomElement($articleOfItemIds),
    ];
  }
}
