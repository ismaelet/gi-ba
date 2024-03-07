<?php

namespace Ecommerce;

function download_quote(int $cartId, string $title = 'Preventivo') {
	$debug = false;
	$cart = new Cart($cartId);

	// products
	$productsHtml = [];

	foreach ($cart->orders as $orderKey => $order) {
		$productName =  $order->product->name;

		if ($cart->printDescriptions && $order->description) {
			$productName .= '<br><span style="font-size: 12px;">' . $order->description . '</span>';
		}

		if ($cart->printStatus) {
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
		<p>' . $cart->downPaymentString . '</p>
	</div>';

	$discountHtml = '
	<div class="recap_item">
		<p>Sconto</p>
		<p>' . $cart->discountString . '</p>
	</div>';

	// populate
	$html = file_get_contents(SERVER_ROOT . '/app/templates/views/quote.html');
	$html = str_replace('{{ title }}', $title, $html);
	$html = str_replace('{{ date }}', date("d/m/Y"), $html);
	$html = str_replace('{{ quote-number }}', $cart->idCode, $html);
	$html = str_replace('{{ cart-net-price }}', $cart->netPriceString, $html);
	$html = str_replace('{{ cart-vat }}', $cart->vatString, $html);
	$html = str_replace('{{ cart-gross-price }}', $cart->priceString, $html);
	$html = str_replace('{{ customer-name }}', $cart->customer->name, $html);
	$html = str_replace('{{ products }}', $productsHtml, $html);
	$html = str_replace('{{ cart-down-payment }}', $cart->downPayment ? $downPaymentHtml : null, $html);
	$html = str_replace('{{ cart-discount }}', $cart->discount ? $discountHtml : null, $html);

	// generate the pdf
	if (!$debug) {
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$options->set('defaultFont', 'Helvetica');

		$dompdf = new \Dompdf\Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream($title . '_' . str_replace('#', '', $cart->idCode) . '.pdf');
	} else {
		exit($html);
	}
}
