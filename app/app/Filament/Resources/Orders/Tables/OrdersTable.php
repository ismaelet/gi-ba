<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Tables\Columns\OrderItemsColumn;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class OrdersTable {
    public static function configure(Table $table): Table {
        return $table
            ->defaultSort('id', 'desc')
            ->query(
                Order::query()->with('order_items.product')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable()
                    ->html()
                    ->wrap()
                    ->formatStateUsing(fn(string $state, Order $record): string => $state . '<br>' . $record->customer->phone),

                OrderItemsColumn::make('order_items.product.name')
                    ->searchable()
                    ->label('Articoli'),

                TextColumn::make('price_gross')
                    ->label('Totale')
                    ->sortable()
                    ->money('eur')
                    ->default(0),

                TextColumn::make('status')
                    ->label('Stato')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => config('ecommerce.order_statuses')[$state]['label'] ?? $state)
                    ->color(fn(int $state): string => 'status-' . $state),

                ToggleColumn::make('billed')
                    ->label('Fatturato')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Data')
                    ->sortable()
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                Filter::make('status')
                    ->schema([
                        CheckboxList::make('statuses')
                            ->label('Stato')
                            ->options(
                                collect(config('ecommerce.order_statuses'))
                                    ->mapWithKeys(fn($status, $key) => [$key => $status['label']])
                                    ->toArray()
                            )
                            ->columns(4)
                            ->default([1, 2, 3, 4, 5, 6]),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['statuses'])) {
                            $query->whereIn('status', $data['statuses']);
                        }
                    })->columnSpan(2),

                TernaryFilter::make('billed')
                    ->label('Fatturato')
                    ->placeholder('Tutti')
                    ->trueLabel('Solo fatturati')
                    ->falseLabel('Solo non fatturati'),

                Filter::make('created_at')
                    ->label('Data creazione')
                    ->schema([
                        DatePicker::make('from')
                            ->label('Da'),

                        DatePicker::make('until')
                            ->label('A'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->deferFilters(false)
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->url(fn($record) => OrderResource::getUrl('edit', [
                            'record' => $record,
                            'return' => self::currentListUrl(),
                        ])),

                    Action::make('download-quote')
                        ->label('Scarica preventivo')
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('info')
                        ->url(fn(Order $record) => route('admin.orders.quote.download', ['order' => $record->id]))
                        ->openUrlInNewTab(),

                    Action::make('download-delivery-note')
                        ->label('Scarica bolla')
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('warning')
                        ->url(fn(Order $record) => route('admin.orders.delivery-note.download', ['order' => $record->id]))
                        ->openUrlInNewTab(),

                    Action::make('merge')
                        ->label('Unisci')
                        ->color('danger')
                        ->icon(Heroicon::ArrowsPointingIn)
                        ->modalSubmitActionLabel('Unisci ordini')
                        ->schema(fn(Order $record) => [
                            Select::make('target_order_id')
                                ->label('Unisci con ordine')
                                ->aboveContent('Gli aricoli verranno tutti trasferiti nell\'ordine qui. I dati che verranno mantenuti saranno quelli dell\'ordine selezionato qui sotto.')
                                ->searchable()
                                ->getSearchResultsUsing(
                                    fn(string $search) => Order::query()
                                        ->whereKeyNot($record->id)
                                        ->where('id', $search)
                                        ->limit(50)
                                        ->get()
                                        ->mapWithKeys(fn(Order $order) => [
                                            $order->id => 'Ordine ' . $order->idCode() . ' (' . ($order->customer?->name ?? '—') . ')',
                                        ])
                                        ->toArray()
                                )
                                ->getOptionLabelUsing(function ($value) {
                                    $order = Order::with('customer')->find($value);

                                    return $order
                                        ? 'Ordine ' . $order->idCode() . ' (' . ($order->customer?->name ?? '—') . ')'
                                        : (string) $value;
                                })
                                ->required(),
                        ])
                        ->action(function (Order $record, array $data): void {
                            $targetOrder = Order::find($data['target_order_id']);

                            OrderItem::where('order_id', $record->id)
                                ->update([
                                    'order_id' => $targetOrder->id,
                                ]);

                            $targetOrder->recalculateTotals();

                            $record->delete();
                        }),
                ]),
            ])
            ->recordUrl(fn($record) => OrderResource::getUrl('edit', [
                'record' => $record,
                'return' => self::currentListUrl(),
            ]))
            ->headerActions([
                Action::make('selectAllStatuses')
                    ->label('Seleziona tutti gli stati')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('primary')
                    ->outlined()
                    ->visible(function (Component $livewire): bool {
                        $filters = $livewire->getTableFiltersForm()?->getState() ?? [];

                        $selected = data_get($filters, 'status.statuses', []);

                        $allStatuses = array_keys(config('ecommerce.order_statuses'));

                        sort($selected);
                        sort($allStatuses);

                        return $selected !== $allStatuses;
                    })
                    ->action(function (Component $livewire): void {
                        $form = $livewire->getTableFiltersForm();

                        $filters = $form?->getState() ?? [];
                        $all = array_map('strval', array_keys(config('ecommerce.order_statuses')));

                        // Push the state back into the filters form
                        $filters['status']['statuses'] = $all;
                        $form?->fill($filters);

                        // Now apply (Filament will read from the form state)
                    }),
                Action::make('clearStatusFilter')
                    ->label('Deseleziona tutti gli stati')
                    ->icon(Heroicon::XMark)
                    ->color('danger')
                    ->outlined()
                    ->visible(function (Component $livewire): bool {
                        $filters = $livewire->getTableFiltersForm()?->getState() ?? [];

                        $values = data_get($filters, 'status.statuses', []);

                        return ! empty($values);
                    })
                    ->action(function (Component $livewire): void {
                        $livewire->removeTableFilter('status');
                    }),
            ])
            ->toolbarActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function currentListUrl(): string {
        // When Livewire is updating, the request path is /livewire-*/update.
        // But Livewire sends the "Referer" header with the real page URL.
        $referer = request()->header('referer');

        if (filled($referer) && ! str_contains($referer, '/livewire')) {
            return $referer;
        }

        // Fallback: build from the known list route
        return OrderResource::getUrl('index');
    }
}
