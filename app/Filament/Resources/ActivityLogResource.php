<?php

namespace App\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Spatie\Activitylog\Models\Activity;
use App\Filament\Resources\ActivityLogResource\Pages;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Audit Trail';
    protected static ?string $navigationLabel = 'Activity Logs';
    protected static ?string $pluralLabel = 'Activity Logs';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // View-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Log ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.id')
                    ->label('Admin ID')
                    ->sortable(false)
                    ->formatStateUsing(fn ($record) => $record->causer?->id ?? '—'),

                Tables\Columns\TextColumn::make('causer.email')
                ->label('Admin Email')
                ->sortable(false)
                ->searchable(false)
                ->formatStateUsing(fn ($record) => $record->causer?->email ?? 'System'),


                Tables\Columns\TextColumn::make('description')
                    ->label('Action Description')
                    ->wrap(),

                Tables\Columns\TextColumn::make('properties')
                    ->label('Properties')
                    ->formatStateUsing(function ($record) {
                        return collect($record->properties ?? [])->map(function ($value, $key) {
                            if (is_array($value)) {
                                $value = json_encode($value);
                            }
                            return "$key: $value";
                        })->implode(', ');
                    })
                    ->limit(100)
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
