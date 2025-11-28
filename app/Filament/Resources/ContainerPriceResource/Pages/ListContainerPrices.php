<?php

namespace App\Filament\Resources\ContainerPriceResource\Pages;

use App\Filament\Resources\ContainerPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContainerPrices extends ListRecords
{
    protected static string $resource = ContainerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
