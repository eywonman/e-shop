<?php

namespace App\Filament\Resources\GuitarResource\Pages;

use App\Filament\Resources\GuitarResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGuitars extends ManageRecords
{
    protected static string $resource = GuitarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
