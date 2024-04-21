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
    Schema::table('comments_to_item', function (Blueprint $table) {
      // 外部キー制約の削除
      $table->dropForeign(['article_of_item_id']);
      $table->dropForeign(['user_id']);
      // 新たな外部キー制約の追加
      $table->foreign('article_of_item_id')->references('id')->on('articles_of_item')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('comments_to_item', function (Blueprint $table) {
      $table->dropForeign(['article_of_item_id']);
      $table->dropForeign(['user_id']);
      $table->foreign('article_of_item_id')->references('id')->on('articles_of_item');
      $table->foreign('user_id')->references('id')->on('users');
    });
  }
};
