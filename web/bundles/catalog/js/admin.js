var CatalogAdmin = {

	parts: {
		categoryEditor: '#category-editor',
		productEditor: '#product-editor',
		categoryLists: '.sortable-category',
		uncatProducts: '#uncat-products-container'
	},

	urls: {
		categoryChild: '/gbi/admin/categoryChilds',
		categoryParent: '/gbi/admin/categoryParent',
		categoryProducts: '/gbi/admin/categoryProducts',
		getCategory: '/gbi/admin/getCategory',
		saveCategory: '/gbi/admin/saveCategory',
		deleteCategory: '/gbi/admin/deleteCategory',
		getBlankCategory: '/gbi/admin/getBlankCategory',

		getProduct: '/gbi/admin/getProduct',
		saveProduct: '/gbi/admin/saveProduct',
		deleteProduct: '/gbi/admin/deleteProduct',
		getBlankProduct: '/gbi/admin/getBlankProduct',
		searchUncatProduct: '/gbi/admin/searchUncatProduct',
		productCategory: '/gbi/admin/productCategory'
	},

	initDragAndDrop: function () {
		$("ul.sortable-category").sortable({
			connectWith: "ul.sortable-category",
			delay: 100,
			revert: true,
			scroll: true,
			forcePlaceholderSize: true,
			helper: 'clone',
			stop: function (event, ui) {
				parentId = ui.item.parent().attr('category-id');
				categoryId = ui.item.attr('category-id');
				CatalogAdmin.setCatagoryParent(parentId, categoryId);
			},
			beforeStop: function (event, ui) {
				//return confirm('Вы действительно хотите переместить категорию?');
			}
		});

		$("ul.sortable-product").sortable({
			connectWith: "ul.sortable-product",
			delay: 100,
			revert: true,
			scroll: true,
			forcePlaceholderSize: true,
			helper: 'clone',
			stop: function (event, ui) {
				categoryId = ui.item.parent().attr('category-id');
				productId = ui.item.attr('product-id');
				console.log('prodId: ' + productId, ' catId: ' + categoryId);
				CatalogAdmin.setProductCategory(productId, categoryId);
			},
			beforeStop: function (event, ui) {
				//return confirm('Вы действительно хотите переместить категорию?');
			}
		});

		$("#uncat-products").draggable({handle: "p", cursor: "move"});
	},

	view: {
		notify: {
			success: function (text) {
				$('#success').stop().html(text)
					.fadeIn(200)
					.delay(1000)
					.fadeOut(200);
			},
			error: function (text) {
				$('#error').stop().html(text)
					.fadeIn(200)
					.delay(2000)
					.fadeOut(200);
			},
			info: function (text) {
				$('#info').stop().html(text)
					.fadeIn(200)
					.delay(2000)
					.fadeOut(200);
			}
		},

		showCategoryChilds: function ($el, childsHtml) {
			$elParent = $el.parent();
			console.log($elParent);
			$elParent.find('li').removeClass('active');
			$el.addClass('active');

			$elParent.nextAll('ul').remove();
			$elParent.after(childsHtml);
			$elParent.next('ul').hide().fadeIn(200);
			$(CatalogAdmin.initDragAndDrop());
		},

		addCategoryToEditor: function (categoryRow) {
			$(CatalogAdmin.parts.categoryEditor).append(categoryRow);

			// scroll
			var $div = $('#category-editor-container');
			var $ul = $('#category-editor');
			var width = $div.width();
			var ulWidth = $ul.width() - width;

			$div
				.on('mouseenter', function (e) {
					// get left offset of div on page
					var divLeft = $div.offset().left;

					$(window).on('mousemove', function (e) {
						var left = e.pageX - divLeft;
						// get percent of width the mouse position is at
						var percent = left / width;

						// set margin-left on ul to achieve a 'scroll' effect
						$ul.css('margin-left', -(percent * ulWidth));
					});
				})
				.on('mouseleave', function () {
					// remove mousemove event
					$(window).off('mousemove');
				})
			;

			tinyMCE.init({
				mode: "textareas",
				editor_selector: "category_description",
				menubar: false
			});

		},

		removeCategoryFromEditor: function (categoryRow) {
			categoryRow.remove();
		},

		removeCategoryFromList: function (categoryId) {
			categoryListItem = $(CatalogAdmin.parts.categoryLists).find('[category-id=' + categoryId + ']');
			if (categoryListItem.length != 0) {
				categoryListItem.remove();
			}
		},

		addProductToEditor: function (productRow) {
			$(CatalogAdmin.parts.productEditor).append(productRow);

			// scroll
			var $div = $('#product-editor-container');
			var $ul = $('#product-editor');
			var width = $div.width();
			var ulWidth = $ul.width() - width;

			$div
				.on('mouseenter', function (e) {
					// get left offset of div on page
					var divLeft = $div.offset().left;

					$(window).on('mousemove', function (e) {
						var left = e.pageX - divLeft;
						// get percent of width the mouse position is at
						var percent = left / width;

						// set margin-left on ul to achieve a 'scroll' effect
						$ul.css('margin-left', -(percent * ulWidth));
					});
				})
				.on('mouseleave', function () {
					// remove mousemove event
					$(window).off('mousemove');
				})
			;

			tinymce.init({
				mode: "textareas",
				editor_selector: "product_description",
				menubar: false
			});
		},

		removeProductFromEditor: function (productRow) {
			productRow.remove();
		},

		addProductsToUncat: function (html) {
			$(CatalogAdmin.parts.uncatProducts).html(html);
		}
	},

	loadCategoryChilds: function (el, categoryId) {
		$el = $(el);
		$.get(CatalogAdmin.urls.categoryChild, {
				categoryId: categoryId
			}, function (json) {
				if (json.success) {
					CatalogAdmin.view.showCategoryChilds($el, json.html);
				} else {
					CatalogAdmin.loadCategoryProducts(el, categoryId);
				}
			}, 'json'
		)
	},

	loadCategoryProducts: function (el, categoryId) {
		$el = $(el);
		$.get(CatalogAdmin.urls.categoryProducts, {
				categoryId: categoryId
			}, function (json) {
				if (json.success) {
					CatalogAdmin.view.showCategoryChilds($el, json.html);
				} else {
					CatalogAdmin.view.notify.error('Ошибка при загрузке продуктов.');
				}
			}, 'json'
		)
	},

	loadCategory: function (categoryId) {
		$.get(
			CatalogAdmin.urls.getCategory,
			{
				categoryId: categoryId
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.addCategoryToEditor(json.html);
					CatalogAdmin.view.notify.info('Категория добавлена в редактор категорий');
				} else {
					CatalogAdmin.view.notify.error('Категория не найдена');
				}
			}
		)
	},

	saveCategory: function (formEl) {
		tinyMCE.triggerSave();
		$.post(
			CatalogAdmin.urls.saveCategory,
			$(formEl).serialize(),
			function (json) {
				if (json.success) {
					CatalogAdmin.view.notify.success('Категория сохранена');
				} else {
					CatalogAdmin.view.notify.error('Возникла ошибка. Категория не сохранена.')
				}
			}, 'json'
		);
	},

	saveProduct: function (formEl) {
		$.post(
			CatalogAdmin.urls.saveProduct,
			$(formEl).serialize(),
			function (json) {
				if (json.success) {
					CatalogAdmin.view.notify.success('Продукт сохранен');
				} else {
					CatalogAdmin.view.notify.error('Возникла ошибка. Продукт не сохранен.')
				}
			}, 'json'
		);
	},

	deleteCategory: function (categoryId, categoryRow) {
		if (confirm('Вы действительно хотите удалить категорию?')) {
			$.post(
				CatalogAdmin.urls.deleteCategory,
				{
					categoryId: categoryId
				},
				function (json) {
					if (json.success) {
						CatalogAdmin.view.removeCategoryFromEditor(categoryRow);
						CatalogAdmin.view.removeCategoryFromList(categoryId);
						CatalogAdmin.view.notify.success('Категория удалена');
					} else {
						CatalogAdmin.view.notify.error('Возникла ошибка. Категория не удалена. Скорее всего такой категории не существует');
					}
				}, 'json'
			);
		}
	},

	deleteProduct: function (productId, productRow) {
		if (confirm('Вы действительно хотите удалить продукт?')) {
			$.post(
				CatalogAdmin.urls.deleteProduct,
				{
					productId: productId
				},
				function (json) {
					if (json.success) {
						CatalogAdmin.view.removeProductFromEditor(productRow);
						CatalogAdmin.view.notify.success('Продукт удален');
					} else {
						CatalogAdmin.view.notify.error('Возникла ошибка. Продукт не удален.');
					}
				}, 'json'
			);
		}
	},

	setCatagoryParent: function (parentId, childId) {
		$.post(
			CatalogAdmin.urls.categoryParent,
			{
				parentId: parentId,
				childId: childId
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.notify.success('Категория успешно прикреплена');
				} else {
					CatalogAdmin.view.notify.error('Не удалось прикрепить категорию');
				}
			}, 'json'
		)
	},

	getBlankCategory: function (parentCategoryId) {
		$.post(
			CatalogAdmin.urls.getBlankCategory,
			{
				parentCategoryId: parentCategoryId
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.addCategoryToEditor(json.html);
				} else {
					CatalogAdmin.view.notify.error('Возникла ошибка при создании категории');
				}
			}, 'json'
		)
	},

	getBlankProduct: function (parentCategoryId) {
		$.post(
			CatalogAdmin.urls.getBlankProduct,
			{
				parentCategoryId: parentCategoryId
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.addProductToEditor(json.html);
				} else {
					CatalogAdmin.view.notify.error('Возникла ошибка при создании категории');
				}
			}, 'json'
		)
	},

	loadProduct: function (productId) {
		$.get(
			CatalogAdmin.urls.getProduct,
			{
				productId: productId
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.addProductToEditor(json.html);
					CatalogAdmin.view.notify.info('Продукт добавлен в редактор продуктов');
				} else {
					CatalogAdmin.view.notify.error('Продукт не найден');
				}
			}
		)
	},

	searchUncatProduct: function (condition) {
		$.get(
			CatalogAdmin.urls.searchUncatProduct,
			{
				condition: condition
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.addProductsToUncat(json.html);
					CatalogAdmin.initDragAndDrop();
					CatalogAdmin.view.notify.info('Найдено ' + $(json.html).find('li').size() + ' продуктов');
				} else {
					CatalogAdmin.view.notify.error('Поиск не дал результатов');
				}
			}
		)
	},

	setProductCategory: function (productId, categoryId) {
		$.post(
			CatalogAdmin.urls.productCategory,
			{
				productId: productId,
				categoryId: (categoryId === undefined) ? null : categoryId
			},
			function (json) {
				if (json.success) {
					CatalogAdmin.view.notify.success('Продукт успешно прикреплен');
				} else {
					CatalogAdmin.view.notify.error('Не удалось прикрепить продукт');
				}
			}, 'json'
		)
	}
}