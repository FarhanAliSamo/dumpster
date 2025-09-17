<?php

namespace App\Imports;

use App\Models\County;
use App\Models\ZipCode;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CountyZipImport implements ToCollection, WithHeadingRow
{
    /**
     * $rows is a collection of rows. WithHeadingRow will convert headings to lower_snake keys.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // normalize keys (depends on your sheet headings: county,state,base_price,zip,special_price)
            $countyName    = isset($row['county']) ? trim($row['county']) : (isset($row['county_name']) ? trim($row['county_name']) : null);
            $state         = isset($row['state']) ? trim($row['state']) : null;
            $basePrice     = $row['base_price'] ?? null;
            $zip           = isset($row['zip']) ? trim((string) $row['zip']) : null;
            $specialPrice  = $row['special_price'] ?? null;

            // skip invalid rows
            if (empty($countyName) || empty($zip)) {
                continue;
            }

            // Create or update county. This will update base_price from sheet.
            $county = County::updateOrCreate(
                [
                    'name'  => $countyName,
                    'state' => $state,
                ],
                [
                    'base_price' => $basePrice,
                ]
            );

            // Create or update zipcode for that county
            ZipCode::updateOrCreate(
                [
                    'zip'       => $zip,
                    'county_id' => $county->id,
                ],
                [
                    'special_price' => $specialPrice,
                ]
            );
        }
    }
}
