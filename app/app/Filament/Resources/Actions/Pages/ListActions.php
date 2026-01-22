<?php

namespace App\Filament\Resources\Actions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Actions\ActionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActions extends ListRecords
{
    protected static string $resource = ActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
