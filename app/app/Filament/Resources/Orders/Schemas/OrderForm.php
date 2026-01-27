<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\Order;
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
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm {
    private const VAT = 0.22;

    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                Fieldset::make('Dati del cliente')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Cliente')
                            ->relationship('customer', 'name')
                            ->searchable(['name', 'phone'])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $customer = filled($state) ? Customer::find($state) : null;

                                $set('customer_data', [
                                    'phone' => $customer?->phone ?? '',
                                    'info'  => $customer?->info  ?? '',
                                ]);
                            }),

                        Group::make()
                            ->statePath('customer_data')
                            ->visible(fn(Get $get) => filled($get('customer_id')))
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Numero di telefono')
                                    ->placeholder('Numero di telefono')
                                    ->afterStateHydrated(fn(?Order $record, Set $set) => $set('phone', $record?->customer?->phone ?? '')),
                                Textarea::make('info')
                                    ->label('Dettagli cliente')
                                    ->placeholder('Dettagli cliente')
                                    ->autosize()
                                    ->afterStateHydrated(fn(?Order $record, Set $set) => $set('info', $record?->customer?->info ?? '')),
                            ]),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Fieldset::make('Dati dell\'ordine')
                    ->schema([
                        TextInput::make('id')
                            ->label('Numero dell\'ordine')
                            ->placeholder('Numero dell\'ordine')
                            ->readOnly()
                            ->dehydrated(false)
                            ->visible(fn(?Order $record) => filled($record))
                            ->copyable(),
                        DatePicker::make('created_at')
                            ->label('Data')
                            ->default(now())
                            ->readOnly()
                            ->columnSpan(fn(?Order $record) => filled($record) ? 1 : 2),
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
                            ->suffix('€')
                            ->default('0.00')
                            ->step(0.01)
                            ->formatStateUsing(fn($state) => self::parseNumber($state)),
                        Checkbox::make('down_payment_with_vat')
                            ->label('L\'acconto è comprensivo di IVA'),
                        TextInput::make('fixed_discount')
                            ->label('Sconto fisso')
                            ->suffix('€')
                            ->default('0.00')
                            ->step(0.01)
                            ->formatStateUsing(fn($state) => self::parseNumber($state)),
                        Checkbox::make('discount_with_vat')
                            ->label('Lo sconto fisso è comprensivo di IVA'),
                        TextInput::make('percentage_discount')
                            ->label('Sconto percentuale')
                            ->suffix('%')
                            ->numeric()
                            ->step(0.01)
                            ->default('0'),
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

                        TextInput::make('info')
                            ->label('Dettagli')
                            ->placeholder('Informazioni aggiuntive sul prodotto')
                            ->extraInputAttributes([
                                'x-on:mouseenter' => '$el.title = $el.value',
                                'x-on:focus'      => '$el.title = $el.value',
                                'x-on:input'      => '$el.title = $el.value',
                            ])
                            ->columnSpan(10),

                        TextInput::make('quantity')
                            ->label('Qty')
                            ->default(1)
                            ->live(debounce: 500)
                            ->extraInputAttributes(self::extraAttributesForInt())
                            ->afterStateUpdated(fn(TextInput $component, callable $set, callable $get) => self::updatePrices(get: $get, set: $set, fieldName: $component->getName()))
                            ->columnSpan(2),

                        Select::make('status')
                            ->label('Stato')
                            ->options(
                                collect(config('ecommerce.order_statuses'))
                                    ->mapWithKeys(fn(array $status, string $key): array => [$key => $status['label']])
                                    ->toArray()
                            )
                            ->extraInputAttributes(fn(callable $get) => ['class' => 'text-status-' . $get('status'),])
                            ->required()
                            ->columnSpan(4),

                        TextInput::make('unit_price_net')
                            ->label('Pr. netto')
                            ->default(0)
                            ->live(debounce: 500)
                            ->extraInputAttributes(self::extraAttributesForPrices())
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrateStateUsing(fn($state) => self::parseToFloat($state))
                            ->afterStateUpdated(fn(TextInput $component, callable $set, callable $get) => self::updatePrices(get: $get, set: $set, fieldName: $component->getName()))
                            ->columnSpan(2),

                        TextInput::make('unit_price_gross')
                            ->label('Pr. ivato')
                            ->default(0)
                            ->live(debounce: 500)
                            ->extraInputAttributes(self::extraAttributesForPrices())
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrateStateUsing(fn($state) => self::parseToFloat($state))
                            ->afterStateUpdated(fn(TextInput $component, callable $set, callable $get) => self::updatePrices(get: $get, set: $set, fieldName: $component->getName()))
                            ->columnSpan(2),
                        Checkbox::make('has_vat')
                            ->label('IVA')
                            ->default(true)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn(TextInput $component, callable $set, callable $get) => self::updatePrices(get: $get, set: $set, fieldName: $component->getName()))
                            ->columnSpan(2),
                        TextInput::make('total_price_gross')
                            ->label('Totale')
                            ->default(0)
                            ->live(debounce: 500)
                            ->extraInputAttributes(self::extraAttributesForPrices())
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrateStateUsing(fn($state) => self::parseToFloat($state))
                            ->afterStateUpdated(fn(TextInput $component, callable $set, callable $get) => self::updatePrices(get: $get, set: $set, fieldName: $component->getName()))
                            ->columnSpan(2),
                    ])
                    ->columns(30)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->required(),

                Fieldset::make('Totale')
                    ->schema([
                        TextInput::make('price_net')
                            ->label('Imponibile')
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrated(false)
                            ->readOnly(),
                        TextInput::make('vat')
                            ->label('IVA')
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrated(false)
                            ->readOnly(),
                        TextInput::make('down_payment')
                            ->label('Acconto')
                            ->dehydrated(false)
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->readOnly(),
                        TextInput::make('total_discount')
                            ->label('Sconto')
                            ->dehydrated(false)
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->readOnly(),
                        TextInput::make('price_gross')
                            ->label('Totale ordine')
                            ->dehydrated(false)
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
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
            $unitPriceNet = self::parseNumber($get('unit_price_net'));
            $unitPriceGross = $hasVat ? $unitPriceNet * (1 + self::VAT) : $unitPriceNet;
            $totalGross = $unitPriceGross * $quantity;

            $set('real_unit_price_net', $unitPriceNet);
        } else if ($fieldName === 'unit_price_gross') {
            // Calculate prices based on unit gross price
            $unitPriceGross = self::parseNumber($get('unit_price_gross'));
            $unitPriceNet = $hasVat ? $unitPriceGross / (1 + self::VAT) : $unitPriceGross;
            $totalGross = $unitPriceGross * $quantity;

            $set('real_unit_price_net', $unitPriceNet);
        } else if ($fieldName === 'total_price_gross') {
            // Calculate prices based on total gross price
            $totalGross = self::parseNumber($get('total_price_gross'));
            $unitPriceGross = $totalGross / $quantity;
            $unitPriceNet = $hasVat ? $unitPriceGross / (1 + self::VAT) : $unitPriceGross;

            $set('real_unit_price_net', $unitPriceNet);
        } else if ($fieldName === 'quantity' || $fieldName === 'has_vat') {
            $unitPriceNet = (float) $get('real_unit_price_net');
            $unitPriceGross = $hasVat ? $unitPriceNet * (1 + self::VAT) : $unitPriceNet;
            $totalGross = $unitPriceGross * $quantity;
        }

        if ($fieldName !== 'unit_price_net')
            $set('unit_price_net', self::parseNumber($unitPriceNet));
        if ($fieldName !== 'unit_price_gross')
            $set('unit_price_gross', self::parseNumber($unitPriceGross));
        if ($fieldName !== 'total_price_gross')
            $set('total_price_gross', self::parseNumber($totalGross));
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

        $set('price_net', self::parseNumber($sumNet));
        $set('price_gross', self::parseNumber($sumGross));
        $set('vat', self::parseNumber($sumGross - $sumNet));
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

    private static function parseNumber(?string $number): string {
        return number_format((float) str_replace(',', '.', preg_replace('/[^0-9,\.]/', '', $number ?: 0)), 2, '.', '');
    }

    private static function parseToFloat(?string $number): float {
        return (float) str_replace(',', '.', preg_replace('/[^0-9,\.]/', '', $number ?: 0));
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
            'onblur' => "let v = parseFloat(this.value.replace(/,/g, '.').replace(/[^0-9.]/g, ''));
                if (isNaN(v)) v = 0;
                this.value = v.toFixed(2);
            ",
        ];
    }

    private static function extraAttributesForInt(): array {
        return [
            'type' => 'text',
            'inputmode' => 'numeric',
            'oninput' => "this.value = this.value.replace(/[^0-9]/g, '');",
        ];
    }
}
