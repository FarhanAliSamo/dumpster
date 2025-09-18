<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountyResource\Pages;
use App\Filament\Resources\CountyResource\RelationManagers;
use App\Models\County;
use App\Models\State;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CountyZipImport;
use Filament\Notifications\Notification;

class CountyResource extends Resource
{
    protected static ?string $model = County::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    { 
        return $form
            ->schema([
                TextInput::make('name')->label('County Name')->maxLength(255)->required(),
                // TextInput::make('state')->label('State')->maxLength(255)->required(),
                Select::make('state')->label('State')->options(State::all()->pluck('name', 'name'))->searchable()->required(),
                TextInput::make('base_price')->label('Base Price')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('County Name')->sortable()->searchable(),
                TextColumn::make('state')->label('State')->sortable()->searchable(),
                TextColumn::make('base_price')->label('Base Price')->money('usd', true)->sortable(),
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
            'index' => Pages\ListCounties::route('/'),
            'create' => Pages\CreateCounty::route('/create'),
            'edit' => Pages\EditCounty::route('/{record}/edit'),
        ];
    }
}
