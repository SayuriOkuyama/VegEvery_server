<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookshelfArticleOfItem extends Model
{
  use HasFactory;

  protected $table = 'bookshelf_article_of_item';

  protected $fillable = [
    'bookshelf_id',
    'article_of_item_id',
  ];

  public function articlesOfItem(): BelongsTo
  {
    return $this->belongsTo(ArticleOfItem::class);
  }
}
