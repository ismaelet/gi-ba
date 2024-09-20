<?php

namespace Ecommerce;

use App\Models\Ecommerce\Order;
use Ecommerce\Cart as OldCart;

function download_quote(int $cartId, string $title = 'Preventivo') {
	$debug = false;
	$oldCart = new OldCart($cartId);
	$cart = Order::find($cartId);

	// products
	$productsHtml = [];

	foreach ($oldCart->orders as $orderKey => $order) {
		$productName =  $order->product->name;

		if ($oldCart->printDescriptions && $order->description) {
			$productName .= '<br><span style="font-size: 12px;">' . $order->description . '</span>';
		}

		if ($oldCart->printStatus) {
			$productName .= '<br><span style="font-size: 12px;">' . $order->statusString . '</span>';
		}

		$productsHtml[] = '
		<div class="product product--' . ($orderKey % 2 ? 'even' : 'odd') . '">
			<p>' . $order->product->idCode . '</p>
			<p>' . $productName . '</p>
			<p>' . $order->quantity . '</p>
			<p>' . $order->netProductPriceString . '</p>
			<p>' . $order->netPriceString . '</p>
		</div>';
	}

	$productsHtml = implode('', $productsHtml);

	// recap panel
	$downPaymentHtml = '
	<div class="recap_item">
		<p>Acconto</p>
		<p>' . $oldCart->downPaymentString . '</p>
	</div>';

	$discountHtml = '
	<div class="recap_item">
		<p>Sconto</p>
		<p>' . $oldCart->discountString . '</p>
	</div>';

	// populate

	$html = file_get_contents(SERVER_ROOT . '/app/templates/views/quote.html');
	$html = str_replace('{{ title }}', $title, $html);
	$html = str_replace('{{ date }}', date("d/m/Y"), $html);
	$html = str_replace('{{ quote-number }}', $oldCart->idCode, $html);
	$html = str_replace('{{ cart-net-price }}', $oldCart->netPriceString, $html);
	$html = str_replace('{{ cart-vat }}', $oldCart->vatString, $html);
	$html = str_replace('{{ cart-gross-price }}', $oldCart->priceString, $html);
	$html = str_replace('{{ customer-name }}', $oldCart->customer->name, $html);
	$html = str_replace('{{ products }}', $productsHtml, $html);
	$html = str_replace('{{ cart-down-payment }}', $oldCart->downPayment ? $downPaymentHtml : '', $html);
	$html = str_replace('{{ cart-discount }}', $oldCart->discount ? $discountHtml : '', $html);
	$html = str_replace('{{ notes }}', $oldCart->print_notes ? $oldCart->customer->info : '', $html);

	if (!$cart->print_total) {
		$html = str_replace('<section class="recap">', '<section class="recap hidden">', $html);
		$html = str_replace('<p class="iva-warning hidden', '<p class="iva-warning', $html);
	}

	if (!$cart->print_notes) {
		$html = str_replace('<p class="notes">', '<p class="notes hidden">', $html);
	}

	// generate the pdf
	if (!$debug) {
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'Helvetica');

		$dompdf = new \Dompdf\Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream($title . '_' . str_replace('#', '', $oldCart->idCode) . '.pdf');
	} else {
		exit($html);
	}
}
