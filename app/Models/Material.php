<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_recipe_id',
    'name',
    'quantity',
    'unit',
  ];

  public function articleOfRecipe()
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }

  public static function search($keyword)
  {
    if (empty($keyword)) {
      return static::query();
    }

    $search_columns = ['name'];

    $search_query = static::query();

    foreach ($search_columns as $column) {
      $search_query->orWhereRaw("$column &@~ ?", [$keyword]);
    }

    return $search_query;
  }
}
