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
    Schema::create('comments_to_recipe', function (Blueprint $table) {
      $table->id();
      $table->foreignId('article_of_recipe_id')->constrained('articles_of_recipe');
      $table->foreignId('user_id')->constrained('users');
      $table->integer('number_of_likes');
      $table->string('text');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('comments_to_recipe');
  }
};
