$(window).ready(function() {

	const count = $('#gallery').data('count');
	var pos = 0;

	$('.image').click(function() { pos = $(this).parents('.image-box').data('pos'); });

	$('.arrow').click(function() {

		const self = $(this);

		if (self.hasClass('right')) { pos++; }
		else { pos--; }

		$('#image' + pos).click();

	});

	document.onkeydown = function(e) {


		if ($('.lbox:visible').length) {

			if (e.which == 37) {

				pos--;
				
				if (pos < 1) { pos = 1; }

				$('#image' + pos).click();

			} else if (e.which == 39) {

				pos++;

				if (pos > count) { pos = count; }

				$('#image' + pos).click();

			}

		}

	};

});