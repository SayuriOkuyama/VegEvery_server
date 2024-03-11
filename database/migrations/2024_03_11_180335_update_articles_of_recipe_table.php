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
    Schema::table('articles_of_recipe', function (Blueprint $table) {
      $table->integer('number_of_likes')->default(0)->change();
      $table->string('servings')->change();
      $table->boolean('vegan')->default(false)->change();
      $table->boolean('oriental_vegetarian')->default(false)->change();
      $table->boolean('ovo_vegetarian')->default(false)->change();
      $table->boolean('pescatarian')->default(false)->change();
      $table->boolean('lacto_vegetarian')->default(false)->change();
      $table->boolean('pollo_vegetarian')->default(false)->change();
      $table->boolean('fruitarian')->default(false)->change();
      $table->boolean('other_vegetarian')->default(false)->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('articles_of_recipe', function (Blueprint $table) {
      $table->integer('number_of_likes')->change();
      $table->integer('servings')->change();
      $table->boolean('vegan')->change();
      $table->boolean('oriental_vegetarian')->change();
      $table->boolean('ovo_vegetarian')->change();
      $table->boolean('pescatarian')->change();
      $table->boolean('lacto_vegetarian')->change();
      $table->boolean('pollo_vegetarian')->change();
      $table->boolean('fruitarian')->change();
      $table->boolean('other_vegetarian')->change();
    });
  }
};
