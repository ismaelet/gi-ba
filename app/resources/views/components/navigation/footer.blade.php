@pushOnce('styles')
	<style>
		/* Contacts */
		.f-contact {
			opacity: .9;
			transition: opacity .2s;
		}

		/* Link */
		.f-link {
			font-size: .625rem;
			letter-spacing: .2em;
			text-transform: uppercase;
			transition: color .2s;
		}
	</style>
@endpushOnce

<footer class="padv2 bgr-c3">

	<div
		id="f-contacts"
		class="txt-cen flex flex-wrap justify-center gap-6"
	>
		<a
			class="f-contact"
			href="{{ config('contacts.whatsapp.url') }}"
		>
			<x-icon-whatsapp class="text-neutral-heading hover:text-primary-400 h-5 w-5 transition-colors" />
		</a>
		<a
			class="f-contact"
			href="{{ config('contacts.phone.url') }}"
		>
			<x-icon-phone class="text-neutral-heading hover:text-primary-400 h-5 w-5 transition-colors" />
		</a>
		<a
			class="f-contact"
			href="{{ config('contacts.email.url') }}"
		>
			<x-icon-email class="text-neutral-heading hover:text-primary-400 h-5 w-5 transition-colors" />
		</a>
		<a
			class="f-contact"
			href="{{ config('contacts.facebook.url') }}"
		>
			<x-icon-facebook class="text-neutral-heading hover:text-primary-400 h-5 w-5 transition-colors" />
		</a>
	</div>

	<div
		id="f-links"
		class="txt-cen mart1"
	>
		<p class="f-link">Copyright © {{ now()->year }} Gi&Ba</p>
		<p class="f-link mt-1">Sito web realizzato da <a
				class="txt-c1-h"
				href="mailto:ismaele.tuffanelli@gmail.com"
			>Ismaele Tuffanelli</a></p>
	</div>

</footer>
