@props([
    'config' => [],
    'locationTag' => null,
])

@php

	$user = auth()->user();

	//theme
	$h_theme['color'] = $config['color'] ?? 'light';
	$h_theme['floating'] = $config['floating'] ?? false;
	$h_theme['sticky'] = false;

	//elements
	$h_comp['logo'] = true;
	$h_comp['name-bar'] = false;
	$h_comp['srch-bar'] = false;
	$h_comp['lang-bar'] = true;
	$h_comp['login'] = true;
	$h_comp['force-mob'] = false;
	$h_comp['privacy'] = false;
	$h_comp['wishlist'] = false;

	//name
	$h_name = config('app.name');
	$h_name_m = $h_name;

	//links
	$h_links = [];
	$h_links_raw = [];

	$h_links_raw[] = ['title' => 'Cosa facciamo', 'loc' => '#home', 'url' => route('home') . '#services'];
	$h_links_raw[] = ['title' => 'Contatti', 'loc' => '#home', 'url' => route('home') . '#contacts'];
	$h_links_raw[] = ['title' => 'Galleria', 'loc' => 'gallery', 'url' => route('gallery')];
	$h_links_raw[] = ['title' => 'Gestione', 'loc' => 'admin', 'admin' => true, 'url' => '/admin'];

	foreach ($h_links_raw as $link) {
	    //check logged filter
	    if (isset($link['logged'])) {
	        if ($link['logged'] && !$user->id) {
	            continue;
	        } elseif (!$link['logged'] && $user->id) {
	            continue;
	        }
	    }

	    //check admin filter
	    if (isset($link['admin']) && !is_admin()) {
	        continue;
	    }

	    //check cart filter
	    if (isset($link['cart']) && !$user->cart_count) {
	        continue;
	    }

	    //add it to the list if valid
	    $h_links[] = $link;
	}

@endphp

@pushOnce('styles')
	<style>
		/* Header */
		header {
			width: 100%;
			box-sizing: border-box;
			top: 0;
			left: 0;
			z-index: 100;
		}

		header.light {
			--bgr-c: var(--color-background-500);
			--text-c: #303030;
			--text-h-c: var(--color-primary-400);
		}

		header.dark {
			--bgr-c: #101010;
			--text-c: #eee;
			--text-h-c: var(--color-primary-400);
		}

		header.floating {
			position: absolute;
		}

		header.sticky #h-content,
		header.open #h-content {
			position: fixed;
		}

		#h-content {
			display: grid;
			grid-template-columns: auto 0 70%;
			align-items: center;
			width: 100%;
			padding: 1.5rem 4rem;
			box-sizing: border-box;
			top: 0;
			left: 0;
		}

		/* Backgrounds */
		#h-content>.background,
		#h-mob-menu .background {
			transition: opacity .4s, background .4s;
		}

		header.light #h-content>.background {
			background-color: var(--bgr-c);
		}

		header.light #h-mob-menu .background {
			background-color: var(--bgr-c);
		}

		header.dark #h-content>.background {
			background-color: var(--bgr-c);
		}

		header.dark #h-mob-menu .background {
			background-color: var(--bgr-c);
		}

		header.floating:not(.open) #h-content>.background {
			background-color: transparent;
		}

		header.floating #h-content>.background {
			opacity: 0;
		}

		header.open #h-content>.background {
			opacity: 1;
		}

		/* Drop down menues */
		header .hanging-menu {
			position: absolute;
			top: 100%;
		}

		/* Logo */
		header .logo {
			display: inline-block;
			object-fit: contain;
			object-position: center;
			height: 3rem;
		}

		/* TO FIX - Name and searchbar */
		.h-name {
			font-size: 1.4rem;
			letter-spacing: .1em;
			transition: color .4s;
			text-transform: uppercase;
		}

		.h-name .caps {
			font-size: 1.15em;
		}

		#h-srch {
			margin-left: -1rem;
			font-size: 0;
			white-space: nowrap;
			overflow: hidden;
		}

		#h-srch .h-name-box {
			max-width: 100%;
			overflow: hidden;
			transition: max-width .1s .3s;
		}

		#h-srch-ico-box {
			display: inline-block;
			border-width: 1px;
			border-style: solid;
			border-color: #ddd;
			width: 50px;
			height: 50px;
			box-sizing: border-box;
			transition: border .2s;
			cursor: pointer;
		}

		#h-srch-ico {
			background-size: 20px;
			background-position: center;
			width: 100%;
			height: 100%;
		}

		#h-srch-ico-box .bgr {
			opacity: 0;
			width: calc(100% + 2px);
			height: calc(100% + 2px);
			top: -1px;
			left: -1px;
			transition: opacity .4s;
		}

		#h-srch-inp-box {
			display: inline-block;
			width: 0;
			overflow: hidden;
			transition: width .4s;
			vertical-align: middle;
		}

		#h-srch-inp {
			background-color: #F2F3F5;
			border: 1px solid #E6E7EB;
			border-width: 1px 1px 1px 0;
			width: 100%;
			height: 50px;
			padding: 0 15px;
			font-family: var(--font-text);
			box-sizing: border-box;
		}

		#h-srch-cbx:not(:checked)~#h-srch-ico-box {
			border-color: transparent !important;
		}

		#h-srch-cbx:checked~#h-srch-ico-box .bgr {
			opacity: 1;
		}

		#h-srch-cbx:checked~#h-srch-inp-box {
			width: 300px;
		}

		#h-srch-cbx:checked~.h-name-box {
			max-width: 0;
			transition: max-width .1s;
		}

		/* Desktop menu: links */
		#h-links .link {
			display: inline-block;
			transition: background-color .3s;
		}

		#h-links .title {
			margin: 0 2rem;
			vertical-align: middle;
			color: var(--text-c);
			font-family: var(--font-heading);
			font-size: .9rem;
			letter-spacing: .1em;
			transition: color .3s, border .3s;
		}

		#h-links .title:hover,
		#h-links .title.active {
			color: var(--text-h-c);
		}

		#h-links .ddown-icon {
			font-size: .8em;
			vertical-align: top;
		}

		/* Languages */
		#h-lang-icon {
			margin-left: 0;
		}

		#h-lang-icon .img {
			border-radius: 50%;
			width: 1.5rem;
			height: 1.5rem;
			cursor: pointer;
		}

		#h-lang-menu {
			opacity: 0;
			padding-top: .7rem;
			right: 1rem;
			transform: translateY(2rem);
			pointer-events: none;
			transition: opacity .4s, transform .4s;
		}

		#h-lang-menu.open {
			opacity: 1;
			transform: none;
			pointer-events: all;
		}

		#h-lang-menu .box {
			background-color: var(--bgr-c);
			border-radius: .5rem;
			box-shadow: 0 10px 2rem rgba(0, 0, 0, .1);
			overflow: hidden;
		}

		#h-lang-menu .language {
			grid-template-columns: 2.5rem auto;
			min-width: 150px;
			padding: 0 1rem;
			letter-spacing: .02em;
			cursor: pointer;
			transition: background-color .3s;
		}

		#h-lang-menu .language:hover,
		#h-lang-menu .language.active {
			background-color: var(--color-primary-400);
		}

		#h-lang-menu .img {
			border-radius: 50%;
			width: 1.4rem;
			height: 1.4rem;
		}

		#h-lang-menu .language .name {
			line-height: 3.5;
			transition: color .3s;
		}

		#h-lang-menu .language:hover .name,
		#h-lang-menu .language.active .name {
			color: #fff;
		}

		/* TO FIX - Desktop menu: dropdown */
		.h-drop {
			background-color: var(--color-primary-400);
			width: 230px;
			max-height: 0;
			position: absolute;
			top: 100%;
			left: calc(50% - 115px);
			box-sizing: border-box;
			overflow: hidden;
			transition: max-height .2s ease-out;
			z-index: 1;
		}

		.h-link:hover .h-drop,
		#nav_prof:hover .h-drop {
			max-height: 300px;
			transition: max-height .4s ease-in;
		}

		.h-drop-link {
			display: block;
			line-height: 1.5;
			text-align: left;
			transition: background .2s, color .2s;
		}

		.h-drop-link a {
			display: block;
			padding: 1em 1em;
		}

		/* TO FIX - Wishlist & Cart */
		#h-cart {
			background-position: center;
			background-size: contain;
			width: 20px;
			height: 20px;
			padding: 0;
			margin: 0 1rem;
		}

		#h-cart-num {
			background: #E00003;
			border-radius: 100%;
			width: 14px;
			position: absolute;
			bottom: .9rem;
			left: .9rem;
			color: #FFF;
			font-size: .8rem;
			line-height: 14px;
			text-align: center;
		}

		#h-wish {
			background-position: center;
			background-size: contain;
			width: 20px;
			height: 20px;
			padding: 0;
			margin: 0 1rem;
		}

		/* TO FIX - Login & profile */
		#h-user-ico {
			display: block;
			background-color: #DDD;
			background-position: center;
			background-size: cover;
			border: 2px solid #8b8b8b;
			width: 40px;
			height: 40px;
			box-sizing: border-box;
			transition: border .1s;
		}

		.h-log-txt {
			margin-left: 2rem;
			display: inline-block;
			transition: color .4s, background .4s, border .4s;
		}

		.h-log-txt.main {
			padding: 0 1.7rem;
			border-width: 1px;
			border-style: solid;
			letter-spacing: .2em;
			line-height: calc(3rem - 2px);
		}

		/* Mobile: icon */
		#h-mob-icon {
			display: inline-block;
			width: 1.7rem;
			height: 3rem;
			vertical-align: middle;
			cursor: pointer;
		}

		#h-mob-icon .line-box {
			width: 100%;
			position: absolute;
			top: calc(50% - 2px);
			left: 0;
			transition: transform .4s;
		}

		#h-mob-icon .line {
			background-color: var(--text-c);
			height: 2px;
			transition: transform .4s;
		}

		#h-mob-icon .line-box:nth-child(1) {
			transform: translateY(-.7rem);
			transition: transform .4s .4s;
		}

		#h-mob-icon .line-box:nth-child(2) {
			transition: opacity .1s .4s, transform .2s .4s;
		}

		#h-mob-icon .line-box:nth-child(3) {
			transform: translateY(.7rem);
			transition: transform .4s .4s;
		}

		input:checked+#h-mob-icon .line-box:nth-child(1),
		input:checked+#h-mob-icon .line-box:nth-child(3) {
			transform: translateY(0);
			transition: transform .4s;
		}

		input:checked+#h-mob-icon .line-box:nth-child(2) {
			opacity: 0;
			transform: translateX(-4rem);
			transition: opacity .1s .1s, transform .2s;
		}

		input:checked+#h-mob-icon .line-box:nth-child(1) .line {
			transform: rotate(45deg);
			transition: transform .4s .4s;
		}

		input:checked+#h-mob-icon .line-box:nth-child(3) .line {
			transform: rotate(-45deg);
			transition: transform .4s .4s;
		}

		/* Mobile: menu */
		#h-mob-menu {
			width: 100%;
			overflow-x: hidden;
			overflow-y: auto;
			left: 0;
			transform: translateX(-100%);
			z-index: 1;
			transition: transform .4s;
		}

		header.open #h-mob-menu {
			transform: none;
		}

		#h-mob-links {
			display: grid;
			height: 100%;
			align-items: center;
			justify-content: center;
			text-align: center;
		}

		#h-mob-links .link {
			opacity: 0;
			transform: translateY(1rem);
			transition: opacity .1s, transform .1s .4s;
		}

		header.open #h-mob-links .link {
			opacity: 1;
			transform: none;
			transition: opacity .6s .3s, transform .6s .3s;
		}

		#h-mob-links .link .title {
			display: inline-block;
			color: var(--text-c);
			font-size: 1.5rem;
			letter-spacing: .1em;
			cursor: pointer;
			transition: opacity .5s, color .5s;
		}

		#h-mob-links .link.focus {
			color: #000;
		}

		#h-mob-links .link.blur {
			opacity: .3;
		}

		/*TO FIX - Cookies*/
		#h-cook {
			background-color: #161616;
			width: 100%;
			padding: 10px 1rem;
			position: fixed;
			bottom: 0;
			left: 0;
			z-index: 10;
			text-align: center;
			box-sizing: border-box;
		}

		#h-cook-txt {
			display: inline-block;
			color: #DDD;

			vertical-align: middle;
		}

		#h-cook-btn {
			background-color: transparent;
			border: 1px solid #DDD;
			padding: 3px .6rem;
			margin: 10px 10px;
			color: #DDD;
			font-size: 11px;
			vertical-align: middle;
			cursor: pointer;
		}

		/* Responsive */
		@media (max-width: 1440px) {

			#h-pan {
				padding-left: 3rem;
				padding-right: 3rem;
			}

		}

		@media (max-width: 1024px) {

			#h-logo {
				height: 2.3rem;
			}

			#h-lang-icon {
				margin-right: 1rem;
			}

		}

		@media (max-width: 480px) {

			#h-content {
				padding-left: 2rem;
				padding-right: 2rem;
			}

			#h-logo {
				height: 2rem;
			}

			header .name-box .name {
				font-size: 1.2rem;
			}

			#h-mob-links .link .title {
				font-size: 1.2rem
			}

		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		$(document).ready(function() {

			//fix height
			fix_height();

			$(window).resize(function() {
				fix_height();
			})

			function fix_height() {

				const height = $('#h-content').outerHeight();

				$('header').css('height', height + 'px');
				$('#h-mob-menu').css('height', 'calc(100vh - ' + height + 'px)');
			}

			//language menu
			$('#h-lang-icon').mouseenter(function() {

				$('#h-lang-menu').addClass('open');

			});

			$('header').mouseleave(function() {

				$('#h-lang-menu').removeClass('open');

			});

		});
	</script>
@endpushOnce

<header
	class="{{ $h_theme['color'] }} {{ $h_theme['floating'] ? 'floating' : '' }} {{ $h_theme['sticky'] ? 'sticky' : '' }}"
>

	<div id="h-content">

		<div class="background bgr"></div>

		<!-- Left bar -->
		<div class="nav-bar txt-lef">

			<!-- Logo -->
			@if ($h_comp['logo'])
				<a href="{{ route('home') }}">
					<img
						src="{{ $h_theme['color'] == 'light' ? Vite::image('logo/logo-light.webp') : Vite::image('logo/logo-dark.webp') }}"
						class="logo"
					>
				</a>
			@endif

			<!-- Name -->
			@if ($h_comp['name-bar'])
				<div class="name-box">
					<a href="{{ route('home') }}">
						<h2 class="name desk1024">{{ $h_name }}</h2>
					</a>
					<a href="{{ route('home') }}">
						<h2 class="name mob1024">{{ $h_name_m }}</h2>
					</a>
				</div>
			@endif

		</div>

		<!-- Middle bar -->
		<div class="nav-bar txt-cen"></div>

		<!-- Right bar -->
		<div class="nav-bar txt-rig">

			@if (!$h_comp['force-mob'])
				<!-- Links -->
				<div
					id="h-links"
					class="desk1024 inlb"
				>
					@foreach ($h_links as $h_link)
						@if (!isset($h_link['mobile']) || !$h_link['mobile'])
							<!-- Link -->
							<div class="link">
								<a href="{{ $h_link['url'] }}">
									<h1 class="title {{ $locationTag == $h_link['loc'] ? 'active' : '' }}">
										{{ $h_link['title'] }}
										{!! isset($h_link['drop']) ? '<span class="ddown-icon">&#x25BC;</span>' : '' !!}
									</h1>
								</a>

								@if (isset($h_link['drop']))
									<!-- Dropdown menu -->
									<div class="ddown-menu bgr-c1">
										@foreach ($h_drop[$h_link['drop']] as $h_drop_link)
											<a href="{{ $h_drop_link[1] }}">
												<p class="ddown-link">{{ $h_drop_link[0] }}</p>
											</a>
										@endforeach
									</div>
								@endif
							</div>
						@endif
					@endforeach
				</div>
			@endif

			<!-- Mobile menu icon -->
			<input
				type="checkbox"
				id="h-mob-open"
				class="hide"
				onclick="$('header').toggleClass('open');"
			>

			<label
				id="h-mob-icon"
				class="{{ $h_comp['force-mob'] ? '' : 'mob1024' }}"
				for="h-mob-open"
			>
				<div class="line-box">
					<div class="line"></div>
				</div>
				<div class="line-box">
					<div class="line"></div>
				</div>
				<div class="line-box">
					<div class="line"></div>
				</div>
			</label>

		</div>

		<!-- Mobile menu -->
		<div
			id="h-mob-menu"
			class="hanging-menu {{ $h_comp['force-mob'] ? '' : 'mob1024' }}"
			onClick="$('#h-mob-open').click();"
		>

			<div class="background bgr"></div>

			<!-- Links -->
			<div id="h-mob-links">
				@foreach ($h_links as $h_link)
					<!-- Link -->
					<label class="link">
						<input
							type="checkbox"
							class="hide"
						>

						<a {!! isset($h_link['drop']) ? '' : 'href="' . $h_link['url'] . '"' !!}>
							<p class="title txt-upp">
								{{ $h_link['title'] }}
								{!! isset($h_link['drop']) ? '<span class="h-dropM-ico">&#x25BC;</span>' : '' !!}
							</p>
						</a>

						@if (isset($h_link['drop']))
							<!-- Dropdown menu -->
							<div class="ddown-menu">
								@foreach ($h_drop[$h_link['drop']] as $h_drop_link)
									<p class="ddown-link txt-wht-h bgr-c3-h">
										<a href="{{ $h_drop_link[1] }}">
											{{ $h_drop_link[0] }}
										</a>
									</p>
								@endforeach
							</div>
						@endif
					</label>
				@endforeach
			</div>
		</div>
	</div>
</header>
