<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
  protected $fillable = [
        'size_name',
        'image',
        'description',
        'price',
    ];
}
