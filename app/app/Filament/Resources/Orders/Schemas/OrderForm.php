<?php

namespace App\Filament\Resources\Orders\Schemas;

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
                            ->live(debounce: 500)
                            ->extraInputAttributes(fn(callable $get) => [
                                'class' => 'text-status-' . $get('status'),
                            ])
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
                            ->numeric()
                            ->suffix('€'),
                        Checkbox::make('down_payment_with_vat')
                            ->label('L\'acconto è comprensivo di IVA'),
                        TextInput::make('fixed_discount')
                            ->label('Sconto fisso')
                            ->placeholder('0')
                            ->numeric()
                            ->suffix('€'),
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
                        Select::make('product_id')
                            ->label('Prodotto')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->required()
                            ->columnSpan(4),
                        Textarea::make('info')
                            ->label('Dettagli aggiuntivi')
                            ->placeholder('Informazioni aggiuntive sul prodotto')
                            ->autosize()
                            ->columnSpan(4),
                        TextInput::make('quantity')
                            ->label('Quantità')
                            ->numeric()
                            ->default(1)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                        Select::make('status')
                            ->label('Stato articolo')
                            ->options(
                                collect(config('ecommerce.order_statuses'))
                                    ->mapWithKeys(fn(array $status, string $key): array => [$key => $status['label']])
                                    ->toArray()
                            )
                            ->extraInputAttributes(fn(callable $get) => [
                                'class' => 'text-status-' . $get('status'),
                            ])
                            ->required()
                            ->columnSpan(3),
                        TextInput::make('unit_price_net')
                            ->label('Pr. unitario netto')
                            ->inputMode('decimal')
                            ->default('0.00')
                            ->suffix('€')
                            ->columnSpan(2)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                        TextInput::make('unit_price_gross')
                            ->label('Pr. unitario ivato')
                            ->inputMode('decimal')
                            ->default('0.00')
                            ->suffix('€')
                            ->columnSpan(2)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                        Checkbox::make('has_vat')
                            ->label('ha IVA')
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
                            ->label('Prezzo totale')
                            ->inputMode('decimal')
                            ->default('0.00')
                            ->suffix('€')
                            ->columnSpan(2)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (TextInput $component, callable $set, callable $get) {
                                self::updatePrices(
                                    get: $get,
                                    set: $set,
                                    fieldName: $component->getName()
                                );
                            }),
                    ])
                    ->columns(20)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->required(),

                Fieldset::make('Totale')
                    ->schema([
                        TextInput::make('price_net')
                            ->label('Imponibile')
                            ->placeholder('0')
                            ->numeric()
                            ->readOnly()
                            ->suffix('€'),
                        TextInput::make('vat')
                            ->label('IVA')
                            ->placeholder('0')
                            ->numeric()
                            ->readOnly()
                            ->suffix('€'),
                        TextInput::make('down_payment')
                            ->label('Acconto')
                            ->placeholder('0')
                            ->numeric()
                            ->readOnly()
                            ->suffix('€'),
                        TextInput::make('total_discount')
                            ->label('Sconto')
                            ->placeholder('0')
                            ->numeric()
                            ->readOnly()
                            ->suffix('€'),
                        TextInput::make('price_gross')
                            ->label('Totale ordine')
                            ->placeholder('0')
                            ->numeric()
                            ->readOnly()
                            ->suffix('€'),
                    ])->columns(5)
                    ->columnSpanFull(),
            ]);
    }

    private static function updatePrices(callable $get, callable $set, string $fieldName): void {
        $quantity = max(1, (int) $get('quantity'));
        $hasVat = $get('has_vat');

        $unitPriceNet = match ($fieldName) {
            'unit_price_net' => (float) $get('unit_price_net'),
            'unit_price_gross' => $hasVat
                ? (float) $get('unit_price_gross') / (1 + self::VAT)
                : (float) $get('unit_price_gross'),
            'total_price_gross' => $hasVat
                ? (float) $get('total_price_gross') / (1 + self::VAT) / $quantity
                : (float) $get('total_price_gross') / $quantity,
            default => (float) $get('unit_price_net'),
        };

        $unitPriceGross = $hasVat ? $unitPriceNet * (1 + self::VAT) : $unitPriceNet;
        $totalGross = $unitPriceGross * $quantity;

        if ($fieldName !== 'unit_price_net')
            $set('unit_price_net', self::format($unitPriceNet));
        if ($fieldName !== 'unit_price_gross')
            $set('unit_price_gross', self::format($unitPriceGross));
        if ($fieldName !== 'total_price_gross')
            $set('total_price_gross', self::format($totalGross));
    }

    private static function updateOrderTotals(Get $get, Set $set): void {
        $items = $get('order_items') ?? [];

        $sumNet = 0.0;
        $sumGross = 0.0;

        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));

            $unitNet = self::format($item['unit_price_net'] ?? 0);
            $unitGross = self::format($item['unit_price_gross'] ?? 0);

            $sumNet += $unitNet * $qty;
            $sumGross += $unitGross * $qty;
        }

        $set('price_net', self::format($sumNet));
        $set('price_gross', self::format($sumGross));
        $set('vat', self::format($sumGross - $sumNet));
    }

    private static function format(float $value): string {
        return number_format($value, 2, '.', '');
    }
}
