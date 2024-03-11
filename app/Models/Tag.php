<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
  ];

  public function articlesOfRecipe()
  {
    return $this->belongsToMany(ArticleOfRecipe::class);
  }

  public function articlesOfItem()
  {
    return $this->belongsToMany(ArticleOfItem::class);
  }

  public function reviews()
  {
    return $this->belongsToMany(Review::class);
  }
}
