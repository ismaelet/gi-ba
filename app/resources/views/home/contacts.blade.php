<!-- Contacts -->
<section
	id="contacts"
	class="bgr-c3 txt-cen fill-width"
>

	<!-- Title -->
	<h1 class="section-title txt-bold txt-cen text-neutral-heading txt-upp">Contatti</h1>

	<div class="body grid2 grid1-1024w al-top mart7">

		<!-- Form -->
		<form
			class="form txt-lef"
			method="POST"
			action="{{ route('contact-request.send') }}"
		>
			@csrf

			<div class="bgr border"></div>
			<div class="background bgr"></div>

			<!-- Title -->
			<h1 class="stitle marb2 txt-light txt-cen text-neutral-heading">Dubbi? <span class="txt-bold">Scrivici qui</span></h1>

			@session('success')
				<p
					id="mail-sent"
					class="txt-cen"
				>{!! session('success') !!}</p>
			@endsession

			@error('spam')
				<p class="inp err">{!! $message !!}</p>
			@enderror

			<!-- Input -->
			<div class="field">
				<div class="contact-lbl-box">
					<p class="contact-lbl txt-c1">Nome e cognome</p>
				</div>
				<input
					type="text"
					name="name"
					class="inp"
					required
					onfocus="$(this).prev().addClass('focus')"
					onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}"
				>
			</div>

			<!-- Input -->
			<div class="field">
				<div class="contact-lbl-box">
					<p class="contact-lbl txt-c1">Indirizzo email</p>
				</div>
				<input
					type="email"
					name="email"
					class="inp"
					required
					onfocus="$(this).prev().addClass('focus')"
					onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}"
				>
			</div>

			<!-- Input -->
			<div class="field">
				<div class="contact-lbl-box">
					<p class="contact-lbl txt-c1">Testo del messaggio</p>
				</div>
				<textarea
				 name="text"
				 class="textarea"
				 maxlength="500"
				 required
				 onfocus="$(this).prev().addClass('focus')"
				 onblur="if (!$(this).val().length) {$(this).prev().removeClass('focus')}"
				></textarea>
			</div>

			<!-- Button -->
			<div class="txt-cen padt2">
				<div class="btn-full">
					<span class="btn-double"></span>
					<button
						type="submit"
						name="send"
						class="mbtn double"
					>
						<span class="btn-content">Invia</span>
					</button>
				</div>
			</div>

		</form>

		<!-- Contact data -->
		<div class="data">

			<!-- Title -->
			<h1 class="stitle marb4 txt-light txt-cen text-neutral-heading">I nostri <span class="txt-bold">contatti</span></h1>

			<div class="content txt-lef grid2 grid1-480w grid-pad2 al-top">

				<!-- Links -->
				<div class="links">

					<a
						href="{{ config('custom.contacts.facebook.url') }}"
						class="flex flex-wrap items-center gap-4"
					>
						<x-icon-facebook class="text-neutral-heading h-5 w-5" />
						<p class="text-lg">{{ config('custom.contacts.facebook.label') }}</p>
					</a><br>

					<a
						href="{{ config('custom.contacts.whatsapp.url') }}"
						class="flex flex-wrap items-center gap-4"
					>
						<x-icon-whatsapp class="text-neutral-heading h-5 w-5" />
						<p class="text-lg">{{ config('custom.contacts.whatsapp.label') }}</p>
					</a><br>

					<a
						href="{{ config('custom.contacts.phone.url') }}"
						class="flex flex-wrap items-center gap-4"
					>
						<x-icon-phone class="text-neutral-heading h-5 w-5" />
						<p class="text-lg">{{ config('custom.contacts.phone.label') }}</p>
					</a><br>

					<a
						href="{{ config('custom.contacts.email.url') }}"
						class="flex flex-wrap items-center gap-4"
					>
						<x-icon-email class="text-neutral-heading h-5 w-5" />
						<p class="text-lg">{{ config('custom.contacts.email.label') }}</p>
					</a>

				</div>

				<!-- Address -->
				<div class="address">

					<h1 class="txt-bold txt-15rem marb1 text-neutral-heading">Vieni a trovarci in:</h1>
					<p class="txt-11rem txt-lh2">Piazza Scopoli, 9<br>38033 Cavalese (TN)</p>

				</div>

			</div>

			<!-- Map -->
			<div
				id="map"
				class="mart2"
			>
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11027.738449230343!2d11.4601256!3d46.2912732!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xe685be1afa594d27!2sGi%20%26%20BA%20graphics%20and%20Promotion%20snc!5e0!3m2!1sen!2sit!4v1625702112859!5m2!1sen!2sit"
					style="border:0;"
					allowfullscreen=""
					loading="lazy"
				></iframe>
			</div>

		</div>

	</div>

</section>
