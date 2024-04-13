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
    Schema::table('menus', function (Blueprint $table) {
      $table->boolean('vegan')->default(false);
      $table->boolean('oriental_vegetarian')->default(false);
      $table->boolean('ovo_vegetarian')->default(false);
      $table->boolean('pescatarian')->default(false);
      $table->boolean('lacto_vegetarian')->default(false);
      $table->boolean('pollo_vegetarian')->default(false);
      $table->boolean('fruitarian')->default(false);
      $table->boolean('other_vegetarian')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('menus', function (Blueprint $table) {
      $table->dropColumn('vegan');
      $table->dropColumn('oriental_vegetarian');
      $table->dropColumn('ovo_vegetarian');
      $table->dropColumn('pescatarian');
      $table->dropColumn('lacto_vegetarian');
      $table->dropColumn('pollo_vegetarian');
      $table->dropColumn('fruitarian');
      $table->dropColumn('other_vegetarian');
    });
  }
};
