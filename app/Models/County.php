<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
      protected $fillable = [
        'name',
        'state_id',
        'base_price'
    ];
    // Relationships
    public function zipCodes()
    {   
        return $this->hasMany(ZipCode::class);
    }
 
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
