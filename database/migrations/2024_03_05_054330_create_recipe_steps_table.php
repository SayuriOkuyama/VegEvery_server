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
    Schema::create('recipe_steps', function (Blueprint $table) {
      $table->id();
      $table->foreignId('article_of_recipe_id')->constrained('articles_of_recipe');
      $table->integer('order');
      $table->string('text');
      $table->string('image');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('recipe_steps');
  }
};
