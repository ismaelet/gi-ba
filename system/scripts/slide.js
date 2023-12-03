$(document).ready(function() {

	//document ready
	$('.slide-pan').each(function() {
		var panel = $(this);
		panel.find('.slide-row').addClass('no-trans');

		slide(panel);

		panel[0].offsetHeight;

		panel.find('.slide-row').removeClass('no-trans');
	});
	
	//on click
	$('.slide-arrow').click(function() {
		if (!$(this).hasClass('disabled')) {
			var arrow = $(this);
			var panel = $(arrow.data('tar'));

			update_pos(arrow, panel);
			slide(panel);
		}
	});

	//update position variable
	function update_pos (arrow, panel) {
		//get data
		var qty = Math.round(parseInt(panel.find('.slide-row').css('width')) / parseInt(panel.find('.slide-box').css('width')));
		var size = Math.round(parseInt(panel.find('.slide-block').css('width')) / parseInt(panel.find('.slide-box').css('width')));
		var loop = panel.data('loop');
		var leap = panel.data('leap');

		if (!leap) {leap = size;}
		
		//if is a fixed arrow
		if (arrow.data('pos')) {
			var pos = parseInt(arrow.data('pos'));

		//if is a dynamic arrow
		} else if (arrow.data('dir')) {
			var pos = parseInt(panel.data('pos'));
			var dir = arrow.data('dir');
			var posOld = pos;

			//get pos
			if (dir === 'right') {
				pos = Math.floor(pos + leap);
				arrow.prev('.left.disabled').removeClass('disabled');
			} else if (dir === 'left') {
				pos = Math.ceil(pos - leap);
				arrow.next('.right.disabled').removeClass('disabled');
			}

			//enable & disable arrows
			if (pos <= 1 && dir === 'left') {
				if (loop && posOld === 1) {pos = qty - size + 1;}
				else {pos = 1;}

				if (!loop) {arrow.addClass('disabled');}
			}
		
			if ((pos >= qty - (size - 1)) && dir === 'right') {
				if (loop && posOld === qty - size + 1) {pos = 1;}
				else {pos = qty - size + 1;}

				if (!loop) arrow.addClass('disabled');
			}
		}

		//save new pos
		panel.data('pos', pos);
	}

	//move panel
	function slide (panel) {		
		//get variables
		var pos = parseInt(panel.data('pos'));
		var size = Math.round(parseInt(panel.find('.slide-block').css('width')) / parseInt(panel.find('.slide-box').css('width')));
		
		//move the row
		var left = -100 * (pos - 1) / size;
		
		panel.find('.slide-row').css({'left': left + '%'});
	}
});