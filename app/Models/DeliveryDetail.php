<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'delivery_date',
        'delivery_preference',
        'expected_rental_days',
        'property_type',
        'street_address',
        'city',
        'state',
        'zip_code',
        'placement_instructions',
        'placement_map_picture',
        'comments',
        'site_contact_name',
        'site_contact_phone',
        'call_prior_to_arrival',
    ];

    public function billingDetail()
    {
        return $this->belongsTo(BillingDetail::class, 'billing_id');
    }
}
