<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeStep extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_recipe_id',
    'order',
    'text',
    'image_path',
    'image_url',
  ];

  public function articlesOfRecipe(): BelongsTo
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }
}
