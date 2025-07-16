<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuitarResource\Pages;
use App\Filament\Resources\GuitarResource\RelationManagers;
use App\Models\Guitar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuitarResource extends Resource
{
    protected static ?string $model = Guitar::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationLabel = 'Guitars';

    protected static ?string $pluralLabel = 'Guitars';

    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Guitar Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('brand')
                    ->required()
                    ->maxLength(255),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),

                TextInput::make('stock')
                    ->required()
                    ->numeric(),

                TextInput::make('image_url')
                    ->label('Image URL')
                    ->url()
                    ->maxLength(2048)
                    ->nullable(),
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
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(fn () => null), // disable toast
                Tables\Actions\DeleteAction::make()
                    ->after(fn () => null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(fn () => null),
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageGuitars::route('/'),
        ];
    }
}
