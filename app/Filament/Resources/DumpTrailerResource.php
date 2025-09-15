<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DumpTrailerResource\Pages;
use App\Filament\Resources\DumpTrailerResource\RelationManagers;
use App\Models\DumpTrailer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class DumpTrailerResource extends Resource
{
    protected static ?string $model = DumpTrailer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

 public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('size_name')->label('Size Name')->required(),
                TextInput::make('price')->label('Price')->nullable()->numeric(),
                MarkdownEditor::make('description')->label('Description')->required()->columnSpan(2),
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->disk('public')
                    ->directory('dumptrailers')
                    ->nullable()
                    ->imageEditor() 
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                        'image/bmp',
                        'image/svg+xml',
                        'image/tiff',
                        'image/x-icon',
                        'image/heic',
                        'image/heif',
                    ]),
           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Image'),
                TextColumn::make('size_name')->label('Size Name')->sortable()->searchable(),
                TextColumn::make('price')->label('Price')->money('usd', true)->sortable()->searchable(),
                TextColumn::make('description')->label('Description')->limit(50)->wrap()->sortable()->searchable(),
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
            'index' => Pages\ListDumpTrailers::route('/'),
            'create' => Pages\CreateDumpTrailer::route('/create'),
            'edit' => Pages\EditDumpTrailer::route('/{record}/edit'),
        ];
    }
}
