var Cart = {
	url: {
		add: '/gbi/cart/add/',
		remove: '/gbi/cart/remove/',
		clean: '/gbi/cart/clean/'
	},
	view: {
		container: '.menu-basket',

		update: function (html) {
			$(Cart.view.container).html(html);
		}
	},
	add: function (id, quantity) {
		$.post(
			Cart.url.add,
			{
				id: id,
				quantity: quantity
			},
			Cart.view.update,
			'html'
		);
		return false;
	},
	remove: function (id, quantity) {
		$.post(
			Cart.url.remove,
			{
				id: id,
				quantity: quantity
			},
			Cart.view.update,
			'html'
		);
		return false;
	},
	clean: function () {
		$.post(
			Cart.url.clean,
			{},
			Cart.view.update,
			'html'
		);
		return false;
	}
};

$(function () {
	/*
	 * Тултипы
	 */
	var $basketItemsContainer = $('.mbasket-items');

	function darkTooltipInit() {
		$('#butEmptyCart')
			.darkTooltip({
				trigger   : 'click',
				animation : 'fadeIn',
				gravity   : 'south',
				confirm   : true,
				yes       : 'Да',
				no        : 'Нет',
				theme     : 'light',
				onYes : function() {
					Cart.clean();
				},
				onNo  : function() {
					$basketItemsContainer.removeClass('active');
				},
				onClose: function() {
					$basketItemsContainer.removeClass("active");
				}
			})
			.on('click', function () {
				$(this).closest('.mbasket-items').addClass('active');
			});
	}

	darkTooltipInit();

	$('.add-basket')
		.darkTooltip({
			trigger   : 'click',
			animation : 'fadeIn',
			gravity   : 'south',
			theme     : 'light'
		})
		.on('click', function () {
			setTimeout( darkTooltipInit, 1000);
			setTimeout( function() {
				$('.dark-tooltip').fadeOut();
			}, 500);
		});
});