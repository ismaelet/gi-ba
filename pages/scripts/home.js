$(window).ready(function() {

	//enable smooth scrolling
	$('html').css('scroll-behavior', 'smooth');

	/* Slider height */
	fix_height();

	$(window).resize(function() { fix_height(); });

	function fix_height() {

		const width = $(window).width();
		const height = $(window).height();
		const header = $('header').height();

		if (width > height) { $('#slider').css('height', 'calc(' + (height - header) + 'px'); }
	}

	//loading
	setTimeout(function() { $('#load-logo').addClass('active');}, 200);

});

//loading
$(window).on('load', function() {

	const time = $('#load-time').data('time');

	setTimeout(function(){
		$('#load-pan').fadeOut(400);
	}, time);

});