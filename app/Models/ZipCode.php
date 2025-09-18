<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    protected $fillable = [
        'zip',
        'county_id',
        'special_price',
        'city'
    ];
    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
