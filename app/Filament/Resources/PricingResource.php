<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingResource\Pages;
use App\Filament\Resources\PricingResource\RelationManagers;
use App\Models\Addon;
use App\Models\Material;
use App\Models\Pricing;
use App\Models\ZipCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PricingResource extends Resource
{
    protected static ?string $model = Pricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

       public static function shouldRegisterNavigation(): bool
    {
        return false; // Sidebar me hide
    }
 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('zip_code_id')->label('Zip')->options(ZipCode::all()->pluck('zip', 'id'))->searchable(),
                Select::make('material_id')->label('Material')->options(Material::all()->pluck('name', 'id'))->searchable(),
                Select::make('addon_id')->label('Add On')->options(Addon::all()->pluck('name', 'id'))->searchable(),
                TextInput::make('base_price')
                    ->numeric()
                    ->required(),
            ]);
        // ->schema([
        // Select::make('zip_code_id')
        //         ->relationship('zipCode', 'zip')
        //         ->searchable()
        //         ->required(),
        // Select::make('material_id')
        //         ->relationship('material', 'name')
        //         ->required(),
        // Select::make('addon_id')
        //         ->relationship('addon', 'name')
        //         ->searchable()
        //         ->nullable(),
        // TextInput::make('base_price')
        //         ->numeric()
        //         ->required(),
        // ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('zipCode.zip')->label('Zip Code')->sortable()->searchable(),
                TextColumn::make('material.name')->label('Material')->sortable()->searchable(),
                TextColumn::make('addon.name')->label('Addon')->sortable()->searchable(),
                TextColumn::make('base_price')->label('Base Price')->money('usd', true)->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricings::route('/'),
            'create' => Pages\CreatePricing::route('/create'),
            'edit' => Pages\EditPricing::route('/{record}/edit'),
        ];
    }
}
