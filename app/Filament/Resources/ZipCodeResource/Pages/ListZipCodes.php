<?php

namespace App\Filament\Resources\ZipCodeResource\Pages;

use App\Filament\Resources\ZipCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZipCodes extends ListRecords
{
    protected static string $resource = ZipCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
