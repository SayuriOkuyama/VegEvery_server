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
    Schema::table('article_of_item_tag', function (Blueprint $table) {
      $table->dropForeign(['article_of_item_id']);
      $table->dropForeign(['tag_id']);
      $table->foreign('article_of_item_id')->references('id')->on('articles_of_item')->onDelete('cascade');
      $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('article_of_item_tag', function (Blueprint $table) {
      $table->dropForeign(['article_of_item_id']);
      $table->dropForeign(['tag_id']);
      $table->foreign('article_of_item_id')->references('id')->on('articles_of_item');
      $table->foreign('tag_id')->references('id')->on('tags');
    });
  }
};
