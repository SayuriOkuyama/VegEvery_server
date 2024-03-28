<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
  ];

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

  /**
   *
   * @return BelongsToMany
   */
  public function reviews(): BelongsToMany
  {
    return $this->belongsToMany(Review::class);
  }
}
