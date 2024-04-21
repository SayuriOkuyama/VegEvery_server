<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CommentToRecipe extends Model
{
  use HasFactory;

  protected $table = 'comments_to_recipe';

  protected $fillable = [
    'article_of_recipe_id',
    'user_id',
    'number_of_likes',
    'text',
  ];

  public function articlesOfRecipe(): BelongsTo
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }

  public function users(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  // public function likes(): MorphMany
  // {
  //   return $this->morphMany(Like::class, 'likeable');
  // }
}
