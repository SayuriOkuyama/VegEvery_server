<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookshelf extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'name',
  ];

  public function users(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   *
   * @return BelongsToMany
   */
  public function articlesOfRecipe(): BelongsToMany
  {
    return $this->belongsToMany(ArticleOfRecipe::class);
  }

  /**
   *
   * @return BelongsToMany
   */
  public function articlesOfItem(): BelongsToMany
  {
    return $this->belongsToMany(ArticleOfItem::class);
  }
}
