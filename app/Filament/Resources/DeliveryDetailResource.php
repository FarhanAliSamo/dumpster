<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryDetailResource\Pages;
use App\Models\DeliveryDetail;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DeliveryDetailResource extends Resource
{
    protected static ?string $model = DeliveryDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Form Submissions';
    protected static ?string $pluralLabel = 'Submissions';
    protected static ?string $navigationGroup = 'Customer Forms';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('delivery_date')->date(),
                Tables\Columns\TextColumn::make('delivery_preference')->label('Delivery Pref'),
                Tables\Columns\TextColumn::make('expected_rental_days')->label('Rental Days'),
                Tables\Columns\TextColumn::make('property_type')->label('Property Type'),
                Tables\Columns\TextColumn::make('city')->label('City'),
                Tables\Columns\TextColumn::make('state')->label('State'),
                Tables\Columns\TextColumn::make('zip_code')->label('Zip'),
                Tables\Columns\TextColumn::make('site_contact_name')->label('Contact Name'),
                Tables\Columns\TextColumn::make('billingDetail.first_name')->label('Billing First Name'),
                Tables\Columns\TextColumn::make('billingDetail.email')->label('Billing Email'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Submitted At'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveryDetails::route('/'),
            'view' => Pages\ViewDeliveryDetail::route('/{record}'),
        ];
    }
}
