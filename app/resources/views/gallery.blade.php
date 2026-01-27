@pushOnce('styles')
	<style>
		/* Structure */
		.content-wrap {
			max-width: 1600px;
		}

		/* Slider */
		#slider {
			height: clamp(20rem, 55vh, 55rem);
		}

		#slider .bgr {
			background-position: 70% 50%;
			clip-path: polygon(0 0, 0 95%, 26% 100%, 55% 94%, 80% 100%, 100% 97%, 100% 0);
		}

		#slider .title {
			font-size: min(7rem, 17vw);
			letter-spacing: .1em;
		}

		/* Gallery */
		#gallery {
			min-height: 100vh;
		}

		.collection {
			padding-bottom: 70%;
			overflow: hidden;
		}

		.collection img {
			transition: transform .5s;
		}

		.collection:hover img {
			transform: scale(1.1);
		}

		.collection .overlay {
			background: linear-gradient(to top, rgba(0, 0, 0, .85), rgba(0, 0, 0, .2));
			transition: opacity .5s;
		}

		.collection .overlay-h {
			opacity: 0;
			background: linear-gradient(45deg, rgba(39, 29, 40, 1), #EEAD47);
			transition: opacity .5s;

		}

		.collection:hover .overlay {
			opacity: 0;
		}

		.collection:hover .overlay-h {
			opacity: .8;
		}

		.collection .name {
			letter-spacing: .1em;
		}

		@media (max-width: 1440px) {

			.collection {
				padding-bottom: 60%;
			}

		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		$(document).ready(function() {

			$('.filter').click(function() {

				const self = $(this);
				const filter = self.data('filter');

				if (self.hasClass('active')) {

					$('.filter').removeClass('blur').removeClass('active');

					$('.image-box').show(400);

				} else {

					$('.filter').addClass('blur').removeClass('active');
					self.removeClass('blur').addClass('active');
					$('.filter-remove').removeClass('hide');

					$('.image-box').each(function() {

						const self = $(this);
						const category = self.data('category');

						if (category == filter) {

							self.show(400);

						} else {

							self.hide(400);
						}
					});

				}

			});

		});
	</script>
@endpushOnce

<x-layouts.legacy-main
	:headerConfig="['floating' => true]"
	:$locationTag
>

	<!-- Slider -->
	<section
		id="slider"
		class="fill-width"
	>

		<div
			class="bgr bgr-f3"
			style="background-image: url('{{ Vite::image('gallery/bgr.jpg') }}')"
		></div>

		<div class="title-box cenY rel txt-cen">

			<h1 class="title txt-wht txt-bold marb2">Galleria</h1>

		</div>

	</section>

	<!-- Collections -->
	<section
		id="collections"
		class="padt4 padb5 breakout"
	>

		<p class="txt-1rem txt-sbold text-neutral-heading txt-cen pado2 pado1-480w padv5px">Se vedi qualcosa che ti piace <a
				href="{{ route('home') . '#contacts' }}"
				class="txt-bold txt-c1"
			>contattaci</a> per un preventivo o vieni a scoprire questi e molti altri articoli nel nostro negozio</p>

		<div class="mart3 maro-1 txt-0rem txt-cen">

			@foreach ($collections as $collection)
				<!-- Image -->
				<div class="box pad1 ele3 ele2-1440w ele1-480w">

					<a
						href="{{ route('collection', $collection) }}"
						class="collection bgr-f3 block"
					>

						<img
							src="{{ storage_asset('collections/' . $collection->id . '_m.jpg') }}"
							alt=""
							loading="lazy"
							class="img-cover"
						>

						<div class="bgr overlay"></div>
						<div class="bgr overlay-h"></div>

						<h1 class="name cenY wid100 pado1 txt-wht txt-bold txt-25px">{{ $collection->name }}</h1>

					</a>

				</div>
			@endforeach

		</div>

	</section>


</x-layouts.legacy-main>
