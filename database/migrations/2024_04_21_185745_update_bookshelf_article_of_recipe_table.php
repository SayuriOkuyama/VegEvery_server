<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('bookshelf_article_of_recipe', function (Blueprint $table) {
      $table->dropForeign(['bookshelf_id']);
      $table->dropForeign(['article_of_recipe_id']);
      $table->foreign('bookshelf_id')->references('id')->on('bookshelves')->onDelete('cascade');
      $table->foreign('article_of_recipe_id')->references('id')->on('articles_of_recipe')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('bookshelf_article_of_recipe', function (Blueprint $table) {
      $table->dropForeign(['bookshelf_id']);
      $table->dropForeign(['article_of_recipe_id']);
      $table->foreign('bookshelf_id')->references('id')->on('bookshelves');
      $table->foreign('article_of_recipe_id')->references('id')->on('articles_of_recipe');
    });
  }
};
