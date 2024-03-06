<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleOfItemTag extends Model
{
  use HasFactory;

  protected $table = 'article_of_item_tag';

  protected $fillable = [
    'article_of_item_id',
    'tag_id',
  ];
}
