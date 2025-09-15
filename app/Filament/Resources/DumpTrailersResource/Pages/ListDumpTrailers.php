<?php

namespace App\Filament\Resources\DumpTrailersResource\Pages;

use App\Filament\Resources\DumpTrailersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDumpTrailers extends ListRecords
{
    protected static string $resource = DumpTrailersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
