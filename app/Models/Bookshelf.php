<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookshelf extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'name',
  ];

  public function users()
  {
    return $this->belongsTo(User::class);
  }
}
