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
}
