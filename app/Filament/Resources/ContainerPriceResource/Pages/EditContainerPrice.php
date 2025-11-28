<?php

namespace App\Filament\Resources\ContainerPriceResource\Pages;

use App\Filament\Resources\ContainerPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContainerPrice extends EditRecord
{
    protected static string $resource = ContainerPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
