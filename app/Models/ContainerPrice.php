<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'county_id',
        'zip_code_id',
        'county_price',
        'zip_price',
        'weight_zip',
        'rental_zip',
        'weight_county',
        'rental_county',
        'base_price',
        
    ];

    // Reverse relation
    public function container()
    {
        return $this->belongsTo(Container::class);
    }
    public function county()
    {
        return $this->belongsTo(County::class);
    }
    public function zipcode()
    {
        return $this->belongsTo(ZipCode::class);
    }

     // 🔗 One container → many pricing rules
    // public function prices()
    // {
    //     return $this->hasMany(ContainerPrice::class);
    // }

    // // 🔗 Shortcut: county-based prices
    // public function countyPrices()
    // {
    //     return $this->hasMany(ContainerPrice::class)->whereNotNull('county_id');
    // }

    // // 🔗 Shortcut: zip-based prices
    // public function zipPrices()
    // {
    //     return $this->hasMany(ContainerPrice::class)->whereNotNull('zip_code');
    // }
}
