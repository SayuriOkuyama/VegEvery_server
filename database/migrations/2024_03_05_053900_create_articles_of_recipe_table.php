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
    Schema::create('articles_of_recipe', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users');
      $table->string('title');
      $table->string('thumbnail');
      $table->integer('cooking_time');
      $table->integer('number_of_likes');
      $table->integer('servings');
      $table->boolean('vegan');
      $table->boolean('oriental_vegetarian');
      $table->boolean('ovo_vegetarian');
      $table->boolean('pescatarian');
      $table->boolean('lacto_vegetarian');
      $table->boolean('pollo_vegetarian');
      $table->boolean('fruitarian');
      $table->boolean('other_vegetarian');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('articles_of_recipe');
  }
};
