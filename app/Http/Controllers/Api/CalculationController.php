<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZipCode;
use App\Models\County;
use App\Models\Container;
use App\Models\Material;
use App\Models\Addon;

class CalculationController extends Controller
{
    public function finalPrice(Request $request)
    {
        
    //     return [
    //        'message' => 'This is a placeholder response. Calculation logic to be implemented.',
    //        'input' => $request->all()
    //    ];
    //     $request->validate([
    //         'zip' => 'required|max:10',
    //         'material_id' => 'required',
    //         'container_id' => 'required',
    //         'addons' => 'array' // optional
    //     ]);




        $zip = $request->zip;
        $materialId = $request->material_id;
        $containerId = $request->container_id;
        $addons = $request->addons ?? [];
        if (!is_array($addons)) {
            $addons = [$addons];
        }

        // 1. ZIP code se county nikaalo
        $zipRecord = ZipCode::where('zip', $zip)->with('county')->first();

        if (!$zipRecord) {
            return response()->json([
                'error' => 'Invalid ZIP code'
            ], 422);
        }
 
        // 2. ZIP special price check karo
        $basePrice = null;

        if ($zipRecord->special_price) {
            $basePrice = $zipRecord->special_price; // special ZIP price
        } else {
            $basePrice = $zipRecord->county->base_price ?? 0; // county price
        }

        // 3. Container price
        $containerPrice = Container::findOrFail($containerId)->price ?? 0;

        // 4. Material modifier (agar required ho to)
        $materialPrice = Material::findOrFail($materialId)->modifier_price ?? 0;

        // 5. Add-ons price
        $addonsPrice = Addon::whereIn('id', $addons)->sum('price');

        // 6. Final calculation
        $totalPrice = $basePrice + $containerPrice + $materialPrice + $addonsPrice;

        return response()->json([
            'total_price' => $totalPrice,
            'breakdown' => [
                'base_price' => $basePrice,
                'container_price' => $containerPrice,
                'material_price' => $materialPrice,
                'addons_price' => $addonsPrice
            ],
        ]);
    }
}
