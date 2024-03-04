<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_recipe_id',
    'user_id',
    'number_of_likes',
    'text',
  ];

  public function bookshelf()
  {
    return $this->belongsTo(Bookshelf::class);
  }
}
