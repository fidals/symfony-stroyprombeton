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
}