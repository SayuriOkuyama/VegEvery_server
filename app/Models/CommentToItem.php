<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentToItem extends Model
{
  use HasFactory;

  protected $table = 'comments_to_item';

  protected $fillable = [
    'article_of_item_id',
    'user_id',
    'number_of_likes',
    'text',
  ];

  public function articlesOfItem()
  {
    return $this->belongsTo(ArticleOfItem::class);
  }
}
