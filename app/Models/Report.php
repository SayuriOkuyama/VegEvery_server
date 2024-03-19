<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_item_id',
    'order',
    'image_path',
    'image_url',
    'text',
  ];

  public function articlesOfItem()
  {
    return $this->belongsTo(ArticleOfItem::class);
  }

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
