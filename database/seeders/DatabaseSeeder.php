<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ArticleOfItem;
use App\Models\ArticleOfItemTag;
use App\Models\ArticleOfRecipe;
use App\Models\ArticleOfRecipeTag;
use App\Models\Bookshelf;
use App\Models\BookshelfArticleOfItem;
use App\Models\BookshelfArticleOfRecipe;
use App\Models\CommentToItem;
use App\Models\CommentToRecipe;
use App\Models\Item;
use App\Models\Like;
use App\Models\Material;
use App\Models\Menu;
use App\Models\RecipeStep;
use App\Models\Report;
use App\Models\Review;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $users = User::factory(30)->create();
    $articleOfItem = ArticleOfItem::factory(100)->recycle($users)->create();
    $articleOfRecipe = ArticleOfRecipe::factory(100)->recycle($users)->create();

    Tag::factory(20)->create();

    ArticleOfItemTag::factory(200)->create();
    ArticleOfRecipeTag::factory(200)->create();

    RecipeStep::factory(300)->recycle($articleOfRecipe)->create();
    Material::factory(300)->recycle($articleOfRecipe)->create();
    $commentToRecipe = CommentToRecipe::factory(300)->recycle($users)->recycle($articleOfRecipe)->create();

    Item::factory(300)->recycle($articleOfItem)->create();
    Report::factory(300)->recycle($articleOfItem)->create();
    $commentToItem = CommentToItem::factory(500)->recycle($users)->recycle($articleOfItem)->create();

    $review = Review::factory(100)->recycle($users)->create();
    Menu::factory(200)->recycle($review)->create();

    Bookshelf::factory(100)->recycle($users)->create();
    BookshelfArticleOfRecipe::factory(50)->create();
    BookshelfArticleOfItem::factory(50)->create();

    Like::factory(1000)->recycle($users)->recycle($articleOfItem)->recycle($articleOfRecipe)->recycle($review)->recycle($commentToRecipe)->recycle($commentToItem)->create();
  }
}
