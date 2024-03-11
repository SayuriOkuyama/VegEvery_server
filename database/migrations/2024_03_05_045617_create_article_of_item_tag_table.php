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
    Schema::create('article_of_item_tag', function (Blueprint $table) {
      $table->id();
      $table->foreignId('article_of_item_id')->constrained('articles_of_item');
      $table->foreignId('tag_id')->constrained('tags');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('article_of_item_tag');
  }
};
