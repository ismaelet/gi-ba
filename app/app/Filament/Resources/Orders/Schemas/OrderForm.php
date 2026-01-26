<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\OrderItem;
use Dom\Text;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm {
    private const VAT = 0.22;

    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                Fieldset::make('Dati del cliente')
                    ->relationship('customer')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome completo')
                            ->placeholder('Nome completo')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Numero di telefono')
                            ->placeholder('Numero di telefono'),
                        Textarea::make('info')
                            ->label('Informazioni aggiuntive')
                            ->placeholder('Informazioni aggiuntive')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Fieldset::make('Dati dell\'ordine')
                    ->schema([
                        TextInput::make('id')
                            ->label('Numero dell\'ordine')
                            ->placeholder('Numero dell\'ordine')
                            ->readOnly()
                            ->copyable(),
                        DatePicker::make('created_at')
                            ->label('Data')
                            ->readOnly(),
                        Select::make('status')
                            ->label('Stato')
                            ->options(
                                collect(config('ecommerce.order_statuses'))
                                    ->mapWithKeys(fn(array $status, string $key): array => [$key => $status['label']])
                                    ->toArray()
                            )
                            ->extraInputAttributes(fn(callable $get) => [
                                'class' => 'text-status-' . $get('status'),
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                self::updateOrderItemsStatuses($state, $get, $set);
                            })
                            ->required()
                            ->columnSpanFull(),
                        Checkbox::make('billed')
                            ->label('Fatturato'),
                        Checkbox::make('print_descriptions')
                            ->label('Stampa dettagli articoli'),
                        Checkbox::make('print_notes')
                            ->label('Stampa note'),
                        Checkbox::make('print_status')
                            ->label('Stampa stato ordine'),
                        Checkbox::make('always_print_prices')
                            ->label('Stampa i prezzi nella bolla'),
                        Checkbox::make('print_total')
                            ->label('Stampa il totale'),
                    ]),

                Fieldset::make('Acconti e sconti')
                    ->schema([
                        TextInput::make('down_payment')
                            ->label('Acconto')
                            ->placeholder('0')
                            ->numeric(),
                        Checkbox::make('down_payment_with_vat')
                            ->label('L\'acconto è comprensivo di IVA'),
                        TextInput::make('fixed_discount')
                            ->label('Sconto fisso')
                            ->placeholder('0')
                            ->numeric(),
                        Checkbox::make('discount_with_vat')
                            ->label('Lo sconto fisso è comprensivo di IVA'),
                        TextInput::make('percentage_discount')
                            ->label('Sconto percentuale')
                            ->placeholder('0')
                            ->numeric()
                            ->suffix('%'),
                    ])->columns(1),

                Repeater::make('order_items')
                    ->label('Articoli dell\'ordine')
                    ->relationship()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateOrderTotals($get, $set);
                    })
                    ->schema([
                        TextInput::make('real_unit_price_net')
                            ->hidden()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Set $set, ?OrderItem $record) {
                                $set('real_unit_price_net', $record?->unit_price_net);
                            }),

                        Select::make('product_id')
                            ->label('Prodotto')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->required()
                            ->columnSpan(6),
                        Textarea::make('info')
                            ->label('Dettagli')
                            ->placeholder('Informazioni aggiuntive sul prodotto')
                            ->autosize()
                            ->columnSpan(6),
                        TextInput::make('quantity')
                            ->label('Qty')
                            ->numeric()
                            ->default(1)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            })
                            ->columnSpan(3),
                        Select::make('status')
                            ->label('Stato')
                            ->options(
                                collect(config('ecommerce.order_statuses'))
                                    ->mapWithKeys(fn(array $status, string $key): array => [$key => $status['label']])
                                    ->toArray()
                            )
                            ->extraInputAttributes(fn(callable $get) => [
                                'class' => 'text-status-' . $get('status'),
                            ])
                            ->required()
                            ->columnSpan(4),
                        TextInput::make('unit_price_net')
                            ->label('Pr. netto')
                            ->numeric()
                            ->columnSpan(3)
                            ->live(debounce: 500)
                            ->formatStateUsing(
                                fn($state) => self::formatNumber($state)
                            )
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                        TextInput::make('unit_price_gross')
                            ->label('Pr. ivato')
                            ->default(0)
                            ->numeric()
                            ->columnSpan(3)
                            ->live(debounce: 500)
                            ->formatStateUsing(
                                fn($state) => self::formatNumber($state)
                            )
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                        Checkbox::make('has_vat')
                            ->label('IVA')
                            ->default(true)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (Checkbox $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            })
                            ->columnSpan(2),
                        TextInput::make('total_price_gross')
                            ->label('Totale')
                            ->default(0)
                            ->columnSpan(3)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                    ])
                    ->columns(30)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->required(),

                Fieldset::make('Totale')
                    ->schema([
                        TextInput::make('price_net')
                            ->label('Imponibile')
                            ->inputMode('decimal')
                            ->formatStateUsing(fn($state) => self::formatNumber($state))
                            ->readOnly(),
                        TextInput::make('vat')
                            ->label('IVA')
                            ->inputMode('decimal')
                            ->formatStateUsing(fn($state) => self::formatNumber($state))
                            ->readOnly(),
                        TextInput::make('down_payment')
                            ->label('Acconto')
                            ->inputMode('decimal')
                            ->formatStateUsing(fn($state) => self::formatNumber($state))
                            ->readOnly(),
                        TextInput::make('total_discount')
                            ->label('Sconto')
                            ->inputMode('decimal')
                            ->formatStateUsing(fn($state) => self::formatNumber($state))
                            ->readOnly(),
                        TextInput::make('price_gross')
                            ->label('Totale ordine')
                            ->inputMode('decimal')
                            ->formatStateUsing(fn($state) => self::formatNumber($state))
                            ->readOnly(),
                    ])->columns(5)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Update the order item prices based on the changed field
     */
    private static function updatePrices(callable $get, callable $set, string $fieldName): void {
        $quantity = max(1, (int) $get('quantity'));
        $hasVat = $get('has_vat');

        if ($fieldName === 'unit_price_net') {
            // Calculate prices based on unit net price
            $unitPriceNet = (float) $get('unit_price_net');
            $unitPriceGross = $hasVat ? $unitPriceNet * (1 + self::VAT) : $unitPriceNet;
            $totalGross = $unitPriceGross * $quantity;

            $set('real_unit_price_net', $unitPriceNet);
        } else if ($fieldName === 'unit_price_gross') {
            // Calculate prices based on unit gross price
            $unitPriceGross = (float) $get('unit_price_gross');
            $unitPriceNet = $hasVat ? $unitPriceGross / (1 + self::VAT) : $unitPriceGross;
            $totalGross = $unitPriceGross * $quantity;

            $set('real_unit_price_net', $unitPriceNet);
        } else if ($fieldName === 'total_price_gross') {
            // Calculate prices based on total gross price
            $totalGross = (float) $get('total_price_gross');
            $unitPriceGross = $totalGross / $quantity;
            $unitPriceNet = $hasVat ? $unitPriceGross / (1 + self::VAT) : $unitPriceGross;

            $set('real_unit_price_net', $unitPriceNet);
        } else if ($fieldName === 'quantity' || $fieldName === 'has_vat') {
            $unitPriceNet = (float) $get('real_unit_price_net');
            $unitPriceGross = $hasVat ? $unitPriceNet * (1 + self::VAT) : $unitPriceNet;
            $totalGross = $unitPriceGross * $quantity;
        }

        if ($fieldName !== 'unit_price_net')
            $set('unit_price_net', self::formatNumber($unitPriceNet));
        if ($fieldName !== 'unit_price_gross')
            $set('unit_price_gross', self::formatNumber($unitPriceGross));
        if ($fieldName !== 'total_price_gross')
            $set('total_price_gross', self::formatNumber($totalGross));
    }

    /**
     * Update the order totals based on the order items
     */
    private static function updateOrderTotals(Get $get, Set $set): void {
        $items = $get('order_items') ?? [];

        $sumNet = 0.0;
        $sumGross = 0.0;

        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));

            $unitNet = $item['real_unit_price_net'] ?? 0;
            $unitGross = $item['has_vat'] ? $unitNet * (1 + self::VAT) : $unitNet;

            $sumNet += $unitNet * $qty;
            $sumGross += $unitGross * $qty;
        }

        $set('price_net', self::formatNumber($sumNet));
        $set('price_gross', self::formatNumber($sumGross));
        $set('vat', self::formatNumber($sumGross - $sumNet));
    }

    private static function updateOrderItemsStatuses($state, Get $get, Set $set): void {
        $items = $get('order_items') ?? [];

        // Update every repeater item's status
        $items = array_map(function (array $item) use ($state) {
            $item['status'] = $state;
            return $item;
        }, $items);

        $set('order_items', $items);
    }

    private static function formatNumber($number): string {
        return number_format((float) $number, 2, '.', '');
    }

    private static function extraAttributesForPrices(): array {
        return [
            'type' => 'text',
            'inputmode' => 'decimal',
            'oninput' => "let v = this.value
                    .replace(/,/g, '.')
                    .replace(/[^0-9.]/g, '')
                    .replace(/(\..*)\./g, '$1');
                const parts = v.split('.');
                if (parts.length === 2) parts[1] = parts[1].slice(0, 2);
                this.value = parts.join('.');
            ",
        ];
    }
}
