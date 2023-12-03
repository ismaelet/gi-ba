$(document).ready(function () {
	// setup
	$('.carousel').each(function () {
		const panel = $(this)

		panel.find('.carousel-grid').addClass('no-trans')

		panel[0].offsetHeight

		panel.find('.carousel-grid').removeClass('no-trans')
	})

	// on click
	$('.carousel-arrow').click(function () {
		if (!$(this).hasClass('disabled')) {
			const arrow = $(this)
			const panel = $(arrow.data('tar'))

			update_position(arrow, panel)
			slide(panel)
		}
	})

	// on click
	$('.carousel-dot').click(function () {
		const self = $(this)
		const panel = $(self.parent().data('target'))

		if (!self.hasClass('disabled')) {
			const span = Math.round(panel.css('--columns'))
			const page = self.index() + 1

			let pos = span * (page - 1) + 1

			panel.data('pos', pos)

			self.parent().find('.carousel-dot').removeClass('active')
			self.addClass('active')

			slide(panel)
		}
	})

	// update position variable
	function update_position(arrow, panel) {
		let pos

		// if is a fixed arrow
		if (arrow.data('pos')) {
			pos = parseInt(arrow.data('pos'))

			// if is a dynamic arrow
		} else {
			const step = panel.css('--step') ? parseInt(panel.css('--step')) : 1
			const span = Math.round(panel.css('--columns'))
			const loop = panel.data('loop') ? true : false
			const rows = parseInt(panel.css('--rows'))
			const count = panel.find('> *').length / rows
			const last = count - span + 1
			const posOld = panel.data('pos')

			let direction

			pos = posOld

			if (arrow.data('direction')) {
				direction = arrow.data('direction')
			} else {
				direction = arrow.hasClass('left') ? 'left' : 'right'
			}

			// update position
			if (direction === 'right') {
				pos += step
				arrow.prev('.left.disabled').removeClass('disabled')
			} else if (direction === 'left') {
				pos -= step
				arrow.next('.right.disabled').removeClass('disabled')
			}

			// disable arrows if the limit is reached
			if (pos <= 1 && !loop) {
				arrow.addClass('disabled')
			}
			if (pos >= last && !loop) {
				arrow.addClass('disabled')
			}

			// clamp position
			if (pos < 1) {
				if (loop && posOld <= 1) {
					pos = last
				} else {
					pos = 1
				}
			}

			if (pos > last) {
				if (loop && posOld >= last) {
					pos = 1
				} else {
					pos = last
				}
			}
		}

		// save the new position
		panel.data('pos', pos)
	}

	// move panel
	function slide(panel) {
		const rows = parseInt(panel.css('--rows'))
		const pos = rows > 1 ? parseInt(panel.data('pos')) * rows - 1 : parseInt(panel.data('pos'))
		const left = panel.find('> *:nth-child(' + pos + ')').position().left + panel.scrollLeft()

		panel.scrollLeft(left)
	}
})
$(document).ready(function () {
	// setup
	$('.carousel').each(function () {
		const panel = $(this)

		panel.find('.carousel-grid').addClass('no-trans')

		panel[0].offsetHeight

		panel.find('.carousel-grid').removeClass('no-trans')
	})

	// on click
	$('.carousel-arrow').click(function () {
		if (!$(this).hasClass('disabled')) {
			const arrow = $(this)
			const panel = $(arrow.data('tar'))

			update_position(arrow, panel)
			slide(panel)
		}
	})

	// on click
	$('.carousel-dot').click(function () {
		const self = $(this)
		const panel = $(self.parent().data('target'))

		if (!self.hasClass('disabled')) {
			const span = Math.round(panel.css('--columns'))
			const page = self.index() + 1

			let pos = span * (page - 1) + 1

			panel.data('pos', pos)

			self.parent().find('.carousel-dot').removeClass('active')
			self.addClass('active')

			slide(panel)
		}
	})

	// update position variable
	function update_position(arrow, panel) {
		let pos

		// if is a fixed arrow
		if (arrow.data('pos')) {
			pos = parseInt(arrow.data('pos'))

			// if is a dynamic arrow
		} else {
			const step = panel.css('--step') ? parseInt(panel.css('--step')) : 1
			const span = Math.round(panel.css('--columns'))
			const loop = panel.data('loop') ? true : false
			const rows = parseInt(panel.css('--rows'))
			const count = panel.find('> *').length / rows
			const last = count - span + 1
			const posOld = panel.data('pos')

			let direction

			pos = posOld

			if (arrow.data('direction')) {
				direction = arrow.data('direction')
			} else {
				direction = arrow.hasClass('left') ? 'left' : 'right'
			}

			// update position
			if (direction === 'right') {
				pos += step
				arrow.prev('.left.disabled').removeClass('disabled')
			} else if (direction === 'left') {
				pos -= step
				arrow.next('.right.disabled').removeClass('disabled')
			}

			// disable arrows if the limit is reached
			if (pos <= 1 && !loop) {
				arrow.addClass('disabled')
			}
			if (pos >= last && !loop) {
				arrow.addClass('disabled')
			}

			// clamp position
			if (pos < 1) {
				if (loop && posOld <= 1) {
					pos = last
				} else {
					pos = 1
				}
			}

			if (pos > last) {
				if (loop && posOld >= last) {
					pos = 1
				} else {
					pos = last
				}
			}
		}

		// save the new position
		panel.data('pos', pos)
	}

	// move panel
	function slide(panel) {
		const rows = parseInt(panel.css('--rows'))
		const padding = panel.css('--padding') ? parseInt(panel.css('--padding')) : 0
		const pos = rows > 1 ? parseInt(panel.data('pos')) * rows - 1 : parseInt(panel.data('pos'))
		const left = panel.find('> *:nth-child(' + pos + ')').position().left + panel.scrollLeft() - padding * 16

		panel.scrollLeft(left)
	}
})
