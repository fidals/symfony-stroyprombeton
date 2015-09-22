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
	var $basketWrapper        = $('#sticky-wrapper'),
		$basketItemsContainer = $('.mbasket-items');

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

	$basketWrapper.on('click', '.shk-del', function () {
		setTimeout( darkTooltipInit, 1000);
	});

	$('.add-basket')
		.darkTooltip({
			trigger   : 'click',
			opacity   : 0,
			gravity   : 'south',
			theme     : 'light'
		})
		.on('click', function () {
			var currentTooltip = $(this).attr('id');


			$('#darktooltip-' + currentTooltip )
				.css({
					'display' : 'block'
				})
				.stop().animate({
					'opacity' : 0.9
				}, 500)
			;

			setTimeout( function() {
				$('#darktooltip-' + currentTooltip )
					.stop().animate({
						'opacity' : 0
					}, 500, function() {
						$(this).css({
							'display' : 'none'
						});
					});
			}, 2000);

			setTimeout( darkTooltipInit, 1000);
		});
});