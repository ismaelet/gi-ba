@php
	$services = [
	    [
	        'title' => 'Abbigliamento professionale per',
	        'desc' => '
			Alberghi<br>
			Ristoranti<br>
			Centri estetici<br>
			Studi medici',
	    ],
	    [
	        'title' => 'Personalizzazione di',
	        'desc' => '
			T-shirt ~ Felpe<br>
			Cappellini<br>
			Forniture sportive<br>
			Capi in genere<br>
			Decoro automezzi',
	    ],
	    [
	        'title' => 'Creazione di',
	        'desc' => '
			Brochures ~ Striscioni<br>
			Biglietti da visita<br>
			Adesivi pre-spaziati ~ Ricami<br>
			Stampa a caldo<br>
			Stampa digitale e
			serigrafica',
	    ],
	];
@endphp

<section
	id="services"
	class="padt7 padb10 txt-cen breakout"
>

	<div class="">

		<!-- Title -->
		<h1 class="section-title txt-cen text-neutral-heading txt-upp"><span class="txt-light">Cosa</span> <span
				class="txt-bold">Facciamo</span></h1>

		<!-- Services list -->
		<div class="content mart7 grid3 grid2-767w grid1-480w grid-pad2 grid-pad1-767w al-top">

			@foreach ($services as $key => $service)
				<div class="grid-cell txt-cen load ld-bot ld-del{{ $key * 2 }}">
					<div class="service inlb">

						<div
							class="service-box"
							data-tilt
						>

							<div class="image marb1 mx-auto grid place-items-center">
								<x-dynamic-component
									:component="'svg-home.service-' . ($key + 1)"
									class="text-neutral-heading h-[60%] w-[60%]"
								/>
							</div>

							<h1 class="title txt-14rem txt-bold">{!! $service['title'] !!}</h1>
							<p class="desc txt-11rem mart1">{!! $service['desc'] !!}</p>

						</div>

					</div>
				</div>
			@endforeach

		</div>

	</div>

</section>
