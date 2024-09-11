@php

	$orderItems = $getRecord()->order_items->load(
	    'product',
	    'product.type',
	    'product_with_trashed',
	    'product_with_trashed.type',
	    'product_with_trashed.design_with_trashed',
	    'type',
	    'color',
	    'position',
	    'size',
	    'logo',
	);

@endphp

<div class="order-list grid grid-cols-[max-content,max-content,minmax(0,1fr),max-content] gap-x-4">

	<div class="order-row grid-cols-subgrid col-span-full grid border-b py-4">
		<p class="">Articoli</p>
		<span></span>
		<span></span>
		<p class="text-right">{{ format_price($getRecord()->orderItemsPrice()) }}</p>
	</div>

	@if ($getRecord()->coupon)
		<div class="order-row grid-cols-subgrid col-span-full grid">
			<p class="">Codice</p>
			<span></span>
			<p class="">{{ $getRecord()->code }}</p>
		</div>
	@endif

	@if ($getRecord()->discount())
		<div class="order-row grid-cols-subgrid col-span-full grid border-b py-4">
			<p class="">Sconto</p>
			<span></span>
			<span></span>
			<p class="text-right">-{{ $getRecord()->discountString() }}</p>
		</div>
	@endif

	<div class="order-row grid-cols-subgrid col-span-full grid border-b py-4">
		<p class="">Spedizione</p>
		<span></span>
		<span></span>
		<p class="text-right">{{ $getRecord()->shippingFeeString() }}</p>
	</div>

	<div class="order-row grid-cols-subgrid col-span-full grid pt-4 text-lg text-neutral-700">
		<p class="">Totale</p>
		<span></span>
		<span></span>
		<p class="text-right">{{ $getRecord()->priceString() }}</p>
	</div>
</div>
