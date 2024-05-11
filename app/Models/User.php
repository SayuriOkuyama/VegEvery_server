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
    'account_id',
    'name',
    'password',
    'secret_question',
    'answer_to_secret_question',
    'vegetarian_type',
    'icon_url',
    'icon_storage_path',
  ];

  /**
   * JSONに変換するときに結果に含まれないようにしたいカラム
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'answer_to_secret_question',
  ];

  /**
   * 自動的にハッシュ化する
   *
   * @var array<string, string>
   * @return HasMany
   */
  protected $casts = [
    'password' => 'hashed',
    'answer_to_secret_question',
  ];

  public function articlesOfRecipe(): HasMany
  {
    return $this->hasMany(ArticleOfRecipe::class);
  }

  public function articlesOfItem(): HasMany
  {
    return $this->hasMany(ArticleOfItem::class);
  }

  public function socialAccounts(): HasMany
  {
    return $this->hasMany(SocialAccount::class);
  }
}
