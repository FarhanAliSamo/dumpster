<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZipCode;
use App\Models\Container;
use App\Models\Material;
use App\Models\Addon;
use App\Models\State;

class CalculationController extends Controller
{
    public function finalPrice(Request $request)
    {
        $zip = $request->zip;
        $materialIds = $request->material_id;
        $containerId = $request->container_id;
        $addons = $request->addons ?? [];

        if (!is_array($addons)) {
            $addons = [$addons]; 
        }

        // 1️⃣ ZIP code & County + State fetch
        $zipRecord = ZipCode::where('zip', $zip)
            ->with(['county.state'])
            ->first();

        if (!$zipRecord) {
            return response()->json([
                'error' => 'Invalid ZIP code'
            ], 422);
        }

        // 2️⃣ Base price logic
        $basePrice = $zipRecord->special_price ?? ($zipRecord->county->base_price ?? 0);

        // 3️⃣ Container details
        $container = Container::findOrFail($containerId);
        $containerPrice = $container->price ?? 0;

        // 4️⃣ Material details
        $materials = Material::whereIn('id', $materialIds)->get(['id', 'name', 'price_modifier']);
        $materialPrice = $materials->sum('price_modifier');

        // 5️⃣ Add-ons details
        $addonsList = Addon::whereIn('id', $addons)->get(['id', 'name', 'price']);
        $addonsPrice = $addonsList->sum('price');

        // 6️⃣ Final total
        $totalPrice = $basePrice + $containerPrice + $materialPrice + $addonsPrice;

        // 7️⃣ County & State info
        $county = $zipRecord->county;
        $state = $county->state ?? null;
 

        // 8️⃣ Response
        return response()->json([
            'total_price' => $totalPrice,
            'breakdown' => [
                'base_price' => $basePrice,
                'container_price' => $containerPrice,
                'materials_total' => $materialPrice,
                'addons_total' => $addonsPrice,
                'materials_count' => count($materialIds),
                'addons_count' => count($addons),
            ],
            'region' => [
                'zip' => $zipRecord->zip,
                'city' => $zipRecord->city,
                'special_price' => $zipRecord->special_price,
                'county' => [
                    'id' => $county->id,
                    'name' => $county->name,
                    'base_price' => $county->base_price,
                ],
                'state' => $state ? [
                    'id' => $state->id,
                    'name' => $state->name,
                    'code' => $state->code ?? null,
                ] : null,
            ],
            'details' => [
                'container' => [
                    'id' => $container->id,
                    'name' => $container->size_name ?? null,
                    'price' => $containerPrice,
                    'description' => $container->description ?? null,
                ],
                'materials' => $materials,
                'addons' => $addonsList,
            ]
        ]);
    }
}
