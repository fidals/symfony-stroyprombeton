var Cart = {
	url: {
		add: '/gbi/cart/add/',
		remove: '/gbi/cart/remove/',
		clean: '/gbi/cart/clean/'
	},
	part: {
		container: '.menu-basket'
	},
	view: {
		update: function (html) {
			$(Cart.part.container).html(html);
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
		)
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
		)
	},
	clean: function () {
		$.post(
			Cart.url.clean,
			{},
			Cart.view.update,
			'html'
		)
	}
}