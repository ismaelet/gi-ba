@pushOnce('styles')
	<style>
		/* Structure */
		.mbtn {
			font-size: 1rem;
		}

		/* Slider */
		#slider {
			height: 100vh;
			min-height: 45rem;
			padding: 0 3rem 0 7rem;
		}

		#slider .content {
			grid-template-columns: minmax(0, 45fr) minmax(0, 55fr);
		}

		#slider .title-box {
			padding-bottom: 4rem;
			z-index: 1;
		}

		#slider .title {
			font-size: clamp(6rem, 11vw, 11rem);
		}

		#slider .title::before {
			content: attr(data-content);
			position: absolute;
			top: -0.14em;
			left: 0.14em;
			color: transparent;
			-webkit-text-stroke: 1px var(--color-neutral-heading);
		}

		#slider .title::after {
			content: attr(data-content);
			position: relative;
		}

		#slider .title-img .image {
			object-fit: contain;
			object-position: left;
			width: 100%;
			max-height: 17rem;
		}

		#slider .image-shadow {
			opacity: 0.7;
			position: absolute;
			top: -5%;
			left: 3.5%;
		}

		#slider .stitle {
			position: absolute;
			left: 18%;
			bottom: 5%;
		}

		#slider .desc {
			font-size: clamp(0.9rem, 1.2vw, 1.25rem);
			letter-spacing: 0.18em;
		}

		#slider .contact-box {
			grid-template-columns: auto 191px;
			max-width: 620px;
		}

		#slider .btn-icon {
			margin-left: 2rem;
		}

		#slider .img-box {
			width: 110%;
			height: 100%;
			left: -10%;
		}

		#slider .img-box {
			background-position: right;
		}

		#slider .socials {
			position: absolute;
			bottom: 3rem;
		}

		#slider .social {
			display: inline-block;
			width: 1.5rem;
			height: 1.5rem;
			margin-right: 2rem;
		}

		/* Sections */
		.content-wrap {
			max-width: 1600px;
		}

		.section-title {
			font-size: clamp(2rem, 4vw, 3rem);
			letter-spacing: 0.1em;
		}

		/* Services */
		.service {
			border: 1px solid transparent;
			border-radius: 2rem;
			width: 100%;
			max-width: 350px;
			cursor: default;
			transition: border 0.4s;
		}

		.service:hover {
			border-color: #eee;
		}

		.service-box {
			background-color: #fff;
			border-radius: inherit;
			padding: 2rem 1.5rem;
			transform-style: preserve-3d;
			transition: background 0.4s, box-shadow 0.4s;
		}

		.service:hover .service-box {
			background-color: #fafafa;
			box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
			z-index: 1;
		}

		.service .image {
			border: 1px solid rgba(39, 29, 40, 0.3);
			border-radius: 1.5em;
			width: clamp(5rem, 12vw, 8rem);
			height: clamp(5rem, 12vw, 8rem);
			box-sizing: border-box;
			transform: translateZ(40px);
			transition: background 0.4s, border 0.4s;
		}

		.service:hover .image {
			background-color: var(--color-primary-400);
			border-color: transparent;
		}

		.service .icon {
			width: 60%;
			height: 60%;
			transition: background 0.4s;
		}

		.service:hover .icon {
			background-color: #fff;
		}

		.service .title {
			max-width: 350px;
			margin: 0 auto;
			transform: translateZ(25px);
		}

		.service .desc {
			transform: translateZ(40px);
		}

		/* Gallery */
		#gallery .image {
			overflow: hidden;
		}

		#image9 {
			display: none;
		}

		#gallery .image .background {
			transition: transform 0.6s;
		}

		#gallery .image:hover .background {
			transform: scale(1.3);
		}

		#gallery .image .overlay {
			opacity: 0;
			background-color: #000;
			transition: opacity 0.6s;
		}

		#gallery .image:hover .overlay {
			opacity: 0.7;
		}

		#gallery .search {
			opacity: 0;
			width: 5rem;
			height: 5rem;
			transform: translate(-50%, 0);
			transition: opacity 0.6s, transform 0.6s;
		}

		#gallery .image:hover .search {
			opacity: 1;
			transform: translate(-50%, -50%);
		}

		/* Contacts */
		#contacts {
			background-color: #fcf9f4;
			padding: 4rem 2rem 5rem 2rem;
		}

		#contacts .body {
			display: inline-grid;
			width: 1400px;
			max-width: 100%;
			grid-column-gap: 4rem;
		}

		#contacts .stitle {
			font-size: 2.2rem;
		}

		/* Contacts: form */
		#contacts .form {
			box-shadow: 20px 40px 30px -30px rgba(0, 0, 0, 0.2);
			border-radius: 1.5rem;
			max-width: 600px;
			padding: 4rem 3rem;
			box-sizing: border-box;
		}

		#contacts .form .bgr {
			border-radius: inherit;
		}

		#contacts .form .border {
			opacity: 0.5;
			border: 1px solid var(--color-neutral-heading);
			top: -1.5rem;
			left: 1.5rem;
			box-sizing: border-box;
		}

		#contacts .form .background {
			background-color: #f2f0eb;
		}

		#mail-sent {
			background-color: #137415;
			padding: 10px 0;
			margin-bottom: 2rem;
			color: #fff;
			font-size: 1rem;
			text-align: center;
		}

		.field {
			margin-bottom: 0.5rem;
		}

		.contact-lbl {
			padding-top: 1rem;
			transform-origin: left;
			transform: translateY(2rem) scale(1.2);
			line-height: 1rem;
			pointer-events: none;
			z-index: 1;
			transition: transform 0.3s, color 0.3s;
		}

		.focus .contact-lbl {
			transform: translateY(0.5rem) scale(1);
		}

		.contact-lbl-box:not(.focus) .contact-lbl {
			color: #777 !important;
		}

		#contacts .field .inp,
		#contacts .field .textarea {
			background-color: transparent;
			display: inline-block;
			border: none;
			border-bottom: 1px solid #999;
			border-radius: 0;
			box-shadow: none !important;
			padding: 0;
			margin: 0;
			font-size: 0.9rem;
			box-sizing: border-box;
		}

		#contacts .field .inp {
			line-height: 3rem;
		}

		#contacts .field .textarea {
			width: 100%;
			height: 11rem;
			padding-top: 1rem;
			resize: none;
		}

		#contacts .mbtn {
			min-width: 200px;
		}

		/* Contacts: data */
		#contacts .data {
			padding-top: 4rem;
		}

		#contacts .link {
			display: inline-block;
			margin: 10px 0;
			white-space: nowrap;
		}

		#contacts .link .icon {
			display: inline-block;
			width: 1.8rem;
			height: 1.8rem;
			margin-right: 10px;
			vertical-align: middle;
		}

		#contacts .link:hover .icon {
			background-color: var(--color-primary-400);
		}

		#contacts .link p {
			display: inline-block;
			font-size: 1.1rem;
			vertical-align: middle;
		}

		#map iframe {
			width: 100%;
			height: 20rem;
		}

		/* Responsive */
		@media (max-width: 1440px) {
			#slider {
				padding: 0 0 0 3rem;
				min-height: 44rem;
			}

			#slider .contact-box {
				grid-template-columns: 1fr;
				grid-row-gap: 2rem;
				max-width: 400px;
				margin-top: 5rem;
			}

			#slider .text {
				font-size: 1rem;
			}

			#gallery .image h1 {
				font-size: 2rem;
			}
		}

		@media (orientation: portrait) {
			#slider {
				height: auto;
				padding: 0 2rem;
			}

			#slider .content {
				display: flex;
				width: 100%;
				flex-direction: column;
				box-sizing: border-box;
			}

			#slider .title-box {
				order: 2;
				width: 100%;
				text-align: center;
			}

			#slider .title {
				display: inline-block;
			}

			#slider .title-img .image {
				width: auto;
				height: 13rem;
				object-position: center;
			}

			#slider .contact-box {
				display: inline-grid;
				max-width: 550px;
				margin-top: 2rem;
			}

			#slider .img-box {
				order: 1;
				width: 75vw;
				height: auto;
				margin: 0 auto;
				left: -4%;
			}

			#slider .img-box .image {
				aspect-ratio: 1 / .95;
				position: relative;
			}

			#slider .socials {
				padding-bottom: 3rem;
				position: relative;
				bottom: 0;
			}

			#contacts .mbtn {
				display: block;
				margin: 0 auto;
			}
		}

		@media (max-width: 1024px) {
			#slider .stitle {
				left: 20%;
				font-size: 1.1rem;
			}

			#services {
				padding: 5rem 0;
			}

			#services .content {
				margin-top: 4rem;
			}

			#image5,
			#image8 {
				display: none;
			}

			#gallery .more-txt {
				font-size: 2rem;
			}

			#gallery .image h1 {
				font-size: 1.5rem;
			}

			#contacts .form {
				max-width: none;
			}
		}

		@media (max-width: 768px) {
			#contacts .stitle {
				font-size: 1.8rem;
			}

			#services .grid-cell:nth-child(3) {
				grid-column-start: span 2;
			}

			.service .title {
				max-width: 240px;
				font-size: 1.3rem;
			}
		}

		@media (max-width: 480px) {
			#slider .title-img {
				margin: 0 2rem;
			}

			#slider .title-img .image {
				width: 100%;
				height: auto;
			}

			#slider .contact-box {
				margin-top: 3rem;
			}

			#slider .img-box {
				width: 90vw;
			}

			#slider .img-box .image {
				aspect-ratio: 1 / .85;
			}

			#slider .socials {
				padding-bottom: 1.5rem;
			}

			#services .grid-cell:nth-child(3) {
				grid-column-start: span 1;
			}

			#image8,
			#image9 {
				display: block;
			}

			#image7,
			#image3 {
				display: none;
			}

			#contacts .form .border {
				top: -10px;
				left: 10px;
			}

			#contacts .data .content {
				padding-bottom: 2rem;
			}

			#contacts .data .stitle {
				margin-bottom: 1rem;
			}

			#contacts .data .txt-lef {
				text-align: center;
			}
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', function() {

			// Enable smooth scrolling
			document.documentElement.style.scrollBehavior = 'smooth';

			/* Slider height */
			fixHeight();

			window.addEventListener('resize', function() {
				fixHeight();
			});

			function fixHeight() {
				const width = window.innerWidth;
				const height = window.innerHeight;
				const header = document.querySelector('header').offsetHeight;

				if (width > height) {
					document.getElementById('slider').style.height = `calc(${height - header}px)`;
				}
			}

			// Loading
			setTimeout(function() {
				document.getElementById('load-logo').classList.add('active');
			}, 200);

		});
	</script>
@endpushOnce

<x-layouts.legacy-main>

	@include('home.slider')
	@include('home.services')
	@include('home.gallery')
	@include('home.contacts')

</x-layouts.legacy-main>
