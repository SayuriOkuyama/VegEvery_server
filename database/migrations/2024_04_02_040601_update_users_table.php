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
    Schema::table('users', function (Blueprint $table) {
      $table->renameColumn('icon', 'icon_url')->nullable()->change();
      $table->string('icon_storage_path')->nullable()->after('icon_url');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->renameColumn('icon_url', 'icon')->nullable(false)->change();
      $table->dropColumn('icon_storage_path');
    });
  }
};
