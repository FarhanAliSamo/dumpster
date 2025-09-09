<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = ['zip_code_id','material_id','addon_id','base_price'];

    public function zipCode()
    {
        return $this->belongsTo(ZipCode::class);
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
    
}
