<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord {
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void {
        // Update related customer data
        $state = $this->form->getState();

        $customerId = $state['customer_id'] ?? null;

        if ($customerId) {
            Customer::find($customerId)->update([
                'phone' => data_get($state, 'customer_data.phone'),
                'info'  => data_get($state, 'customer_data.info'),
            ]);
        }

        // Recalculate unit prices
        $this->record->order_items->each(function ($item) {
            $item->recalculateUnitPrices();
        });

        // Recalculate totals
        $this->record->recalculateTotals();
    }
}
