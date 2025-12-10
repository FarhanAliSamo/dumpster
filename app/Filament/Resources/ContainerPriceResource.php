<?php

namespace App\Filament\Resources;
use Filament\Forms\Get;
use Closure;
use Illuminate\Validation\Rule;

use App\Filament\Resources\ContainerPriceResource\Pages;
use App\Filament\Resources\ContainerPriceResource\RelationManagers;
use App\Models\ContainerPrice;
use App\Models\County;
use App\Models\ZipCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContainerPriceResource extends Resource
{
    protected static ?string $model = ContainerPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\Select::make('container_id')
                ->relationship('container', 'size_name')
                ->required()
                ->live(), // needed for duplicate checks + dependent fields

            Forms\Components\Select::make('county_id')
                ->label('County')
                ->options(County::query()->pluck('name', 'id'))
                ->searchable()
                ->live()
                ->rules([
                    fn (Get $get, ?ContainerPrice $record): Closure =>
                        function (string $attribute, $value, Closure $fail) use ($get, $record) {
                            // No county selected → nothing to validate
                            if (empty($value)) {
                                return;
                            }

                            // We must have a container to check uniqueness
                            $containerId = $get('container_id');
                            if (empty($containerId)) {
                                return;
                            }

                            $query = ContainerPrice::query()
                                ->where('container_id', $containerId)
                                ->where('county_id', $value);

                            // Ignore current record on edit
                            if ($record && $record->exists) {
                                $query->whereKeyNot($record->id);
                            }

                            if ($query->exists()) {
                                $fail('A price for this container and county already exists.');
                            }
                        },
                ]),

            Forms\Components\Select::make('zip_code_id')
                ->label('Zip Code (optional)')
                ->options(ZipCode::query()->pluck('zip', 'id'))
                ->searchable()
                ->live()
                ->rules([
                    fn (Get $get, ?ContainerPrice $record): Closure =>
                        function (string $attribute, $value, Closure $fail) use ($get, $record) {
                            // No zip selected → nothing to validate
                            if (empty($value)) {
                                return;
                            }

                            $containerId = $get('container_id');
                            if (empty($containerId)) {
                                return;
                            }

                            $query = ContainerPrice::query()
                                ->where('container_id', $containerId)
                                ->where('zip_code_id', $value);

                            // Ignore current record on edit
                            if ($record && $record->exists) {
                                $query->whereKeyNot($record->id);
                            }

                            if ($query->exists()) {
                                $fail('A price for this container and ZIP code already exists.');
                            }
                        },
                ]),

            // COUNTY FIELDS (only active when county selected)
            Forms\Components\TextInput::make('county_price')
                ->numeric()
                ->visible(fn (Get $get): bool => filled($get('county_id')))
                ->required(fn (Get $get): bool => filled($get('county_id'))),

            Forms\Components\Textarea::make('weight_county')
                ->label('Weight Limit Description for County (optional)')
                ->visible(fn (Get $get): bool => filled($get('county_id'))),

            Forms\Components\Textarea::make('rental_county')
                ->label('Rental Price Description for County (optional)')
                ->visible(fn (Get $get): bool => filled($get('county_id'))),

            // ZIP FIELDS (only active when zip selected)
            Forms\Components\TextInput::make('zip_price')
                ->numeric()
                ->visible(fn (Get $get): bool => filled($get('zip_code_id')))
                ->required(fn (Get $get): bool => filled($get('zip_code_id'))),

            Forms\Components\Textarea::make('weight_zip')
                ->label('Weight Limit Description for Zip Code (optional)')
                ->visible(fn (Get $get): bool => filled($get('zip_code_id'))),

            Forms\Components\Textarea::make('rental_zip')
                ->label('Rental Price Description for Zip Code (optional)')
                ->visible(fn (Get $get): bool => filled($get('zip_code_id'))),
        ]);
}






    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('container.size_name')->label('Container'),
                Tables\Columns\TextColumn::make('county.name')->label('County'),
                // Tables\Columns\TextColumn::make(' zipcode.zip')->label('Zip Code'),

                Tables\Columns\TextColumn::make('zip_price'),
                Tables\Columns\TextColumn::make('county_price'),
                Tables\Columns\TextColumn::make('base_price'),
                Tables\Columns\TextColumn::make('weight_zip')->label('Weight Limit Description (Zip)'),
                Tables\Columns\TextColumn::make('weight_county')->label('Weight Limit Description (County)'),
                Tables\Columns\TextColumn::make('rental_zip')->label('Rental Description (Zip)'),
                Tables\Columns\TextColumn::make('rental_county')->label('Rental Description (County)'),
            ])
            ->filters([])
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
            'index' => Pages\ListContainerPrices::route('/'),
            'create' => Pages\CreateContainerPrice::route('/create'),
            'edit' => Pages\EditContainerPrice::route('/{record}/edit'),
        ];
    }
}
