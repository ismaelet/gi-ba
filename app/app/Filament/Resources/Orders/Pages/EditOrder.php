<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord {
    protected static string $resource = OrderResource::class;

    public ?string $returnUrl = null;

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

    public function mount($record): void {
        parent::mount($record);

        $candidate = request()->query('return')
            ?? request()->header('referer');

        $this->returnUrl = $this->sanitizeReturnUrl($candidate);
    }

    protected function afterSave(): void {
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

        // Redirect to the list page
        $this->redirect(
            $this->returnUrl ?? static::getResource()::getUrl('index')
        );
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


    private function sanitizeReturnUrl(?string $url): ?string {
        if (!filled($url)) {
            return null;
        }

        if (str_contains($url, '/livewire') || str_contains($url, '/livewire-')) {
            return null;
        }

        $parts = parse_url($url);
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);

        if (!empty($parts['host']) && $parts['host'] !== $appHost) {
            return null;
        }

        return $url;
    }
}
