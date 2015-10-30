// Scripts - Client

(function($) {

	if( $('body').find('.overlay_it_container') ) {

		$('.overlay_it_container').fadeIn(500);

		$('.overlay_it_box .close').click(function() {
			$('.overlay_it_container').hide();
		});

		$('.overlay_it_container .overlay_it_box').click(function(e) {
			e.stopPropagation();
		});

		$('.overlay_it_container').click(function() {
			$('.overlay_it_container').hide();
		});

	}

})(jQuery);