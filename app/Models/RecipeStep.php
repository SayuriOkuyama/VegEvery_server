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
    'image_path',
    'image_url',
  ];

  public function articlesOfRecipe()
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }

  public static function search($keyword)
  {
    if (empty($keyword)) {
      return static::query();
    }

    $search_columns = ['text'];

    $search_query = static::query();

    foreach ($search_columns as $column) {
      $search_query->orWhereRaw("$column &@~ ?", [$keyword]);
    }

    return $search_query;
  }
}
