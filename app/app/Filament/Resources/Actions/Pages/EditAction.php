<?php

namespace App\Filament\Resources\Actions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Actions\ActionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAction extends EditRecord
{
    protected static string $resource = ActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
