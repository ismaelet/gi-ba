@pushOnce('styles')
	<style>
		/* Slider */
		#slider {
			height: clamp(20rem, 55vh, 55rem);
		}

		#slider .background {
			clip-path: polygon(0 0, 0 95%, 15% 100%, 57% 95%, 79% 100%, 100% 94%, 100% 0);
		}

		#slider .background .image {
			opacity: .5;
			filter: saturate(1);
		}

		#slider .background .overlay {
			background: linear-gradient(rgba(240, 240, 240, 1), rgba(240, 240, 240, 0));
		}

		#slider .title-box {
			top: 46%;
		}

		#slider .title {
			max-width: 800px;
			font-size: min(5.5rem, 10vw);
			letter-spacing: .1em;
		}

		/* Gallery */
		#gallery {
			min-height: 100vh;
		}

		#gallery .image {
			padding-bottom: 100%;
			overflow: hidden;
		}

		#gallery .image img {
			transition: transform .5s;
		}

		#gallery .image:hover img {
			transform: scale(1.3);
		}

		#gallery .image .overlay {
			opacity: 0;
			background: linear-gradient(45deg, #E09B2E, #FADAA7);
			transition: opacity .5s;
		}

		#gallery .image:hover .overlay {
			opacity: .8;
		}

		#gallery .image .icon {
			opacity: 0;
			width: 3rem;
			height: 3rem;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -30%);
			transition: opacity .3s, transform .3s;
		}

		#gallery .image:hover .icon {
			opacity: 1;
			transform: translate(-50%, -50%);
			transition: opacity .3s .2s, transform .3s .2s;
		}

		/* Lightboxes */
		.arrow {
			width: 4rem;
			height: 4rem;
			transition: transform .3s;
		}

		.arrow.right {
			right: 0;
		}

		.arrow.left:hover {
			transform: translate(-10px, -50%);
		}

		.arrow.right:hover {
			transform: translate(10px, -50%);
		}

		.arrow .icon {
			width: 50%;
			height: 50%;
		}

		.arrow.left .icon {
			transform: translate(-50%, -50%) rotate(180deg);
		}

		/* Responsive */
		@media (max-width: 768px) {

			.arrow.left .icon {
				left: 0;
				transform: translateY(-50%) rotate(180deg);
			}

			.arrow.right .icon {
				left: auto;
				right: 0;
				transform: translateY(-50%);
			}

		}

		@media (max-width: 480px) {

			.lbox-img {
				max-width: 85%;
			}

		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		$(window).ready(function() {

			const count = $('#gallery').data('count');
			var pos = 0;

			$('.image').click(function() {
				pos = $(this).parents('.image-box').data('pos');
			});

			$('.arrow').click(function() {

				const self = $(this);

				if (self.hasClass('right')) {
					pos++;
				} else {
					pos--;
				}

				$('#image' + pos).click();

			});

			document.onkeydown = function(e) {


				if ($('.lbox:visible').length) {

					if (e.which == 37) {

						pos--;

						if (pos < 1) {
							pos = 1;
						}

						$('#image' + pos).click();

					} else if (e.which == 39) {

						pos++;

						if (pos > count) {
							pos = count;
						}

						$('#image' + pos).click();

					}

				}

			};

		});
	</script>
@endpushOnce

<x-layouts.legacy-main
	:headerConfig="['floating' => true, 'color' => 'dark']"
	:$locationTag
>
	<!-- Slider -->
	<section
		id="slider"
		class="fill-width"
	>

		<div class="background bgr bgr-2">
			<div
				class="image bgr"
				style="background-image: url('{{ public_asset('storage/collections/' . $collection['id'] . '.jpg') }}')"
			></div>
		</div>

		<div class="title-box cenY rel txt-cen">

			<h1 class="title txt-wht wrap txt-bold marb2">{{ $collection['name'] }}</h1>

		</div>

	</section>

	<!-- Gallery -->
	<section
		id="gallery"
		class="padt4 padb5 content-wrap"
		data-count="{{ $images->count() }}"
	>

		<p class="txt-1rem txt-sbold text-neutral-heading txt-cen pado2 pado1-480w padv5px">Se vedi qualcosa che ti piace <a
				href="{{ route('home') . '#contacts' }}"
				class="txt-bold txt-c1"
			>contattaci</a> per un preventivo o vieni a scoprire questi e molti altri articoli nel nostro negozio</p>

		<div class="txt-cen marv2">
			<a
				href="{{ route('gallery') }}"
				class="sbtn"
			>Torna alle collezioni</a>
		</div>

		<div class="maro-1 txt-0rem">

			@foreach ($images as $index => $image)
				@php
					$imagePath = '/uploads/images/' . $image['id'] . '_m.webp';
					$imageClientPath = file_exists(base_path() . '/public/legacy' . $imagePath)
					    ? asset($imagePath)
					    : asset('/uploads/images/' . $image['id'] . '_m.jpg');
				@endphp

				<!-- Image -->
				<div
					class="image-box pad1 ele4 ele3-1024w ele2-480w"
					data-pos="{{ $loop->iteration }}"
				>

					<div
						id="image{{ $loop->iteration }}"
						class="image bgr-f3 lbox-open clickable"
						data-tar="#lbox{{ $image['id'] }}"
					>

						<img
							src="{{ $imageClientPath }}"
							alt=""
							loading="lazy"
							class="img-cover"
						>

						<div class="bgr overlay"></div>

						<x-icon-expand class="icon bgr-wht" />

					</div>

					<!-- Lightbox -->
					<div
						id="lbox{{ $image['id'] }}"
						class="lbox"
					>

						<div class="lbox-bgr"></div>

						<div class="lbox-close-icon"></div>

						<img
							src="{{ str_replace('_m', '', $imageClientPath) }}"
							alt=""
							class="lbox-img lbox-close clickable"
						>

						@if (!$loop->first)
							<div class="arrow left cenY">
								<x-icon-arrow-simple-right class="icon cen bgr-wht clickable" />
							</div>
						@endif

						@if (!$loop->last)
							<div class="arrow right cenY">
								<x-icon-arrow-simple-right class="icon cen bgr-wht clickable" />
							</div>
						@endif

					</div>

				</div>
			@endforeach

		</div>

	</section>

</x-layouts.legacy-main>
