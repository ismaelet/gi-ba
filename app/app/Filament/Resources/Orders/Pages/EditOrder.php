<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord {
    protected static string $resource = OrderResource::class;

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

    protected function afterSave(): void {
        $this->record->recalculateTotals();

        $this->redirect(
            static::getResource()::getUrl('index')
        );
    }

    protected function duplicateRecord(): void {
        DB::transaction(function () {
            /** @var \App\Models\Order $original */
            $original = $this->record->load('order_items');

            // 1. Duplicate main record
            $copy = $original->replicate([
                'id',
                'created_at',
                'updated_at',
            ]);

            $copy->created_at = now();
            $copy->save();

            // 2. Duplicate repeater items
            foreach ($original->order_items as $item) {
                $copy->order_items()->create(
                    $item->replicate([
                        'id',
                        'order_id',
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
}
