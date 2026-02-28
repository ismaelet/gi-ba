<?php

namespace App\Filament\Resources\SupplierOrders\Pages;

use App\Filament\Resources\SupplierOrders\SupplierOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplierOrder extends CreateRecord {

    use HandleSupplierOrderPageLogic;

    protected static string $resource = SupplierOrderResource::class;

    /**
     * Filament Method: Mount the edit page with the given record
     */
    public function mount(): void {
        $this->storeReturnUrlInSession();

        parent::mount();
    }

    protected function afterCreate(): void {
        $this->updateOrder();
    }

    protected function getRedirectUrl(): string {
        return $this->getReturnUrlFromSession();
    }
}
