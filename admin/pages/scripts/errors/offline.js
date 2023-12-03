$(window).ready(function() {

	//main height
	resize();

	$(window).on('resize', function() {resize();});

	function resize() {

		const height = $(window).height();

		$('main').css('height', height + 'px');
	
	}

});