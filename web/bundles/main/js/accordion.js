(function($) {
	var allPanels = $('.akkordion > .akkordion-content').hide(),
		allTitles = $('.akkordion > .akkordion-title');

	$('.akkordion > .akkordion-title').on('click', function() {
		$this = $(this);
		$target = $this.next();

		if( !$this.hasClass('active') ) {
			allTitles.removeClass('active');
			$this.addClass('active');
			allPanels.slideUp();
			$target.slideDown();
		}

		return false;
	});
})(jQuery);