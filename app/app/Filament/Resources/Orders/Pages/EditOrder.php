<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class EditOrder extends EditRecord {

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
     * Filament Method: Get the header actions for the edit page
     */
    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
            Action::make('duplicate')
                ->label('Duplica ordine')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->requiresConfirmation()
                ->action(fn() => $this->duplicateRecord()),
        ];
    }

    /**
     * Filament Method: Mount the edit page with the given record
     */
    public function mount($record): void {
        parent::mount($record);

        $this->returnUrl = $this->normalizeReturnUrl(
            request()->query('return') ?? request()->header('referer') ?? url()->previous()
        );
    }

    /**
     * Filament Method: Actions to perform after saving the record
     */
    protected function afterSave(): void {
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

    protected function duplicateRecord(): void {
        DB::transaction(function () {
            $original = $this->record->load('order_items');

            // 1. Duplicate main record
            $copy = $original->replicate([
                'id',
                'created_at',
                'updated_at',
                'status',
                'billed'
            ]);

            $copy->created_at = now();
            $copy->save();

            // 2. Duplicate repeater items
            foreach ($original->order_items as $item) {
                $copy->order_items()->create(
                    $item->replicate([
                        'id',
                        'order_id',
                        'status',
                        'created_at',
                        'updated_at',
                    ])->toArray()
                );
            }

            // 3. Redirect to the new record
            $this->redirect(
                static::getResource()::getUrl('edit', ['record' => $copy])
            );
        });
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
