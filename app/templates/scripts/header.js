$(document).ready(function() {
	
	//fix height
	fix_height();

	$(window).resize(function() {fix_height();})
	
	function fix_height() {

		const height = $('#h-content').outerHeight();

		$('header').css('height', height + 'px');
		$('#h-mob-menu').css('height', 'calc(100vh - ' + height + 'px)');
	}

	//language menu
	$('#h-lang-icon').mouseenter(function() {

		$('#h-lang-menu').addClass('open');

	});

	$('header').mouseleave(function() {

		$('#h-lang-menu').removeClass('open');

	});

});