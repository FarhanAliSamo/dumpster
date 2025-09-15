<?php

namespace App\Filament\Resources\DumpTrailerResource\Pages;

use App\Filament\Resources\DumpTrailerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDumpTrailers extends ListRecords
{
    protected static string $resource = DumpTrailerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
