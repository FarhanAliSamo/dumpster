<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillingDetail;
use App\Models\DeliveryDetail;
use Illuminate\Support\Facades\Validator;

class FormSubmissionController extends Controller
{
    /**
     * Store Billing + Delivery details
     */
    public function store(Request $request)
    {
        // ✅ Validate request data
        $validator = Validator::make($request->all(), [
            // Billing
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'company' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:150',

            // Delivery
            'delivery_date' => 'required|date',
            'delivery_preference' => 'nullable|in:AM,PM',
            'expected_rental_days' => 'required|string',
            'property_type' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'placement_instructions' => 'nullable|string',
            'placement_map_picture' => 'nullable|string', // can be image URL or path
            'comments' => 'nullable|string',
            'site_contact_name' => 'required|string|max:150',
            'site_contact_phone' => 'required|string|max:20',
            'call_prior_to_arrival' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // ✅ Create Billing record
            $billing = BillingDetail::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company' => $request->company,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            // ✅ Create Delivery record linked to Billing
            $delivery = DeliveryDetail::create([
                'billing_id' => $billing->id,
                'delivery_date' => $request->delivery_date,
                'delivery_preference' => $request->delivery_preference ?? 'AM',
                'expected_rental_days' => $request->expected_rental_days,
                'property_type' => $request->property_type,
                'street_address' => $request->street_address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'placement_instructions' => $request->placement_instructions,
                'placement_map_picture' => $request->placement_map_picture,
                'comments' => $request->comments,
                'site_contact_name' => $request->site_contact_name,
                'site_contact_phone' => $request->site_contact_phone,
                'call_prior_to_arrival' => $request->call_prior_to_arrival ?? false,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Form submitted successfully',
                'data' => [
                    'billing' => $billing,
                    'delivery' => $delivery,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error submitting form',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
