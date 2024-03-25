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
    Schema::table('reviews', function (Blueprint $table) {
      $table->dropColumn('display_name');
      $table->foreignId('restaurant_id')->constrained('restaurants');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('reviews', function (Blueprint $table) {
      $table->string('display_name');
      $table->dropColumn('restaurant_id');
    });
  }
};
