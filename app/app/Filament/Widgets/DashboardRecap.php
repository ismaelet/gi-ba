<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\Widget;
use Illuminate\Support\Str;

class DashboardRecap extends Widget {

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-recap';

    /**
     * Get the status recap data
     */
    public function statusData(): array {
        $recapData = [];
        $statuses = config('ecommerce.order_statuses');

        foreach ($statuses as $statusId => $status) {
            $statusData = [];

            $statusData['title'] = $status['label'];
            $statusData['value'] = OrderItem::where('status', $statusId)->sum('price');
            $statusData['slug'] = $status['tag'] ?? Str::slug($status['label']);

            $recapData[$statusId] = $statusData;
        }

        return $recapData;
    }

    /**
     * Get the total billed withdrawn amount
     */
    public function billedWithdrawn(): float {
        return OrderItem::whereHas('order', function ($query) {
            $query->where('billed', 1)
                ->where('status', 6);
        })->sum('price');
    }

    /**
     * Get the total not billed withdrawn amount
     */
    public function notBilledWithdrawn(): float {
        return OrderItem::whereHas('order', function ($query) {
            $query->where('billed', 0)
                ->where('status', 6);
        })->sum('price');
    }
}
