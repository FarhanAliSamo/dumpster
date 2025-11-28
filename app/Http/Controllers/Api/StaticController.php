<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addon;
use App\Models\Container;
use App\Models\Material;
use App\Models\ZipCode;
use App\Models\ContainerPrice;


class StaticController extends Controller
{
    public function Addons()
    {
        $data = Addon::all();
        return response()->json($data);
    }
    // public function Containers(Request $request)
    // {
    //     $zip = $request->zip;

    //     $zipRecord = ZipCode::where('zip', $zip)
    //         ->with(['county.state'])
    //         ->first();


    //         $data = [];
    //         $container = ContainerPrice::with(['container', 'county', 'zipcode'])->where('zip_code_id', $zipRecord->id)->get(); 
    //         // $data = Container::with(['prices', 'countyPrices', 'zipPrices'])->get();

    //     return response()->json($data);
    // }

       public function Containers(Request $request)
    {
        $zip = $request->zip;

        // 1. ZIP ka record
        $zipRecord = ZipCode::where('zip', $zip)
            ->with(['county.state'])
            ->first();

        if (!$zipRecord) {
            return response()->json(['message' => 'Invalid Zip Code'], 404);
        }

        $countyId = $zipRecord->county_id;

        // 2. Pehle matching ContainerPrice records (zip ya county)
        $matchedContainerPrices = ContainerPrice::with(['container', 'county'])
            ->where('zip_code_id', $zipRecord->id)
            ->orWhere('county_id', $countyId)
            ->get();

        // 3. Matched container IDs array
        $matchedContainerIds = $matchedContainerPrices->pluck('container_id')->toArray();

        // 4. All containers (Container model se)
        $allContainers = Container::orderBy('id', 'ASC')->get();

        // 5. Merge price from other containers into matched containers
        $matchedContainerPrices = $matchedContainerPrices->map(function ($matched) use ($allContainers) {
            $other = $allContainers->firstWhere('id', $matched->container_id);
            if ($other) {

                // dd( $other->price , $matched);
                // Only price is updated from other container
                $matched->base_price = $other->price;
            }

            
            return $matched;
        });

        // 6. Remaining containers (jo matched nahi hue)
        $remainingContainers = $allContainers->filter(function ($container) use ($matchedContainerIds) {
            return !in_array($container->id, $matchedContainerIds);
        });

        // 7. Merge matched + remaining containers
        $final = $matchedContainerPrices->merge($remainingContainers)
            ->sortBy('id')
            ->values()
            ->all();

        return response()->json([
            'containers' => $final,
            'zip' => $zip,
            'county_id' => $countyId
        ]);
    }




    public function Materials()
    {
        $data = Material::all();
        return response()->json($data);
    }
}
