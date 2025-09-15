<?php

namespace App\Filament\Resources\DumpTrailersResource\Pages;

use App\Filament\Resources\DumpTrailersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDumpTrailers extends EditRecord
{
    protected static string $resource = DumpTrailersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
