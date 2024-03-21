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

  public function articleOfItem()
  {
    return $this->belongsTo(ArticleOfItem::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function likes()
  {
    return $this->morphMany(Like::class, 'likeable');
  }
}
