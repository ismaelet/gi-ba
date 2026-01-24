<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller {

    /**
     * Download the quote PDF for the given order   
     */
    public function downloadQuote(Request $request, Order $order) {
        return $this->generateInvoice(
            order: $order,
            type: 'quote'
        );
    }

    /**
     * Download the delivery note PDF for the given order
     */
    public function downloadDeliveryNote(Request $request, Order $order) {
        return $this->generateInvoice(
            order: $order,
            type: 'delivery_note'
        );
    }

    private function generateInvoice(Order $order, string $type) {
        $title = $type == 'quote' ? 'Preventivo' : 'Bolla di consegna';

        // Products
        $productsHtml = [];

        foreach ($order->order_items as $orderItemKey => $orderItem) {
            $productName =  $orderItem->name;

            if ($order->print_descriptions && $orderItem->info) {
                $productName .= '<br><span style="font-size: 12px;">' . $orderItem->info . '</span>';
            }

            if ($order->print_status) {
                $productName .= '<br><span style="font-size: 12px;">' . $orderItem->statusName() . '</span>';
            }

            $productsHtml[] = '
		<div class="product product--' . ($orderItemKey % 2 ? 'even' : 'odd') . '">
			<p>' . $orderItem->product->idCode() . '</p>
			<p>' . $productName . '</p>
			<p>' . $orderItem->quantity . '</p>
			<p class="visible-price">' . price($orderItem->product_price_net) . '</p>
			<p class="visible-price">' . price($orderItem->total_net_price) . '</p>
		</div>';
        }

        $productsHtml = implode('', $productsHtml);

        // recap panel
        $downPaymentHtml = '
	<div class="recap_item">
		<p>Acconto</p>
		<p>' . price($order->calcTotalDownPayment()) . '</p>
	</div>';

        $discountHtml = '
	<div class="recap_item">
		<p>Sconto</p>
		<p>' . price($order->calcTotalDiscount()) . '</p>
	</div>';

        // populate

        $html = file_get_contents(resource_path('html/invoice.html'));
        $html = str_replace('{{ title }}', $title, $html);
        $html = str_replace('{{ date }}', date("d/m/Y"), $html);
        $html = str_replace('{{ quote-number }}', $order->idCode(), $html);
        $html = str_replace('{{ cart-net-price }}', price($order->calcBasePrice()), $html);
        $html = str_replace('{{ cart-vat }}', price($order->calcVAT()), $html);
        $html = str_replace('{{ cart-gross-price }}', price($order->price), $html);
        $html = str_replace('{{ customer-name }}', $order->customer->name, $html);
        $html = str_replace('{{ products }}', $productsHtml, $html);
        $html = str_replace('{{ cart-down-payment }}', $order->calcTotalDownPayment() ? $downPaymentHtml : '', $html);
        $html = str_replace('{{ cart-discount }}', $order->calcTotalDiscount() ? $discountHtml : '', $html);
        $html = str_replace('{{ notes }}', $order->print_notes ? $order->customer->info : '', $html);

        if (!$order->print_total) {
            $html = str_replace('<section class="recap', '<section class="recap hidden', $html);
            $html = str_replace('<p class="iva-warning hidden', '<p class="iva-warning', $html);
        }

        if (!$order->print_notes) {
            $html = str_replace('<p class="notes">', '<p class="notes hidden">', $html);
        }

        // Bolla di consegna
        if ($type == 'delivery_note' && !$order->always_print_prices) {
            $html = str_replace('<div class="page-content', '<div class="page-content no-prices', $html);
        }

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($title . '_' . str_replace('#', '', $order->idCode()) . '.pdf');
    }
}
