<?php

namespace Database\Factories;

use App\Models\ArticleOfRecipe;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleOfRecipeTag>
 */
class ArticleOfRecipeTagFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $articleOfRecipeIds  = ArticleOfRecipe::pluck('id')->all();
    $tagIDs  = Tag::pluck('id')->all();

    return [
      'article_of_recipe_id' => fake()->randomElement($articleOfRecipeIds),
      'tag_id' => fake()->randomElement($tagIDs)
    ];
  }
}
