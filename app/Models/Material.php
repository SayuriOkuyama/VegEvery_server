<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
  use HasFactory;

  protected $fillable = [
    'article_of_recipe_id',
    'name',
    'quantity',
    'unit',
  ];

  public function articleOfRecipe(): BelongsTo
  {
    return $this->belongsTo(ArticleOfRecipe::class);
  }
}
