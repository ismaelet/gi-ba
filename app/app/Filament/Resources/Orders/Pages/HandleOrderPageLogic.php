<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Models\Customer;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

trait HandleOrderPageLogic {

    /**
     * Property: The URL to return to after saving the record
     */
    #[Url(as: 'return')]
    public ?string $returnUrl = null;

    /**
     * Update the order and related data
     */
    protected function updateOrder(): void {
        $state = $this->form->getState();

        $customerId = $state['customer_id'] ?? null;

        if ($customerId) {
            Customer::find($customerId)->update([
                'phone' => data_get($state, 'customer_data.phone'),
                'info'  => data_get($state, 'customer_data.info'),
            ]);
        }

        // Recalculate unit prices
        $this->record->order_items()->each(function ($item) {
            $item->load('product');
            $item->recalculatePrices();
            $item->product->update([
                'price' => $item->unit_price_net,
            ]);
        });

        // Recalculate totals
        $this->record->recalculateTotals();
    }

    /**
     * Normalize a candidate return URL to ensure it's safe and valid
     */
    protected function getNormalizeReturnUrl(): string {
        $candidate = request()->query('return') ?? request()->header('referer') ?? url()->previous();

        $indexUrl  = static::getResource()::getUrl('index');
        $indexPath = parse_url($indexUrl, PHP_URL_PATH) ?? $indexUrl;

        $fallback = $indexPath;

        if (!filled($candidate)) {
            return $fallback;
        }

        $parts = parse_url($candidate);
        if ($parts === false) {
            return $fallback;
        }

        $path = $parts['path'] ?? null;
        if (!filled($path)) {
            return $fallback;
        }

        if (!empty($parts['host'])) {
            $appHost = parse_url(config('app.url'), PHP_URL_HOST);
            if ($appHost && !Str::is($appHost, $parts['host'])) {
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
