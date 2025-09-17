<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZipCodeResource\Pages;
use App\Filament\Resources\ZipCodeResource\RelationManagers;
use App\Models\ZipCode;
use App\Models\County;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CountyZipImport;
use Filament\Notifications\Notification;

class ZipCodeResource extends Resource
{
    protected static ?string $model = ZipCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('zip')
                    ->label('ZIP Code')
                    ->maxLength(10)
                    ->required(),
                Select::make('county_id')->label('County')->options(County::all()->pluck('name', 'id'))->searchable(),
                TextInput::make('special_price')->label('Special Price')->numeric()->nullable(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('zip')->label('ZIP Code')->sortable()->searchable(),
                TextColumn::make('county.name')->label('County')->sortable()->searchable(),
                TextColumn::make('special_price')->label('Special Price')->money('usd', true)->sortable(),
                TextColumn::make('created_at')->dateTime()->label('Created At')->sortable(),
                TextColumn::make('updated_at')->dateTime()->label('Updated At')->sortable(),
            ])
             ->headerActions([
                Action::make('import')
                    ->label('Import Excel/CSV')
                    ->form([ 
                        FileUpload::make('file')
                            ->disk('public')
                            ->directory('imports')
                            ->required() 
                    ])
                    ->action(function (array $data, $record) {
                        // $data['file'] will be path like 'imports/filename.xlsx' (on default 'local' disk)
                        $path =  'storage/' . $data['file'];

                        // dd($path);

                        // Import using Maatwebsite Excel
                        Excel::import(new CountyZipImport, $path);

                        Notification::make()
                            ->title('Import completed')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListZipCodes::route('/'),
            'create' => Pages\CreateZipCode::route('/create'),
            'edit' => Pages\EditZipCode::route('/{record}/edit'),
        ];
    }
}
