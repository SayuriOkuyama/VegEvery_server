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
    Schema::drop('favorites');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::create('favorites', function (Blueprint $table) {
      $table->id();
      $table->foreignId('bookshelf_id')->constrained('bookshelves');
      $table->string('article_type');
      $table->bigInteger('article_id');
      $table->timestamps();
    });
  }
};
