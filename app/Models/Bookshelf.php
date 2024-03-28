<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookshelf extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'name',
  ];

  public function users(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
