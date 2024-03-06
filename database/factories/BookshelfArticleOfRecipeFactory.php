<?php

namespace Database\Factories;

use App\Models\ArticleOfRecipe;
use App\Models\Bookshelf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookshelfArticleOfRecipe>
 */
class BookshelfArticleOfRecipeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $bookshelfIds = Bookshelf::pluck('id')->all();
    $articleOfRecipeIds  = ArticleOfRecipe::pluck('id')->all();

    return [
      'bookshelf_id' => fake()->randomElement($bookshelfIds),
      'article_of_recipe_id' => fake()->randomElement($articleOfRecipeIds),
    ];
  }
}
