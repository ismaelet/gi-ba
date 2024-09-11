$(document).ready(function() {
	
	$('.filter').click(function() {

		const self = $(this);
		const filter = self.data('filter');

		if (self.hasClass('active')) {

			$('.filter').removeClass('blur').removeClass('active');
			
			$('.image-box').show(400);

		} else {

			$('.filter').addClass('blur').removeClass('active');
			self.removeClass('blur').addClass('active');
			$('.filter-remove').removeClass('hide');

			$('.image-box').each(function() {

				const self = $(this);
				const category = self.data('category');

				if (category == filter) {

					self.show(400);

				} else {

					self.hide(400);
				}
			});

		}

	});

});