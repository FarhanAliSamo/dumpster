<?php

namespace App\Filament\Resources\DeliveryDetailResource\Pages;

use App\Filament\Resources\DeliveryDetailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class ViewDeliveryDetail extends ViewRecord
{
    protected static string $resource = DeliveryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Section::make('Delivery Details')
                    ->description('Information about the delivery request.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('delivery_date')->label('Delivery Date'),
                            TextEntry::make('delivery_preference')->label('Delivery Preference'),
                            TextEntry::make('expected_rental_days')->label('Expected Rental Days'),
                            TextEntry::make('property_type')->label('Property Type'),
                            TextEntry::make('street_address')->label('Street Address'),
                            TextEntry::make('city')->label('City'),
                            TextEntry::make('state')->label('State'),
                            TextEntry::make('zip_code')->label('Zip Code'),
                            TextEntry::make('placement_instructions')->label('Placement Instructions'),
                            TextEntry::make('comments')->label('Additional Comments'),
                            TextEntry::make('site_contact_name')->label('Site Contact Name'),
                            TextEntry::make('site_contact_phone')->label('Site Contact Phone'),
                            TextEntry::make('call_prior_to_arrival')
                                ->label('Call Prior to Arrival')
                                ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                        ]),
                    ])
                    ->columns(2),

                Section::make('Billing Details')
                    ->description('Customer billing information.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('billingDetail.first_name')->label('First Name'),
                            TextEntry::make('billingDetail.last_name')->label('Last Name'),
                            TextEntry::make('billingDetail.company')->label('Company'),
                            TextEntry::make('billingDetail.phone')->label('Phone'),
                            TextEntry::make('billingDetail.email')->label('Email'),
                        ]),
                    ])
                    ->columns(2),
            ]);
    }
}
