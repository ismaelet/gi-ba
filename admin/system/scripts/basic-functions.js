/*
 * Constants
 */
const changeEvent = new Event('change')

/*
 * Functions
 */
function redirect(url) {
	const root = document.querySelector('html').getAttribute('data-client-root')

	window.location.href = root + url
}

function client_path(baseUrl) {
	const clientRoot = document.querySelector('html').getAttribute('data-client-root')
	const url = clientRoot + baseUrl

	return url
}

/*
COVERT
$(window).ready(function () {
	//theme management
	if ($('html').data('theme-fluid') && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
		if (document.cookie.indexOf('theme-color') == -1) {
			switch_theme('dark')
		}
	}

	$('.theme-switch .icon-box').click(function () {
		const theme = $(this).data('theme')

		switch_theme(theme)
	})

	function switch_theme(theme) {
		let expiration = new Date()

		$('html').addClass('theme-transition')

		$('html').attr('data-theme', theme)

		setTimeout(function () {
			$('html').removeClass('theme-transition')
		}, 500)

		expiration.setMonth(expiration.getMonth() + 1)

		document.cookie = 'theme-color=' + theme + '; expires= ' + expiration + '; path=/'
	}

	// cookies warning
	$('.js-accept-cookies-warning').click(function () {
		let expiration = new Date()

		expiration.setMonth(expiration.getMonth() + 1)
		document.cookie = 'cookies-warning=1; expires= ' + expiration + '; path=/'

		$('.cookies-warning').hide()
	})
})
*/
