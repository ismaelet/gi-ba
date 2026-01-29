<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class OrderForm {
    private const VAT = 0.22;

    public static function configure(Schema $schema): Schema {
        return $schema
            ->components([
                Hidden::make('_internal_update')
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                        dump('Internal update state changed to: ' . ($state ? 'true' : 'false'));
                    })
                    ->default(false),

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
                            })
                            ->suffixAction(
                                Action::make('copy')
                                    ->icon(Heroicon::ClipboardDocumentList)
                                    ->tooltip('Copia nome cliente')
                                    ->color('gray')
                                    ->disabled(fn(Get $get) => blank(data_get($get('customer_data'), 'phone')))
                                    ->action(function (Get $get, $livewire) {
                                        $customerId = $get('customer_id');

                                        $name = Customer::whereKey($customerId)->value('name');

                                        if (!$name) {
                                            return;
                                        }

                                        $livewire->dispatch('clipboard:copy', text: $name);

                                        Notification::make()
                                            ->title('Copiato negli appunti')
                                            ->success()
                                            ->send();
                                    })
                            )

                            // Customer creation form
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nome completo')
                                    ->placeholder('Nome completo')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('Numero di telefono')
                                    ->placeholder('Numero di telefono')
                                    ->maxLength(50),

                                Textarea::make('info')
                                    ->label('Dettagli cliente')
                                    ->placeholder('Dettagli cliente')
                                    ->autosize(),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                if (Customer::where('name', $data['name'])->exists()) {
                                    Notification::make()
                                        ->title('Errore: esiste già un cliente con questo nome')
                                        ->danger()
                                        ->send();

                                    return Customer::where('name', $data['name'])->value('id');
                                }

                                $customer = Customer::create($data);

                                Notification::make()
                                    ->title('Cliente creato')
                                    ->success()
                                    ->send();

                                return $customer->getKey();
                            })
                            ->createOptionAction(function ($action) {
                                return $action
                                    ->label('Nuovo cliente')
                                    ->modalHeading('Crea cliente')
                                    ->modalSubmitActionLabel('Crea');
                            }),

                        Group::make()
                            ->statePath('customer_data')
                            ->visible(fn(Get $get) => filled($get('customer_id')))
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Numero di telefono')
                                    ->placeholder('Numero di telefono')
                                    ->afterStateHydrated(fn(?Order $record, Set $set) => $set('phone', $record?->customer?->phone ?? ''))
                                    ->copyable(),
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
                            ->default('1')
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
                            ->label('Stampa dettagli articoli')
                            ->default(true),
                        Checkbox::make('print_notes')
                            ->label('Stampa note'),
                        Checkbox::make('print_status')
                            ->label('Stampa stato ordine'),
                        Checkbox::make('always_print_prices')
                            ->label('Stampa i prezzi nella bolla'),
                        Checkbox::make('print_total')
                            ->label('Stampa il totale')
                            ->default(true),
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
                    ->addActionLabel('+')
                    ->relationship()
                    ->schema([
                        Hidden::make('real_unit_price_net')
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Set $set, ?OrderItem $record) {
                                $set('real_unit_price_net', $record?->unit_price_net);
                            }),

                        Select::make('product_id')
                            ->label('Prodotto')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                if ((float) $get('unit_price_net') > 0) {
                                    return;
                                }

                                $product = filled($state) ? Product::find($state) : null;
                                $customer = Customer::find($get('../../customer_id'));
                                $unitPriceNet = (float) ($customer ? OrderItem::where('product_id', $state)
                                    ->whereHas('order.customer', fn($query) => $query->whereKey($customer->getKey()))
                                    ->orderByDesc('created_at')
                                    ->value('unit_price_net') : $product?->price);

                                self::updatePrices(get: $get, set: $set, unitPriceNet: $unitPriceNet);
                            })
                            ->columnSpan(6)

                            // Create product option
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nome prodotto')
                                    ->placeholder('Nome prodotto')
                                    ->required()
                                    ->maxLength(255)
                            ])
                            ->createOptionUsing(function (array $data): int {
                                if (Product::where('name', $data['name'])->exists()) {
                                    Notification::make()
                                        ->title('Errore: esiste già un prodotto con questo nome')
                                        ->danger()
                                        ->send();

                                    return Product::where('name', $data['name'])->value('id');
                                }

                                $product = Product::create($data);

                                Notification::make()
                                    ->title('Prodotto creato')
                                    ->success()
                                    ->send();

                                return $product->getKey();
                            })
                            ->createOptionAction(function ($action) {
                                return $action
                                    ->label('Nuovo prodotto')
                                    ->modalHeading('Crea prodotto')
                                    ->modalSubmitActionLabel('Crea');
                            }),

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
                            ->live(onBlur: true)
                            ->extraInputAttributes(self::extraAttributesForInt())
                            ->afterStateUpdated(function (TextInput $component, Get $get, Set $set) {
                                self::runInternally($get, $set, function () use ($component, $get, $set) {
                                    self::updatePrices(get: $get, set: $set, fieldName: $component->getName());
                                });
                            })
                            ->columnSpan(2),

                        Select::make('status')
                            ->label('Stato')
                            ->default(function (Get $get) {
                                return $get('../../status') ?? '1';
                            })
                            ->live()
                            ->options(
                                collect(config('ecommerce.order_statuses'))
                                    ->mapWithKeys(fn(array $status, string $key): array => [$key => $status['label']])
                                    ->toArray()
                            )
                            ->extraInputAttributes(fn(callable $get) => ['class' => 'text-status-' . $get('status'),])
                            ->afterStateUpdated(fn(callable $set, callable $get) => self::updateOrderStatus($get, $set))
                            ->required()
                            ->columnSpan(4),

                        TextInput::make('unit_price_net')
                            ->label('Pr. netto')
                            ->default(0)
                            ->live(onBlur: true)
                            ->extraInputAttributes(self::extraAttributesForPrices())
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrateStateUsing(fn($state) => self::parseToFloat($state))
                            ->afterStateUpdated(function (TextInput $component, Get $get, Set $set) {
                                self::runInternally($get, $set, function () use ($component, $get, $set) {
                                    self::updatePrices(get: $get, set: $set, fieldName: $component->getName());
                                });
                            })
                            ->columnSpan(2),

                        TextInput::make('unit_price_gross')
                            ->label('Pr. ivato')
                            ->default(0)
                            ->live(onBlur: true)
                            ->extraInputAttributes(self::extraAttributesForPrices())
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrateStateUsing(fn($state) => self::parseToFloat($state))
                            ->afterStateUpdated(function (TextInput $component, Get $get, Set $set) {
                                self::runInternally($get, $set, function () use ($component, $get, $set) {
                                    self::updatePrices(get: $get, set: $set, fieldName: $component->getName());
                                });
                            })
                            ->columnSpan(2),

                        Checkbox::make('has_vat')
                            ->label('IVA')
                            ->default(true)
                            ->live()
                            ->afterStateUpdated(function (Checkbox $component, Get $get, Set $set) {
                                self::runInternally($get, $set, function () use ($component, $get, $set) {
                                    self::updatePrices(get: $get, set: $set, fieldName: $component->getName());
                                });
                            })
                            ->columnSpan(1),

                        TextInput::make('total_price_gross')
                            ->label('Totale')
                            ->default(0)
                            ->live(onBlur: true)
                            ->extraInputAttributes(self::extraAttributesForPrices())
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->dehydrateStateUsing(fn($state) => self::parseToFloat($state))
                            ->afterStateUpdated(function (TextInput $component, Get $get, Set $set) {
                                self::runInternally($get, $set, function () use ($component, $get, $set) {
                                    self::updatePrices(get: $get, set: $set, fieldName: $component->getName());
                                });
                            })
                            ->columnSpan(2),
                    ])
                    ->columns(30)
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->required(),

                Group::make()
                    ->extraAttributes(['class' => 'total-panel'])
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
                            ->visible(fn($state) => self::parseToFloat($state) > 0)
                            ->dehydrated(false)
                            ->afterStateHydrated(fn($state, TextInput $component) => $component->state(self::parseNumber($state)))
                            ->readOnly(),
                        TextInput::make('total_discount')
                            ->label('Sconto')
                            ->visible(fn($state) => self::parseToFloat($state) > 0)
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

    private static function runInternally(Get $get, Set $set, callable $fn): void {
        $fn();
    }

    /**
     * Update the order item prices based on the changed field
     */
    private static function updatePrices(Get $get, Set $set, ?string $fieldName = null, ?float $unitPriceNet = null): void {
        $quantity = max(1, (int) $get('quantity'));
        $hasVat = (bool) $get('has_vat');
        $vatMul = 1 + self::VAT;

        $toNet = fn(float $gross): float => $hasVat ? ($gross / $vatMul) : $gross;
        $toGross = fn(float $net): float => $hasVat ? ($net * $vatMul) : $net;

        $unitPriceNet = $unitPriceNet ?? match ($fieldName) {
            'unit_price_net'     => self::parseNumber($get('unit_price_net')),
            'unit_price_gross'   => $toNet(self::parseNumber($get('unit_price_gross'))),
            'total_price_gross'  => $toNet(self::parseNumber($get('total_price_gross')) / $quantity),
            default              => (float) $get('real_unit_price_net'),
        };

        $set('real_unit_price_net', $unitPriceNet);

        $unitPriceGross = $toGross($unitPriceNet);
        $totalGross     = $unitPriceGross * $quantity;

        $set('unit_price_net', self::parseNumber($unitPriceNet));
        $set('unit_price_gross', self::parseNumber($unitPriceGross));
        $set('total_price_gross', self::parseNumber($totalGross));

        self::updateOrderTotals(get: $get, set: $set);
    }

    /**
     * Update the order totals based on the order items
     */
    private static function updateOrderTotals(Get $get, Set $set): void {
        $items = $get('../../order_items') ?? [];

        $sumNet = 0.0;
        $sumGross = 0.0;

        foreach ($items as $item) {
            $qty = max(1, (int) ($item['quantity'] ?? 1));

            $unitNet = $item['real_unit_price_net'] ?? 0;
            $unitGross = $item['has_vat'] ? $unitNet * (1 + self::VAT) : $unitNet;

            $sumNet += $unitNet * $qty;
            $sumGross += $unitGross * $qty;
        }

        $set('../../price_net', self::parseNumber($sumNet));
        $set('../../price_gross', self::parseNumber($sumGross));
        $set('../../vat', self::parseNumber($sumGross - $sumNet));
    }

    /**
     * Update the order item statuses based on the order status
     */
    private static function updateOrderItemsStatuses($state, Get $get, Set $set): void {
        $items = $get('order_items') ?? [];

        // Update every repeater item's status
        $items = array_map(function (array $item) use ($state) {
            $item['status'] = $state;
            return $item;
        }, $items);

        $set('order_items', $items);
    }

    /**
     * Update the order status based on the order item statuses
     */
    public static function updateOrderStatus(Get $get, Set $set): void {
        $items = $get('../../order_items') ?? [];

        $statuses = [];

        foreach ($items as $item) {
            if (! array_key_exists('status', $item)) {
                continue;
            }

            $value = $item['status'];

            if ($value === null || $value === '') {
                continue;
            }

            $statuses[] = (int) $value;
        }

        if ($statuses === []) {
            return;
        }

        $min = (string) min($statuses);

        if ((string) $get('../../status') === $min) {
            return;
        }

        $set('../../status', $min);
    }

    private static function parseNumber(?string $number): string {
        return number_format((float) str_replace(',', '.', preg_replace('/[^0-9,\.]/', '', $number ?: 0)), 2, '.', '');
    }

    private static function parseToFloat(?string $number): float {
        return (float) str_replace(',', '.', preg_replace('/[^0-9,\.]/', '', $number ?: 0));
    }

    public static function extraAttributesForPrices(): array {
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

    public static function extraAttributesForInt(): array {
        return [
            'type' => 'text',
            'inputmode' => 'numeric',
            'oninput' => "this.value = this.value.replace(/[^0-9]/g, '');",
        ];
    }
}
