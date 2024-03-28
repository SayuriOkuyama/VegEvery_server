<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

  public function articleOfItem(): BelongsTo
  {
    return $this->belongsTo(ArticleOfItem::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function likes(): MorphMany
  {
    return $this->morphMany(Like::class, 'likeable');
  }
}
