<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class CreateOrder extends CreateRecord {

    /**
     * Property: The resource class associated with this page
     */
    protected static string $resource = OrderResource::class;

    /**
     * Property: The URL to return to after saving the record
     */
    #[Url(as: 'return')]
    public ?string $returnUrl = null;

    /**
     * Filament Method: Mount the edit page with the given record
     */
    public function mount(): void {
        parent::mount();

        $this->returnUrl = $this->normalizeReturnUrl(
            request()->query('return') ?? request()->header('referer') ?? url()->previous()
        );
    }

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
            $item->recalculatePrices();
        });

        // Recalculate totals
        $this->record->recalculateTotals();

        // Redirect to the list page
        $this->redirect($this->returnUrl);
    }

    /**
     * Normalize a candidate return URL to ensure it's safe and valid
     */
    private function normalizeReturnUrl(?string $candidate): string {
        $indexUrl  = static::getResource()::getUrl('index');
        $indexPath = parse_url($indexUrl, PHP_URL_PATH) ?? $indexUrl;

        $fallback = $indexPath;

        if (! filled($candidate)) {
            return $fallback;
        }

        $parts = parse_url($candidate);
        if ($parts === false) {
            return $fallback;
        }

        $path = $parts['path'] ?? null;
        if (! filled($path)) {
            return $fallback;
        }

        if (! empty($parts['host'])) {
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            if ($appHost && ! Str::is($appHost, $parts['host'])) {
                return $fallback;
            }
        }

        if (rtrim($path, '/') !== rtrim($indexPath, '/')) {
            return $fallback;
        }

        $query = isset($parts['query']) && filled($parts['query'])
            ? ('?' . $parts['query'])
            : '';

        return $indexPath . $query;
    }
}
