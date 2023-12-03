$(document).ready(function() {

	//open
	$('.lbox-open').click(function() {

		const tar = $($(this).data('tar'));

		if ($('.lbox:visible').length) {

			$('.lbox').hide();

			tar.show();

		} else {

			$('body').addClass('fixed');

			tar.fadeIn();
		}

	});

	//close
	$('.lbox-close').click(function() {close($(this))});
	$('.lbox-bgr').click(function() {close($(this))});
	$('.lbox-close-icon').click(function() {close($(this))});

	function close(ele) {

		ele.parents('.lbox').fadeOut();

		$('body').removeClass('fixed');

	}
});