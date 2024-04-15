<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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


  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function materials(): HasMany
  {
    return $this->hasMany(Material::class);
  }

  public function recipeSteps(): HasMany
  {
    return $this->hasMany(RecipeStep::class);
  }

  public function commentsToRecipe(): HasMany
  {
    return $this->hasMany(CommentToRecipe::class);
  }

  public function tags(): BelongsToMany
  {
    return $this->belongsToMany(Tag::class);
  }

  public function likes(): MorphMany
  {
    return $this->morphMany(Like::class, 'likeable');
  }

  public function bookshelves(): BelongsToMany
  {
    return $this->belongsToMany(Bookshelf::class, "bookshelf_article_of_recipe");
  }
}
