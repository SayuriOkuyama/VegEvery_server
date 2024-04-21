<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Review extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'restaurant_id',
    'thumbnail_path',
    'thumbnail_url',
    'star',
    'text',
    'number_of_likes',
  ];


  public function users(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }


  public function restaurant(): BelongsTo
  {
    return $this->belongsTo(Restaurant::class);
  }

  public function menus(): HasMany
  {
    return $this->hasMany(Menu::class);
  }

  // public function likes(): MorphMany
  // {
  //   return $this->morphMany(Like::class, 'likeable');
  // }
}
