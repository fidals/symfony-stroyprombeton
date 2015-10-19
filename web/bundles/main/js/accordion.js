(function($) {
	var $akkordion = $('#akkordion'),
		$allPanels = $akkordion.find('.akkordion-content').hide(),
		$allTitles = $akkordion.find('.akkordion-title'),
		activeItem = "#" + localStorage.getItem("akkordionActiveItem");


	if ( activeItem !== null) {
		akkordionCollapse.call( $(activeItem) );
	}

	$akkordion.find('.akkordion-title').on('click', akkordionCollapse);

	function akkordionCollapse() {
		var $this      = $(this),
			$target    = $this.next(),
			activeItem = $this.attr('id');

		localStorage.setItem("akkordionActiveItem", activeItem);

		if( $this.hasClass('active') ) {
			$this.removeClass('active');
			$target.stop().slideUp();
			localStorage.setItem("akkordionActiveItem", null);
		} else {
			$allTitles.removeClass('active');
			$this.addClass('active');
			$allPanels.stop().slideUp();
			$target.stop().slideDown();
		}

		return false;
	}
})(jQuery);