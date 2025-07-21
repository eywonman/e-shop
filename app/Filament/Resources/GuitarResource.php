<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuitarResource\Pages;
use App\Models\Guitar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Facades\Activity;

class GuitarResource extends Resource
{
    protected static ?string $model = Guitar::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';
    protected static ?string $navigationLabel = 'Guitars';
    protected static ?string $pluralLabel = 'Guitars';
    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Guitar Name')
                ->required()
                ->maxLength(255),

            TextInput::make('brand')
                ->required()
                ->maxLength(255),

            TextInput::make('price')
                ->label('Price (₱)')
                ->required()
                ->numeric()
                ->prefix('₱')
                ->minValue(1)
                ->maxValue(999999999999)
                ->step(0.01),

            TextInput::make('stock')
                ->required()
                ->numeric()
                ->minValue(0)
                ->maxValue(100000),

            TextInput::make('image_url')
                ->label('Image URL')
                ->url()
                ->maxLength(2048)
                ->nullable()
                ->rules(['nullable', 'url', 'max:2048']),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->size(60)
                    ->circular()
                    ->defaultImageUrl('https://via.placeholder.com/60'),

                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('brand')->sortable()->searchable(),
                TextColumn::make('price')->label('Price')->money('PHP')->sortable(),
                TextColumn::make('stock')->sortable()->label('Stock'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (Guitar $record) {
                        Activity::causedBy(auth()->user())
                            ->performedOn($record)
                            ->withProperties(['id' => $record->id, 'name' => $record->name])
                            ->log('Updated a guitar');
                    }),

                Tables\Actions\DeleteAction::make()
                    ->after(function (Guitar $record) {
                        Activity::causedBy(auth()->user())
                            ->performedOn($record)
                            ->withProperties(['id' => $record->id, 'name' => $record->name])
                            ->log('Deleted a guitar');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                Activity::causedBy(auth()->user())
                                    ->performedOn($record)
                                    ->withProperties(['id' => $record->id, 'name' => $record->name])
                                    ->log('Bulk deleted guitar');
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGuitars::route('/'),
        ];
    }
}
