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
};

/* JS for new Admin Page */
$(function () {
	var $linkToGo   = $('#link-to-go'),
		$treeTarget = $('#js-tree'),
		$sideBar    = $('.sidebar');

	$treeTarget
		.jstree({
			'core' : {
				'data' : {
					'url' : function (node) {
						return node.id === '#' ?
							'/buildCategoryTree/' :
							'/getTreeProducts/';
					},
					'dataType' : 'json',
					'data' : function (node) {
						return { 'id' : node.id };
					}
				},
				'check_callback' : true
			},
			'contextmenu' : {
				'items' : jsTreeContextMenu
			},
			'plugins' : [ 'contextmenu', 'state' ]
		});

	var bindEditAction = function() {
		$('.jstree-anchor').bind('mousedown', function (e) {
			if (e.which == 1) { // если клик левой кнопкой мыши
				var anchor     = $(e.currentTarget),
					entityId   = anchor.data('id'),
					entityType = anchor.parent().hasClass('jstree-leaf') ? 'product' : 'category',
					pathname   = '/adminka/app/main/' + entityType + '/' + entityId + '/edit';

				if (pathname != window.location.pathname) {
					window.location.href = pathname;
				}
			}
		});

		$treeTarget.on('after_open.jstree', bindEditAction);
	};

	$treeTarget.on('state_ready.jstree', bindEditAction);

	function jsTreeContextMenu( node ) {
		var $tree = $treeTarget.jstree(true);

		return {
			tableGear : {
				'separator_before' : false,
				'separator_after'  : false,
				'label'  : 'TableGear',
				'icon'   : 'fa fa-columns',
				'action' : function (obj) {
					var contextAnchor = obj.reference[0];
					var categoryId;
					if (!$(contextAnchor).parent().hasClass('jstree-leaf')) {
						categoryId = $(contextAnchor).data('id');
					} else {
						categoryId = $(contextAnchor).parents('.jstree-node:not(.jstree-leaf)').eq(0).find('.jstree-anchor').eq(0).data('id');
					}
					var url = '/adminka/editproducts';
					var form = $('<form action="' + url + '" method="post">' +
						'<input type="text" name="type_search" value="0">' +
						'<input type="text" name="field_search" value="tv_section_id">' +
						'<input type="text" name="query" value="' + categoryId + '">' +
						'</form>');
					$('body').append(form);
					form.submit();
				},
				_disabled: function(obj) {
					var ref = $(obj.reference);
					if (ref.parent().hasClass('jstree-leaf')
						|| ref.parent().find('ul').first().find('li').first().hasClass('jstree-leaf')
					) {
						return false;
					}
					return true;
				}
			},
			toItemsPage : {
				'separator_before' : false,
				'separator_after'  : false,
				'label'  : 'На страницу',
				'icon'   : 'fa fa-link',
				'action' : function (obj) {
					var contextAnchor = obj.reference[0],
						productId     = $(contextAnchor).data('id');

					if ( $(contextAnchor).parent().hasClass('jstree-leaf') ) {
						$linkToGo.attr('href', '/gbi/products/'   + productId + '/');
					} else {
						$linkToGo.attr('href', '/gbi/categories/' + productId + '/');
					}

					$linkToGo[0].click();
				}
			}
		};
	}

	/**
	 * Инициализируем плагин WYSIWYG редактора на Bootstrap.
	 */
	$('.js-summernote-edit').on('click', function() {
		if ($(this).attr('id') === 'summernote-close') {
			$(this).attr('id', 'summernote-edit');

			$('#summernote').summernote('destroy');
		} else {
			$(this).attr('id', 'summernote-close');

			$('#summernote').summernote({
				minHeight   : 249,
				lang        : 'ru-RU',
				focus       : true,
				placeholder : 'Описание сущности'
			});
		}
	});

	if ($('.js-summernote-edit').attr('id') === 'summernote-close') {
		$('#summernote').summernote({
			minHeight   : 249,
			lang        : 'ru-RU',
			placeholder : 'Описание сущности'
		});
	}

	$('#summernote').on('summernote.keyup', function() {
		if ( $('.note-editable').text() === '' ) {
			$('.js-summernote-alert').fadeOut()
		} else {
			$('.js-summernote-alert').fadeIn();
		}
	});

	/**
	 * Инициализируем плагин добавления файла.
	 */
	$('.js-file-input').bootstrapFileInput();

	/**
	* Инициализируем плагин полосы прокрутки.
	*/
	function initializeSlimScroll() {
		$sideBar.slimScroll({
			destroy : true
		});

		$sideBar.slimscroll({
			height : ($(window).height() - $('.header').height()) + 'px'
		});
	}

	$(window).on('resize orientationChange', function() {
		initializeSlimScroll();
	});

	initializeSlimScroll();

	/**
	 * Модальное окно подтверждения удаления Сущности.
	 */
	var delete_confirmation_modal = {
		init: function() {
			$('.js-confirm-delete-modal').on('click', function() {
				$('#confirm-modal').addClass('modal-show');

				return false;
			});

			$('.js-modal-delete').on('click', function() {
				$('.js-confirm-delete-modal').trigger('click');
				$('.js-modal-wrapper').removeClass('modal-show');
			});

			$('.js-modal-delete-cancel').on('click', function() {
				$(this).closest('.js-modal-wrapper').removeClass('modal-show');
			});
		}
	};

	delete_confirmation_modal.init();
});