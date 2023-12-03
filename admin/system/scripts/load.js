$(window).on('load', function() {

	load()
		
	$(window).scroll(function() { load() })
	$(window).on('resize', function() { load() })

	function load() {

		const winBot = $(window).scrollTop() + $(window).height()
	
		$('.load').each(function() {
			
			let elem = $(this)

			if (visible(elem, winBot)) {

				elem.removeClass('load')

			}

		})
	
		$('.lazy-loading').each(function() {
			
			let elem = $(this)

			if (visible(elem, winBot)) {

				elem.attr('src', elem.data('lazy-src'))

			}

		})

	}
	
	function visible(elem, winBot) {

		const elemPos = elem.offset().top
		//const elemPos = elem.offset().top + elem.outerHeight(true)
		
		return (elemPos <= winBot)

	}

})