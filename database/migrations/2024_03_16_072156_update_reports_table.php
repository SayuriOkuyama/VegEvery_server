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
    Schema::table('reports', function (Blueprint $table) {
      $table->renameColumn('image', 'image_path')->nullable()->change();
      $table->string('image_url')->nullable()->after('image_path');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->renameColumn('image_path', 'image')->nullable(false)->change();
      $table->dropColumn('image_url');
    });
  }
};
