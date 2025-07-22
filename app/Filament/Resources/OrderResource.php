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
use Spatie\Activitylog\Facades\Activity;

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
            Forms\Components\TextInput::make('order_number')
                ->label('Order #')
                ->disabled(),

            Forms\Components\TextInput::make('user_email')
                ->label('User Email')
                ->disabled()
                ->afterStateHydrated(function ($component, $state, $record) {
                    $component->state(optional($record->user)->email);
                }), 
            
            Forms\Components\Repeater::make('items')
                ->label('Order Items')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('guitar_name')
                        ->label('Guitar')
                        ->disabled()
                        ->afterStateHydrated(function ($component, $state, $record) {
                            $component->state(optional($record->guitar)->name);
                        }),

                    Forms\Components\TextInput::make('quantity')
                        ->label('Quantity')
                        ->disabled(),

                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->prefix('₱')
                        ->disabled(),
                ])
                ->columns(3)
                ->disabled()
                ->dehydrated(false),
            
            Forms\Components\TextInput::make('address')
                ->required()
                ->disabled(), 

            Forms\Components\TextInput::make('total_price')
                ->numeric()
                ->prefix('₱')
                ->required()
                ->disabled(),

            Forms\Components\TextInput::make('payment_method')
                ->label('Payment Method')
                ->disabled(),

            Forms\Components\TextInput::make('status')
                ->disabled(),
        ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                ->label('Order #')
                ->searchable()
                ->sortable(),

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

                        // ✅ Audit log
                        Activity::causedBy(auth()->user())
                            ->performedOn($record)
                            ->withProperties(['order_id' => $record->id])
                            ->log('Accepted an order');

                        // Send email
                        Mail::to($record->user->email)->send(new OrderAccepted($record));
                    }),

                Action::make('decline')
                    ->label('Decline')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // Restock guitars
                        foreach ($record->items as $item) {
                            if ($item->guitar) {
                                $item->guitar->increment('stock', $item->quantity);
                            }
                        }

                        $record->update(['status' => 'declined']);

                        // ✅ Audit log
                        Activity::causedBy(auth()->user())
                            ->performedOn($record)
                            ->withProperties(['order_id' => $record->id])
                            ->log('Declined an order');

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
