<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
  use HasFactory;

  protected $fillable = [
    'review_id',
    'name',
    'price',
    'vegan',
    'oriental_vegetarian',
    'ovo_vegetarian',
    'pescatarian',
    'lacto_vegetarian',
    'pollo_vegetarian',
    'fruitarian',
    'other_vegetarian',
  ];

  public function review(): BelongsTo
  {
    return $this->belongsTo(Review::class);
  }
}
