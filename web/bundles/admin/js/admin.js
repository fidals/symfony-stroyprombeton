var CatalogAdmin = {
	urls: {
		deleteFile: '/admin/files/delete/',
		uploadFile: '/admin/files/upload'
	},

	refreshFileList: function (newFileList) {
		$("div.entity-assets").html(newFileList);
	},

	deleteFile: function (entity, id, fileName) {
		var deleteUrlForFile = CatalogAdmin.urls.deleteFile + entity + '/' + id + '/' + fileName;

		$.post(
			deleteUrlForFile,
			{},
			function (json) {
				CatalogAdmin.refreshFileList(json.html);
			}, 'json'
		)
	},

	constructUploadData: function (file, entity, id) {
		var data = new FormData();
		data.append('entity', entity);
		data.append('id', id);
		data.append('file', file);

		return data;
	},

	startUploading: function () {
		var inputButton = $('.js-file-input');

		inputButton.toggleClass('btn-primary btn-success');
		inputButton.prev().html(' ' + 'Загрузка');
	},

	finishUploading: function () {
		var inputButton = $('.js-file-input');

		inputButton.toggleClass('btn-success btn-primary');
		//dev-618 - Функционал с файлами не пашет, пока не переделаем структуру файлов
		//inputButton.prev().text(' Добавить файл');
	},

	uploadFile: function (event, entity, id) {
		var file = event.target.files[0];
		var data = CatalogAdmin.constructUploadData(file, entity, id);

		CatalogAdmin.startUploading();

		$.ajax({
			url: CatalogAdmin.urls.uploadFile,
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function (data) {
				CatalogAdmin.refreshFileList(data.html);
				CatalogAdmin.finishUploading();
			},
			error: function () {
				// Пока что так. Если будет нужда - будем изобретать модалки и рюшки
				alert('Файл не может быть загружен.');
				CatalogAdmin.finishUploading();
			}
		});
	}
};

/* JS for new Admin Page */
$(function () {
	var $treeTarget = $('#js-tree'),
		$sideBar    = $('.sidebar'),
		$fileUpload = $('.js-file-input'),
        $sidebarToggle = $('.js-sidebar-toggle');

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

    $('.sidebar-wrapper').append('<a href="#" id="link-to-go"></a>');

    var $linkToGo   = $('#link-to-go')

	var bindEditAction = function() {
		$('.jstree-anchor').bind('mousedown', function (e) {
			if (e.which == 1) { // если клик левой кнопкой мыши
				var anchor     = $(e.currentTarget),
					entityId   = anchor.data('id'),
					entityType = anchor.parent().hasClass('jstree-leaf') ? 'product' : 'category',
					pathname   = '/admin/' + entityType + '/edit/' + entityId;

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
					var url = '/admin/editproducts';
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
						|| ref.parent().find('ul:first').find('li:first').hasClass('jstree-leaf')
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
	 * Инициализируем плагин добавления файла.
	 */
	$fileUpload.bootstrapFileInput();

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

	$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});

	/**
	 * Показывает напоминание "не забудьте сохранить изменения", при изменении инстанса CKEditor.
	 * @param editor
	 */
	var showEditorNotification = function (editor) {
		CKEDITOR.instances[editor].on('change', function () {
			$('.fa-star').show()
		});
	};

	if (typeof CKEDITOR !== 'undefined') {
		CKEDITOR.config.protectedSource.push(/<(style)[^>]*>.*<\/style>/ig);
		CKEDITOR.config.protectedSource.push(/<(script)[^>]*>.*<\/script>/ig);

		for (var editor in CKEDITOR.instances) {
			showEditorNotification(editor);
		}
	}

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

    $sidebarToggle.on('click', function() {
        $('body').toggleClass('sidebar-hidden');
    });

	delete_confirmation_modal.init();
});