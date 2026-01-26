<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord {
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void {
        $this->record->order_items->each(function ($item) {
            $item->recalculateUnitPrices();
        });

        $this->record->recalculateTotals();
    }
}
