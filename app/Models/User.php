<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens;
  use HasFactory;
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'password',
    'secret_question',
    'answer_to_secret_question',
    'vegetarian_type',
    'icon',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'answer_to_secret_question',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   * @return HasMany
   */
  protected $casts = [
    'password' => 'hashed',
  ];

  public function articlesOfRecipe(): HasMany
  {
    return $this->hasMany(ArticleOfRecipe::class);
  }

  public function articlesOfItem(): HasMany
  {
    return $this->hasMany(ArticleOfItem::class);
  }
}
