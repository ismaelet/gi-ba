$(document).ready(function() {

	update();

	$(window).resize(function() {
		update()
	});

	function update() {
		$('.slide-pan').each(function() {
			var panel = $(this);
			var block = panel.find('.slide-block').outerWidth();
			var box = panel.find('.slide-box').outerWidth();

			var size = Math.round(block / box);

			panel.data('size', size);

			panel.find('.slide-row').addClass('no-trans');

			slide(panel);

			panel[0].offsetHeight;

			panel.find('.slide-row').removeClass('no-trans');
		});
	}

	//move panel
	function slide (panel) {		
		//get variables
		var pos = parseInt(panel.data('pos'));
		var size = parseInt(panel.data('size'));
		
		//move the row
		var left = -100 * (pos - 1) / size;
		
		panel.find('.slide-row').css({'left': left + '%'});
	}
})