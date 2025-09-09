<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
      protected $fillable = [
        'name',
        'state',
        'base_price'
    ];
    //
    public function zipCodes()
    {
        return $this->hasMany(ZipCode::class);
    }
}
