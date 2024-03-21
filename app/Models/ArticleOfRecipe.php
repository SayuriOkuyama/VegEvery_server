<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleOfRecipe extends Model
{
  use HasFactory;

  protected $table = 'articles_of_recipe';

  protected $fillable = [
    'title',
    'thumbnail_path',
    'thumbnail_url',
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

  public function materials()
  {
    return $this->hasMany(Material::class);
  }

  public function recipeSteps()
  {
    return $this->hasMany(RecipeStep::class);
  }

  public function commentsToRecipe()
  {
    return $this->hasMany(CommentToRecipe::class);
  }

  public function tags()
  {
    return $this->belongsToMany(Tag::class);
  }

  public static function search($keyword)
  {
    if (empty($keyword)) {
      return static::query();
    }

    // $search_columns = ['title'];

    $search_query = static::query();

    // foreach ($search_columns as $column) {
    $search_query->orWhereRaw("title &@~ ?", [$keyword]);
    // }

    return $search_query;
  }
}
