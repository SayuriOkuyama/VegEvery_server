<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeStep extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_recipe_id',
    'order',
    'text',
    'image',
  ];

  public function articlesOfRecipe()
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }
}
