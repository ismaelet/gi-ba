$(document).ready(function () {
	$('input[name="tracking"]').keyup(function () {
		$(this).next().removeClass('hidden')
	})
})
