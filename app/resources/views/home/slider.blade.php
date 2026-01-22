<section
	id="slider"
	class="bgr-c3 fill-width"
>

	<div class="body grid h-full">

		<div class="content grid min-h-full">

			<!-- Title and contact -->
			<div class="title-box">

				<!-- Title -->
				<h1
					class="hide title txt-black text-neutral-heading load ld-lef"
					data-content="GI&BA"
				></h1>

				<h1 class="desc txt-upp text-neutral-heading load ld-lef ld-del4 z1 marb1">Abbigliamento e accessori personalizzati
				</h1>

				<div class="title-img inlb txt-0">
					<img
						class="image image-shadow"
						src="{{ Vite::image('home/title2.png') }}"
					>
					<img
						class="image front"
						src="{{ Vite::image('home/title1.png') }}"
					>
					<h1 class="stitle txt-20px text-neutral-heading txt-ita txt-sbold load ld-rig ld-del2">Di Shipping Dog SNC</h1>
				</div>

				<!-- Contact -->
				<div class="contact-box grid-pado2 mart10 grid">

					<p class="text txt-11rem load ld-lef">
						Contattaci per un chiedere un preventivo, oppure vieni a trovarci in negozio per scoprire la nostra ampia gamma
						di
						prodotti.
					</p>

					<div class="load ld-lef ld-del3">
						<div class="btn-full lg:w-full">

							<span class="btn-double"></span>

							<a
								href="#contacts"
								class="mbtn double"
							>
								<span class="btn-content">
									<span class="btn-txt">Contatti</span>
									<x-icon-arrow-right class="" />
								</span>
							</a>
						</div>
					</div>

				</div>

			</div>

			<!-- Image -->
			<div class="img-box hei100 load ld-rig">
				<img
					class="image bgr object-contain"
					src="{{ Vite::image('home/slider.jpg') }}"
				>
			</div>

		</div>

		<!-- Socials -->
		<div class="socials">
			<a
				href="{{ config('custom.contacts.whatsapp.url') }}"
				class="social load ld-bot ld-del1"
			>
				<x-icon-whatsapp class="text-neutral-heading hover:text-primary-400 h-full w-full transition-colors" />
			</a>
			<a
				href="{{ config('custom.contacts.phone.url') }}"
				class="social load ld-bot ld-del2"
			>
				<x-icon-phone class="text-neutral-heading hover:text-primary-400 h-full w-full transition-colors" />
			</a>
			<a
				href="{{ config('custom.contacts.email.url') }}"
				class="social load ld-bot ld-del3"
			>
				<x-icon-email class="text-neutral-heading hover:text-primary-400 h-full w-full transition-colors" />
			</a>
		</div>

	</div>

</section>
