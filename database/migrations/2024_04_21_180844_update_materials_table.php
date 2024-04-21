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
    Schema::table('materials', function (Blueprint $table) {
      // 外部キー制約の削除
      $table->dropForeign(['article_of_recipe_id']);
      // 新たな外部キー制約の追加
      $table->foreign('article_of_recipe_id')->references('id')->on('articles_of_recipe')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('materials', function (Blueprint $table) {
      $table->dropForeign(['article_of_recipe_id']);
      $table->foreign('article_of_recipe_id')->references('id')->on('articles_of_recipe');
    });
  }
};
