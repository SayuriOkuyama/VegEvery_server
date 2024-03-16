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
    Schema::table('articles_of_item', function (Blueprint $table) {
      $table->renameColumn('thumbnail', 'thumbnail_path');
      $table->string('thumbnail_url')->after('thumbnail_path');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('articles_of_item', function (Blueprint $table) {
      $table->renameColumn('thumbnail_path', 'thumbnail');
      $table->dropColumn('thumbnail_url');
    });
  }
};
