<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'place_id',
    'latitude',
    'longitude',
    'star',
    'vegan',
    'oriental_vegetarian',
    'ovo_vegetarian',
    'pescatarian',
    'lacto_vegetarian',
    'pollo_vegetarian',
    'fruitarian',
    'other_vegetarian',
  ];

  public function reviews()
  {
    return $this->hasMany(Review::class);
  }
}
