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
    Schema::create('bookshelf_article_of_recipe', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bookshelf_id')->constrained('bookshelves');
      $table->foreignId('article_of_recipe_id')->constrained('articles_of_recipe');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bookshelf_article_of_recipe');
  }
};
