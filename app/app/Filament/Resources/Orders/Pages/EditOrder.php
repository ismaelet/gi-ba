<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord {

    use HandleOrderPageLogic;

    /**
     * Property: The resource class associated with this page
     */
    protected static string $resource = OrderResource::class;

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

        $this->returnUrl = $this->getNormalizeReturnUrl();
    }

    /**
     * Filament Method: Actions to perform after saving the record
     */
    protected function afterSave(): void {
        $this->updateOrder();
    }

    protected function duplicateRecord(): void {
        DB::transaction(function () {
            $original = $this->record->load('order_items');

            $copy = $original->replicate([
                'id',
                'created_at',
                'updated_at',
                'status',
                'billed'
            ]);

            $copy->created_at = now();
            $copy->save();

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

            $this->redirect(
                static::getResource()::getUrl('edit', ['record' => $copy])
            );
        });
    }
}
