<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\User;
use App\Models\Guitar;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderAccepted;
use App\Mail\OrderDeclined;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $pluralLabel = 'Orders';

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
    Forms\Components\Select::make('user_id')
        ->label('User')
        ->relationship('user', 'email')
        ->searchable()
        ->required()
        ->disabled(), 

    Forms\Components\TextInput::make('address')
        ->required()
        ->disabled(),

    // ✅ Show order items in a Repeater (read-only)
    Forms\Components\Repeater::make('items')
        ->label('Order Items')
        ->relationship() 
        ->schema([
            Forms\Components\Select::make('guitar_id')
            ->label('Guitar')
            ->relationship('guitar', 'name')
            ->disabled(),

            Forms\Components\TextInput::make('quantity')
                ->label('Quantity')
                ->disabled(),

            Forms\Components\TextInput::make('price')
                ->label('Price')
                ->prefix('₱')
                ->disabled(),
        ])
        ->columns(3)
        ->disabled() // prevents adding/removing items
        ->dehydrated(false), // prevents form update

        Forms\Components\TextInput::make('total_price')
        ->numeric()
        ->prefix('₱')
        ->required()
        ->disabled(),

        Forms\Components\Select::make('payment_method')
        ->options([
            'cod' => 'Cash on Delivery',
        ])
        ->required()
        ->disabled(),

        Forms\Components\Select::make('status')
        ->options([
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'declined' => 'Declined',
        ])
        ->required(),
]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Customer Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('PHP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'declined',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Ordered At'),
            ])
            ->actions([
                Action::make('accept')
                    ->label('Accept')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'accepted']);
                        Mail::to($record->user->email)->send(new OrderAccepted($record));
                    }),

                Action::make('decline')
                    ->label('Decline')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Restock each guitar item
                        foreach ($record->items as $item) {
                            if ($item->guitar) {
                                $item->guitar->increment('stock', $item->quantity);
                            }
                        }

                        // Update status
                        $record->update(['status' => 'declined']);

                        // Send email
                        Mail::to($record->user->email)->send(new OrderDeclined($record));
                    }),


                Tables\Actions\ViewAction::make(),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }
}
