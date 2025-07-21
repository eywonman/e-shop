<?php

namespace App\Filament\Resources;

use App\Models\DailySale;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Filament\Forms;

class SaleResource extends Resource
{
    protected static ?string $model = DailySale::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Sales';
    protected static ?string $pluralLabel = 'Sales';
    protected static ?string $navigationGroup = 'Reports';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                DailySale::query()
                    ->fromSub(
                        DB::table('orders')
                            ->selectRaw('DATE(created_at) as sale_date, SUM(total_price) as total_sales')
                            ->where('status', 'accepted')
                            ->groupByRaw('DATE(created_at)'),
                        'daily_sales'
                    )
            )
            ->columns([
                Tables\Columns\TextColumn::make('sale_date')
                    ->label('Sale Date')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('F j, Y'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Total Sales')
                    ->money('PHP')
                    ->sortable()
                    ->extraAttributes(['class' => 'font-bold text-green-700'])
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()->label('Grand Total')->money('PHP'),
                    ]),
            ])
            ->filters([
                Filter::make('sale_date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')->label('From'),
                        Forms\Components\DatePicker::make('end_date')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['start_date'] ?? null) {
                            $query->whereDate('sale_date', '>=', $data['start_date']);
                        }

                        if ($data['end_date'] ?? null) {
                            $query->whereDate('sale_date', '<=', $data['end_date']);
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
