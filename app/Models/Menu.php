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
  ];

  public function reviews(): BelongsTo
  {
    return $this->belongsTo(Review::class);
  }
}
