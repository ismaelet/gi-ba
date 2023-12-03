$(document).ready(function () {
	//clear error messages
	$('.clear-error').keypress(function () {
		$('.inp.err').hide()
	})
	$('.clear-error').click(function () {
		$('.inp.err').hide()
	})

	//No spance inputs
	$('.inp-nospace').keypress(function (e) {
		if (e.which === 32) return false
	})

	//dependent selector
	$('.sel.affect').each(function () {
		selector_affect($(this))
	})
	$('.sel.affect').on('change', function () {
		selector_affect($(this))
	})

	function selector_affect(selector) {
		const affected = selector.parent().find('.sel[name="' + selector.data('affect') + '"]')
		const field_id = affected.data('id')
		const field_show = affected.data('show')

		affected.attr('disabled', false).empty()

		$.ajax({
			type: 'POST',

			url: affected.data('root') + '/system/modules/ajax/search-selector.php',

			data: { tbl: affected.data('tbl'), filter: affected.data('filter'), filter_val: selector.val(), field_id: field_id, field_show: field_show },

			success: function (data) {
				const options = $.parseJSON(data)

				for (var i = 0; i < options.length; i++) {
					const option = options[i]

					affected.append('<option value="' + option[field_id] + '" selected>' + option[field_show] + '</option>')
				}
			},
		})
	}

	//ajax search
	$('.ajax-search-txt').keyup(function () {
		//setup
		const self = $(this)
		const parent = self.parents('.ajax-search')
		const url = parent.data('url')
		const filter = parent.data('filter')
		const root = parent.data('root')

		$.ajax({
			type: 'POST',

			url: root + '/app/modules/ajax/search-' + url + '.php',

			data: { search: self.val(), filter: filter },

			success: function (data) {
				if ($.trim(data).length) {
					parent.find('.ajax-search-ddown').show()
					parent.find('.ajax-search-box').html(data)
				} else {
					parent.find('.ajax-search-ddown').hide()
					parent.find('.ajax-search-box').html('')
				}
			},
		})
	})

	$('.ajax-search').on('click', '.ajax-search-item', function () {
		var self = $(this)
		var parent = self.parents('.ajax-search')

		parent.find('.ajax-search-id').val(self.data('id'))
		parent.find('.ajax-search-txt').val(self.data('txt'))

		parent.find('.ajax-search-ddown').hide()
	})

	$('.inp, .mbtn').focus(function () {
		$('.ajax-search').find('.ajax-search-ddown').hide()
		$('.ajax-search').find('.ajax-search-box').html('')
	})
})
