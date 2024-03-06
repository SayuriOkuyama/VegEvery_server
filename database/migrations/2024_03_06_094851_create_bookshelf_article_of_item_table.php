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
    Schema::create('bookshelf_article_of_item', function (Blueprint $table) {
      $table->foreignId('bookshelf_id')->constrained('bookshelves');
      $table->foreignId('article_of_item_id')->constrained('articles_of_item');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bookshelf_article_of_item');
  }
};
