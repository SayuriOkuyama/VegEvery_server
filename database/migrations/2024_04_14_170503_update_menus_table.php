<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('menus', function (Blueprint $table) {
      $table->string('price')->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('menus', function (Blueprint $table) {
      DB::statement('ALTER TABLE menus ALTER COLUMN
      price TYPE integer USING (trim(price))::integer');
    });
  }
};
