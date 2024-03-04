<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentToRecipe extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_recipe_id',
    'user_id',
    'number_of_likes',
    'text',
  ];

  public function articlesOfRecipe()
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }
}
