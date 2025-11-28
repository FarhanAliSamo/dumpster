<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_name',
        'image',
        'weight',
        'rental',
        'price',
    ];

    // // 🔗 One container → many pricing rules
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
    //     return $this->hasMany(ContainerPrice::class)->whereNotNull('zip_code_id');
    // }
}
