// Plugins

(function($) {
	$.fn.overlay_it = function( options ) {
		var settings = $.extend({
			html: '<h1>Testing</h1>',
			close_html: '<span>Close</span>'
		}, options );
		$(this).each(function() {
			if( !$('.overlay_it_container').length ) {
				$(this).prepend('<div class="overlay_it_container"><div class="overlay_it_box">'+settings.html+'<div class="close">'+settings.close_html+'</div></div></div>');
			}
			$('.overlay_it_box .close').click(function() {
				$('.overlay_it_container').hide();
			});
			$('.overlay_it_container .overlay_it_box').click(function(e) {
				e.stopPropagation();
			});
			$('.overlay_it_container').click(function() {
				$('.overlay_it_container').hide();
			});
		});
		return this;
	}; //  END OVERLAY_IT FUNCTION
}(jQuery));