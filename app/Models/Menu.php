<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  use HasFactory;

  protected $fillable = [
    'review_id',
    'name',
    'price',
  ];

  public function review()
  {
    return $this->belongsTo(Review::class);
  }
}
