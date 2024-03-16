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
    Schema::table('articles_of_recipe', function (Blueprint $table) {
      $table->string('servings')->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('articles_of_recipe', function (Blueprint $table) {
      DB::statement('ALTER TABLE articles_of_recipe ALTER COLUMN
      servings TYPE integer USING (trim(servings))::integer');
    });
  }
};
