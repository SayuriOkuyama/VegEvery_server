<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookshelfArticleOfRecipe extends Model
{
  use HasFactory;

  protected $table = 'bookshelf_article_of_recipe';

  protected $fillable = [
    'bookshelf_id',
    'article_of_recipe_id',
  ];

  public function articlesOfRecipe(): BelongsTo
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }
}
