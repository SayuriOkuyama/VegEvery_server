<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'display_name',
    'thumbnail_path',
    'thumbnail_url',
    'star',
    'text',
    'number_of_likes',
    'vegan',
    'oriental_vegetarian',
    'ovo_vegetarian',
    'pescatarian',
    'lacto_vegetarian',
    'pollo_vegetarian',
    'fruitarian',
    'other_vegetarian',
  ];


  public function users()
  {
    return $this->belongsTo(User::class);
  }

  public function menus()
  {
    return $this->hasMany(Menu::class);
  }

  public function likes()
  {
    return $this->morphMany(Like::class, 'likeable');
  }
}
