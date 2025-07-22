<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Spatie\Permission\Models\Role;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'User Management';
    protected static ?string $pluralLabel = 'Users';
    protected static ?string $navigationGroup = 'Account Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Full Name')->searchable(),
                TextColumn::make('email')->searchable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge(),
            ])
            ->actions([
                Action::make('changeRole')
                    ->label('Change Role')
                    ->icon('heroicon-o-pencil')
                    ->visible(fn () => Auth::user()->hasRole('super-admin'))
                    ->form([
                        Select::make('role')
                            ->label('Select Role')
                            ->options(Role::pluck('name', 'name')->toArray())
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $oldRoles = $record->roles->pluck('name')->toArray();
                        $newRole = $data['role'];

                        $record->syncRoles([$newRole]);

                        // Log the role change
                        activity()
                        ->causedBy(Auth::user())
                        ->performedOn($record)
                        ->withProperties([
                            'old_roles' => $oldRoles,
                            'new_role' => $newRole,
                        ])
                        ->log('Changed user role');
                    }),
            ])
            ->bulkActions([])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
