<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Tables\Columns\OrderItemsColumn;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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
use Illuminate\Database\Query\Builder as QueryBuilder;
use Livewire\Component;

class OrdersTable {
    public static function configure(Table $table): Table {
        return $table
            ->defaultSort('id', 'desc')
            ->defaultPaginationPageOption(50)
            ->query(
                Order::query()
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->extraAttributes(fn(Order $record): array => [
                        'id' => 'order-id-' . $record->id,
                    ])
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
            ->searchable(['customer.info', 'customer.phone', 'order_items.info'])
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
                        $statuses = array_values(array_filter($data['statuses'] ?? []));

                        if ($statuses === []) {
                            $query->where(function (Builder $q) use ($statuses) {
                                $q->whereNull('orders.status');
                            });

                            return;
                        }

                        $query->where(function (Builder $q) use ($statuses) {
                            $q->whereIn('orders.status', $statuses)
                                ->orWhereExists(function (QueryBuilder $sub) use ($statuses) {
                                    $sub->selectRaw('1')
                                        ->from('order_items')
                                        ->whereColumn('order_items.order_id', 'orders.id')
                                        ->whereIn('order_items.status', $statuses);
                                });
                        });
                    })->columnSpan(2),

                SelectFilter::make('customer')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->searchable(),

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
                    Action::make('download-quote')
                        ->label('Scarica preventivo')
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('info')
                        ->url(fn(Order $record) => route('admin.orders.quote.download', ['order' => $record->id]))
                        ->openUrlInNewTab(),

                    Action::make('download-delivery-note')
                        ->label('Scarica bolla')
                        ->icon(Heroicon::ArrowDownTray)
                        ->color('indigo')
                        ->url(fn(Order $record) => route('admin.orders.delivery-note.download', ['order' => $record->id]))
                        ->openUrlInNewTab(),

                    Action::make('merge')
                        ->label('Unisci')
                        ->color('amber')
                        ->icon(Heroicon::ArrowsPointingIn)
                        ->modalSubmitActionLabel('Unisci ordini')
                        ->schema(fn(Order $record) => [
                            Select::make('target_order_id')
                                ->label('Unisci con ordine')
                                ->aboveContent('Gli aricoli dell\'ordine selezionato qui sotto verranno tutti trasferiti in quest\'ordine. I dati che verranno mantenuti saranno quelli di quest\'ordine, non quelli dell\'ordine selezionato sotto.')
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

                            OrderItem::where('order_id', $targetOrder->id)
                                ->update([
                                    'order_id' => $record->id,
                                ]);

                            $record->recalculateTotals();

                            $targetOrder->delete();
                        }),
                    DeleteAction::make(),
                ]),
            ])
            ->recordUrl(fn(Order $record, Component $livewire) => OrderResource::getUrl('edit', [
                'record' => $record,
                'return_url' => self::currentListUrl($record, $livewire),
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

    private static function currentListUrl(Order $record, ?Component $livewire = null): string {
        $fragment = 'order-id-' . $record->id;

        // 1) Best: build from Livewire state (this updates when you paginate)
        if ($livewire) {
            $filtersFormState = $livewire->getTableFiltersForm()?->getState() ?? [];

            $params = array_filter([
                'filters' => $filtersFormState ?: null,
                'search' => method_exists($livewire, 'getTableSearch') ? ($livewire->getTableSearch() ?: null) : null,
                'sortColumn' => method_exists($livewire, 'getTableSortColumn') ? ($livewire->getTableSortColumn() ?: null) : null,
                'sortDirection' => method_exists($livewire, 'getTableSortDirection') ? ($livewire->getTableSortDirection() ?: null) : null,

                // pagination: Filament uses tablePage in most setups
                'page' => method_exists($livewire, 'getTablePage') ? ($livewire->getTablePage() ?: null) : null,
            ], fn($v) => $v !== null);

            return OrderResource::getUrl('index', $params) . '#' . $fragment;
        }

        // 2) Fallback: referer (only works for full page loads)
        $referer = request()->header('referer');
        if (filled($referer)) {
            $sanitized = self::sanitizeReturnUrl($referer);

            if (filled($sanitized) && self::isOrdersIndexPath($sanitized)) {
                return $sanitized . '#' . $fragment;
            }
        }

        // 3) Final fallback
        return OrderResource::getUrl('index') . '#' . $fragment;
    }

    private static function sanitizeReturnUrl(string $url): ?string {
        $parts = parse_url($url);

        $path = $parts['path'] ?? null;
        if (! filled($path)) {
            return null;
        }

        $query = [];
        if (! empty($parts['query'])) {
            parse_str($parts['query'], $query);
            unset($query['return_url']);
        }

        $qs = $query ? ('?' . http_build_query($query)) : '';
        return $path . $qs;
    }

    private static function isOrdersIndexPath(string $relativeUrl): bool {
        $path = parse_url($relativeUrl, PHP_URL_PATH) ?: '';
        $indexPath = parse_url(OrderResource::getUrl('index'), PHP_URL_PATH) ?: '';

        return $path === $indexPath;
    }
}
