<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ArticleOfItem extends Model
{
  use HasFactory;

  protected $table = 'articles_of_item';

  protected $fillable = [
    'title',
    'thumbnail_path',
    'thumbnail_url',
    'number_of_likes',
    'user_id',
    'vegan',
    'oriental_vegetarian',
    'ovo_vegetarian',
    'pescatarian',
    'lacto_vegetarian',
    'pollo_vegetarian',
    'fruitarian',
    'other_vegetarian',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function items(): HasMany
  {
    return $this->hasMany(Item::class);
  }

  public function reports(): HasMany
  {
    return $this->hasMany(Report::class);
  }

  public function commentsToItem(): HasMany
  {
    return $this->hasMany(CommentToItem::class);
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class);
  }

  public function likes(): MorphMany
  {
    return $this->morphMany(Like::class, 'likeable');
  }
}
