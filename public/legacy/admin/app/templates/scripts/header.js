$(document).ready(function () {
	// minimum main minimum height
	set_min_height()

	$(window).on('resize', function () {
		set_min_height()
	})

	function set_min_height() {
		const header = $('header')
		const windowHeight = $(window).height()

		let headerHeight = header.outerHeight()

		if (header.css('position') == 'fixed' || header.css('position') == 'absolute') {
			headerHeight = 0
		}

		$('main').css('min-height', windowHeight - headerHeight + 'px')
	}

	// fix height
	fix_height()

	$(window).resize(function () {
		fix_height()
	})

	function fix_height() {
		const headerHeight = $('.header-top').outerHeight() + $('.header-bottom').outerHeight()
		const windowHeight = $(window).height()

		$('#h-mob-menu').css('height', windowHeight - headerHeight + 'px')
	}

	// search
	$('.h-search').click(function () {
		$('.h-search-inp').focus()
	})

	$('.h-search-icon').click(function () {
		$('.h-search').submit()
	})

	$('main, footer').click(function () {
		$('.h-search').removeClass('active')
	})

	// mobile menu
	$('[data-function="mobile-menu-toggle"]').click(function () {
		$('header').toggleClass('h-mobile-open')
	})

	$('[data-function="mobile-menu-close"]').click(function () {
		$('header').removeClass('h-mobile-open')
	})

	// language menu
	$('.h-language-icon_active').mouseenter(function () {
		$('.h-languages-ddown').addClass('open')
	})

	$('header').mouseleave(function () {
		$('.h-languages-ddown').removeClass('open')
	})

	// megamenus
	$('.h-link [data-function="open-megamenu"]').mouseenter(function () {
		const megamenu = $(this).data('target')

		$('.megamenu').removeClass('visible')
		$('#megamenu-' + megamenu).addClass('visible')
	})

	$('header').mouseleave(function () {
		$('.megamenu').removeClass('visible')
	})
})
