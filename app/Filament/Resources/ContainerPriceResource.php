<?php

namespace App\Filament\Resources;

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
                    ->required(),

                Forms\Components\Select::make('county_id')
                    ->relationship('county', 'name')->options(County::all()->pluck('name', 'id'))
                    ->searchable(),


                Forms\Components\Select::make('zip_code_id')
                    ->label('Zip Code (optional)') 
                    ->options(ZipCode::all()->pluck('zip','id'))
                    ->searchable(), 

                // Forms\Components\TextInput::make('zip_code')
                //     ->label('Zip Code (optional)')
                //     ->maxLength(10),

                // Forms\Components\TextInput::make('base_price')->numeric(),
                Forms\Components\TextInput::make('county_price')->numeric(),
                Forms\Components\TextInput::make('zip_price')->numeric(),

                Forms\Components\Textarea::make('weight_zip')
                ->label('Weight Limit Description for Zip Code (optional)'),
                Forms\Components\Textarea::make('rental_zip')->label('Rental Price Description for Zip Code (optional)'),

                 Forms\Components\Textarea::make('weight_county')
                ->label('Weight Limit Description for County (optional)'),
                Forms\Components\Textarea::make('rental_county')->label('Rental Price Description for County (optional)'),
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
