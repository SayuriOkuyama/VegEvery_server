<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleOfRecipeTag extends Model
{
  use HasFactory;

  protected $table = 'article_of_recipe_tag';

  protected $fillable = [
    'article_of_recipe_id',
    'tag_id',
  ];
}
