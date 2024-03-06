<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_item_id',
    'name',
    'where_to_buy',
    'price',
  ];

  public function articlesOfItem()
  {
    return $this->belongsTo(ArticleOfItem::class);
  }
}
