$(document).ready(function() {

	$('.file-inp').on('change', function() {

		$(this).parents('.file-form').find('.file-submit').click();

		console.log(1);
	});

	$('.file-form').submit(function() {

		var self = $(this);

		self.find('.file-cancel').hide();
		self.find('.file-submit').hide();
		self.find('.file-load').show();
		self.find('.file-stick').show().css('opacity', 1);

	});
});