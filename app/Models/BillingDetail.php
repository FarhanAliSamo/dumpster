<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'phone',
        'email',
    ];

    public function deliveryDetail()
    {
        return $this->hasOne(DeliveryDetail::class, 'billing_id');
    }
}
