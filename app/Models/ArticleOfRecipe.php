<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleOfRecipe extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'thumbnail',
    'cooking_time',
    'number_of_likes',
    'user_id',
    'material_id',
    'servings',
    'vegan',
    'oriental_vegetarian',
    'ovo_vegetarian',
    'pescatarian',
    'lacto_vegetarian',
    'pollo_vegetarian',
    'fruitarian',
    'other_vegetarian',
  ];


  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function material()
  {
    return $this->hasMany(Material::class);
  }

  public function recipeSteps()
  {
    return $this->hasMany(Material::class);
  }

  public function commentsToRecipe()
  {
    return $this->hasMany(Material::class);
  }

  public function tags()
  {
    return $this->belongsToMany(Material::class);
  }
}
