$(document).ready(function() {

	$('.svg').each(function() {
	
		var img = $(this);
		var imgURL = img.attr('src');
		var attributes = img.prop('attributes');

		$.get(imgURL, function(data) {

			// Get the SVG tag, ignore the rest
			var svg = jQuery(data).find('svg');

			// Loop through IMG attributes and apply on SVG
			$.each(attributes, function() {
				svg.attr(this.name, this.value);
			});

			// Replace IMG with SVG
			img.replaceWith(svg);
		}, 'xml');
	});
});