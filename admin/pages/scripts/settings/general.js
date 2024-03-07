$(document).ready(function() {

	$('.sett-slid').each(function() {
		var dot = $(this).find('.sett-slid-focus');

		var left = $(this).find('.sett-slid-dot.active').position().left;

		dot.css('left', left + 'px').show();
	});

	$('.sett-slid-dot').click(function() {
		var box = $(this).parents('.sett-slid');
		var dot = box.find('.sett-slid-focus');

		dot.removeClass('no-trans');

		var left = $(this).position().left;

		dot.css('left', left + 'px');

		box.find('input').prop('checked', false);
		$(this).find('input').prop('checked', true);

		setTimeout(function() {
			box.find('button').click();
		}, 400);
	});
});