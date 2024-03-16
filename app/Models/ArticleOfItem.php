<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleOfItem extends Model
{
  use HasFactory;

  protected $table = 'articles_of_item';

  protected $fillable = [
    'title',
    'thumbnail_path',
    'thumbnail_url',
    'number_of_likes',
    'user_id',
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

  public function items()
  {
    return $this->hasMany(Item::class);
  }

  public function reports()
  {
    return $this->hasMany(Report::class);
  }

  public function commentsToItem()
  {
    return $this->hasMany(CommentToItem::class);
  }

  public function tags()
  {
    return $this->belongsToMany(Tag::class);
  }
}
