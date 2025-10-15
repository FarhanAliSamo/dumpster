<?php

namespace App\Exports;

use App\Models\ZipCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ZipCodeExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ZipCode::with('county.state')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'ZIP Code',
            'City',
            'County',
            'State',
            'Special Price',
            'Created At',
            'Updated At',
        ];
    }

    public function map($zip): array
    {
        return [
            $zip->id,
            $zip->zip,
            $zip->city,
            optional($zip->county)->name,
            optional(optional($zip->county)->state)->name,
            $zip->special_price,
            $zip->created_at,
            $zip->updated_at,
        ];
    }
}
