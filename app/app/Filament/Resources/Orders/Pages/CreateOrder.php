<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord {

    use HandleOrderPageLogic;

    /**
     * Property: The resource class associated with this page
     */
    protected static string $resource = OrderResource::class;

    /**
     * Filament Method: Mount the edit page with the given record
     */
    public function mount(): void {
        parent::mount();

        // $this->returnUrl = $this->getNormalizeReturnUrl();
    }

    protected function afterCreate(): void {
        $this->updateOrder();
    }

    protected function getRedirectUrl(): string {
        return $this->returnUrl
            ?: static::getResource()::getUrl('index');
    }
}
