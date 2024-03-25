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
    Schema::create('restaurants', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('place_id');
      $table->string('latitude');
      $table->string('longitude');
      $table->integer('star');
      $table->boolean('vegan')->default(false);
      $table->boolean('oriental_vegetarian')->default(false);
      $table->boolean('ovo_vegetarian')->default(false);
      $table->boolean('pescatarian')->default(false);
      $table->boolean('lacto_vegetarian')->default(false);
      $table->boolean('pollo_vegetarian')->default(false);
      $table->boolean('fruitarian')->default(false);
      $table->boolean('other_vegetarian')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('restaurants');
  }
};
