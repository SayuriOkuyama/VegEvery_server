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
      $table->dropColumn('vegan');
      $table->dropColumn('oriental_vegetarian');
      $table->dropColumn('ovo_vegetarian');
      $table->dropColumn('pescatarian');
      $table->dropColumn('lacto_vegetarian');
      $table->dropColumn('pollo_vegetarian');
      $table->dropColumn('fruitarian');
      $table->dropColumn('other_vegetarian');
      $table->renameColumn('thumbnail', 'thumbnail_path')->change();
      $table->string('thumbnail_url')->after('thumbnail_path');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('reviews', function (Blueprint $table) {
      $table->renameColumn('thumbnail_path', 'thumbnail')->change();
      $table->dropColumn('thumbnail_url');
      $table->boolean('vegan');
      $table->boolean('oriental_vegetarian');
      $table->boolean('ovo_vegetarian');
      $table->boolean('pescatarian');
      $table->boolean('lacto_vegetarian');
      $table->boolean('pollo_vegetarian');
      $table->boolean('fruitarian');
      $table->boolean('other_vegetarian');
    });
  }
};
