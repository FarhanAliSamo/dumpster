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
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('County Name')->maxLength(255)->required(),
                // TextInput::make('state')->label('State')->maxLength(255)->required(),
                // Select::make('state_id')->label('State')->options(State::all()->pluck('name', 'id'))->searchable()->required(),
                // Select::make('state_id')
                //     ->label('State')
                //     ->relationship('state', 'name')
                //     ->searchable()
                //     ->required()
                //     ->createOptionForm([
                //         Forms\Components\TextInput::make('name')
                //             ->label('State Name')
                //             ->required(),
                //         Forms\Components\TextInput::make('code')
                //             ->label('Code (Optional)')
                //             ->maxLength(10),
                //     ])
                //     ->createOptionUsing(function (array $data) {
                //         $state = \App\Models\State::create([
                //             'name' => $data['name'],
                //             'code' => $data['code'] ?? strtoupper(substr($data['name'], 0, 2)),
                //         ]);
                //         return $state->getKey();
                //     }),
                Select::make('state_id')
                    ->label('State')
                    ->relationship('state', 'name')
                    ->options(State::query()->pluck('name', 'id')) // show all by default
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('State Name')
                            ->required()
                            ->unique(ignoreRecord: true, table: State::class, column: 'name'),
                        Forms\Components\TextInput::make('code')
                            ->label('Code (Optional)')
                            ->maxLength(10),
                    ])
                    ->createOptionUsing(function (array $data) {
                        // Check if already exists (case-insensitive)
                        $existing = State::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])->first();

                        if ($existing) {
                            return $existing->getKey(); // return existing id (no duplicate)
                        }

                        $state = State::create([
                            'name' => $data['name'],
                            'code' => $data['code'] ?? strtoupper(substr($data['name'], 0, 2)),
                        ]);

                        return $state->getKey();
                    })
                    ->preload(), // show all states instantly

                TextInput::make('base_price')->label('Base Price')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('County Name')->sortable()->searchable(),
                TextColumn::make('state.name')->label('State')->sortable()->searchable(),
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
