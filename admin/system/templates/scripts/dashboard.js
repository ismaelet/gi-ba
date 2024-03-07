$(document).ready(function () {
	//open menu category
	$('.dashboard-navigation .title-box').click(function () {
		const category = $(this).parent()

		if (category.hasClass('active')) {
			category.removeClass('active')
		} else {
			$('.dashboard-navigation').addClass('open')
			$('.dashboard-navigation .category').removeClass('active')
			category.addClass('active')
		}
	})

	$('#open-menu').click(function () {
		$('.dashboard-navigation').toggleClass('open')
	})

	$('#body').click(function () {
		if ($(window).width() <= 1280) {
			$('.dashboard-navigation').removeClass('open')
			$('.dashboard-navigation .category').removeClass('active')
		}
	})

	$('.dashboard-navigation').hover(
		function () {
			if ($(window).width() <= 1280 && $(window).width() >= 1000) {
				$('.dashboard-navigation').addClass('open')
			}
		},
		function () {
			if ($(window).width() <= 1280 && $(window).width() >= 1000) {
				$('.dashboard-navigation').removeClass('open')
			}
		}
	)
})
