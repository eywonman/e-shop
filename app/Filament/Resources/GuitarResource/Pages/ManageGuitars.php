<?php

namespace App\Filament\Resources\GuitarResource\Pages;

use App\Filament\Resources\GuitarResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Spatie\Activitylog\Facades\Activity;

class ManageGuitars extends ManageRecords
{
    protected static string $resource = GuitarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function ($record) {
                    Activity::causedBy(auth()->user())
                        ->performedOn($record)
                        ->withProperties([
                            'id' => $record->id,
                            'name' => $record->name,
                        ])
                        ->log('Created a new guitar');
                }),
        ];
    }
}
