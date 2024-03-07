$(document).ready(function () {
	show_cbx()

	$('input[name="reusable"]').on('change', function () {
		show_cbx()
	})

	function show_cbx() {
		const self = $('input[name="reusable"]')
		const sliblingCbx = self.parents('.cbx').next()
		const siblingInput = sliblingCbx.find('input')

		if (self.prop('checked')) {
			sliblingCbx.removeClass('hidden')
		} else {
			sliblingCbx.addClass('hidden')
			siblingInput.prop('checked', false)
		}
	}
})
