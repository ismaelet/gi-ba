$(window).on('load', function() {

	if ($('#loading').length){
		setTimeout(function(){
			$('#loading').fadeOut(400);
			update();
		}, 400);
	} else {update();}
	
	var winBot = 0;
	
	$(window).scroll(function() {update();});
	$(window).on('resize', function(){update();});

	function update() {

		winBot = $(window).scrollTop() + $(window).height();
	
		$('.load').each(function() {
			
			var elem = $(this);

			if (visible(elem)) {elem.removeClass('load');}
		})
	}
	
	function visible(elem) {		
		var elemPos = elem.offset().top;
		//var elemPos = elem.offset().top + elem.outerHeight(true);
		return (elemPos <= winBot);
	}
});