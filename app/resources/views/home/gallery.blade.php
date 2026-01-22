@php

	$images = [
	    false,
	    ['T-shirt &<br>cappellini', '#271D28'],
	    false,
	    ['Felpe &<br>hoodie', '#EEAD47'],
	    ['Abbigliamento<br>professionale', '#EED09C'],
	    false,
	    ['Brochures &<br>striscioni', '#69636A'],
	    false,
	    ['Abbigliamento<br>professionale', '#EED09C'],
	];

@endphp

<section
	id="gallery"
	class="padb5 txt-cen fill-width"
>

	<!-- Title -->
	<h1 class="section-title txt-bold txt-cen txt-4rem text-neutral-heading txt-upp">Galleria</h1>

	<!-- Gallery -->
	<div class="content grid4 grid3-1024w grid2-480w mart4">

		@foreach ($images as $key => $image)
			<a
				id="image{{ $key }}"
				href="{{ route('gallery') }}"
				class="image load ld-lef ld-del{{ $key }} h-full"
				style="background-color: {{ $image[1] ?? '#eee' }}"
			>

				@if ($image)
					<h1 class="txt-bold txt-wht txt-upp wid100 cenY txt-25rem">{!! $image[0] !!}</h1>
				@else
					<img src="{{ Vite::image('home/gallery' . ($key + 1) . '.jpg') }}">

					<div class="bgr overlay"></div>

					<x-icon-search class="search cen icon text-neutral-200" />
				@endif

			</a>
		@endforeach

	</div>

	<!-- Gallery foot -->
	<h1 class="more-txt txt-25rem text-neutral-heading mart4">... E MOLTO ALTRO</h1>

	<p class="txt-20px marv2 pado2">Scopri tutti i nostri prodotti e servizi nella nostra galleria di foto.</p>

	<div class="btn-full load ld-lef ld-del2">
		<span class="btn-double"></span>
		<a
			href="{{ route('gallery') }}"
			class="mbtn double"
		>
			<span class="btn-content">
				<span class="btn-txt">Galleria</span>
				<x-icon-arrow-right class="btn-icon marl1 text-neutral-heading" />
			</span>
		</a>
	</div>

</section>
