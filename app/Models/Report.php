<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

  public function articlesOfItem(): BelongsTo
  {
    return $this->belongsTo(ArticleOfItem::class);
  }

  public function articlesOfRecipe(): BelongsTo
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }
}
