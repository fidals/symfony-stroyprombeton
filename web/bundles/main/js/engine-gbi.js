$(function () {
	if ($("div.menu-v-content").length > 0) {
		DropDownVerticalMenu();
	}

	$('#showcase-gbi-categories').jcarousel({
		animation: 500,
		scroll: 2
	});

	if ($('#gbi_visual').length > 0) {
		Engine_GBI_Visual();
	}

	$('.jcarousel-container').mousewheel(function (event, delta) {
		if (delta > 0) {
			$('.jcarousel-prev-horizontal').click();
		} else if (delta < 0) {
			$('.jcarousel-next-horizontal').click();
		}
		return false;
	});

	if ($("div.section-photo").length > 0) {
		$('a.link-gbi-section').hover(ShowSectionPhoto, HideSectionPhoto);
	}

	if ($("div.gbi-sub-section").length > 0) {
		ShowSubSection();
		$("div.gbi-sub-section a").hover(ShowSubSectionPhoto, HideSubSectionPhoto);
	}

	if ($("div.order.form").length > 0) {
		CaptureSubmit();
		SetResetForm();
	}

	_pc_div_html_backup = null;
	_def_val = null;
	_search_condition = "";
	EngineSearch();
});

/* -------------------------------------------------------------------------------- */

function slideImg(bool, stopInt){
	var index = parseInt( $('.random_list_main > div:visible').index() );
	index++;
	var index_next=index+1;
	if(index_next>=$(".random_list_main > div").length){
		index_next=1;
	}

	$(".random_list_main > div:nth-child("+index+")").fadeOut( 250, function() {
		$(".random_list_main > div:nth-child("+index_next+")").fadeIn(250);
	});
}

function Engine_GBI_Visual() {
	$('#gbi_visual').jcarousel({
		animation: 500,
		scroll: 2
	});

	_current_section = "";
	GVDS_SetDefaultSection();
	$('div.gbi-visual-title a').click(GVDS_SelectSection);

	$('.jcarousel-next-horizontal').css("top", "260px");
	$('.jcarousel-prev-horizontal').css("top", "260px");

	$('div.gvds-pin').hover(GVDS_ShowSections, GVDS_HideSections);
	$('div.gvds-data-head-3').click(GVDS_ShowSubSections);
}

/* ```````````````````````````````````````````````````````````````````````````````` */

function GVDS_SetDefaultSection() {
	var $layer_own = $('div.gbi-visual li');
	var $layer_storage = $('div.gbi-visual-storage');
	var $layer_view = $($layer_storage.children().get(0));
	_current_section = $layer_view.attr("class").replace("gbi-visual-", "");

	$layer_own.empty();
	var $layer_view_clone = $layer_view.clone(true);
	$layer_view_clone.appendTo($layer_own);

	GVDS_SetCurrentSectionTitle();
}

function GVDS_SetCurrentSectionTitle() {
	$('div.gbi-visual-title a').each(function () {
		$(this).removeClass("gbi-visual-title-link-selected");
	});

	$('div.gbi-visual-title a[name=' + _current_section + ']').addClass("gbi-visual-title-link-selected");
}

function GVDS_SelectSection() {
	var $layer_own = $('div.gbi-visual li');
	var $layer_storage = $('div.gbi-visual-storage');
	var layer_name = $(this).attr("name");
	var $layer_view = $($layer_storage.find('div.gbi-visual-' + layer_name).get(0));

	if (_current_section == layer_name) {
		return true;
	}

	$layer_own.empty();
	var $layer_view_clone = $layer_view.clone(true);
	$layer_view_clone.appendTo($layer_own);

	_current_section = layer_name;
	GVDS_SetCurrentSectionTitle();

	return false;
}

/* ```````````````````````````````````````````````````````````````````````````````` */

function GVDS_ShowSections() {
	var $layer_data = GVDS_GetLayerData($(this));

	GVDS_SH_Pins($(this), "hidden");
	var $layer_data_clone = $layer_data.clone(true);
	$(this).empty();
	$(this).append($layer_data_clone);

	// Расчет верхнего отступа
	var top_set = parseInt($(this).css("top"));
	var height_canvas = $('div.gbi-visual').innerHeight();
	var height = $layer_data_clone.outerHeight();
	top_set -= (height_canvas - height) / 2 - 6;

	if (height < (height_canvas / 2)) {
		top_set = 0;
	}

	$layer_data_clone.css("top", "-" + top_set + "px");

	// Расчет левого отступа
	var left_set = $(this).innerWidth() - 1;

	var left = parseInt($(this).css("left"));
	var width = $layer_data_clone.outerWidth();
	var width_canvas = $('#gbi_visual').innerWidth();

	var diff = width_canvas - left;
	if (diff < width + 150) {
		left_set -= width + $(this).outerWidth() - 2;
		$(this).addClass("gvds-pin-hover-to-right");
	} else {
		$(this).addClass("gvds-pin-hover-to-left");
	}

	$layer_data_clone.css("left", left_set + "px");
	$layer_data_clone.fadeIn("fast");
}

function GVDS_HideSections() {
	var $layer_data = GVDS_GetLayerData($(this));

	$(this).removeClass("gvds-pin-hover-to-left");
	$(this).removeClass("gvds-pin-hover-to-right");

	GVDS_SH_Pins($(this), "visible");

	$layer_data.fadeOut("fast");
	$(this).empty();
}

function GVDS_GetLayerData($source_layer) {
	var code = $source_layer.attr("code");

	return $('div.gvds-data[code=' + code + ']');
}

function GVDS_SH_Pins($source_layer, sh) {
	$('div.gvd-pin-head').each(function () {
		if (sh == "hidden") {
			$(this).fadeOut("fast");
		} else {
			$(this).fadeIn("fast");
		}
	});

	var style = $source_layer.attr("style");

	$('div.gvds-pin').each(function () {
		if ($(this).attr("style") != style) {
			if (sh == "hidden") {
				$(this).fadeOut("fast");
			} else {
				$(this).fadeIn("fast");
			}
		}
	});
}

/* ```````````````````````````````````````````````````````````````````````````````` */

function GVDS_ShowSubSections() {
	var code = $(this).attr("code");
	var $ul_data = $('ul[code=' + code + ']');

	$("ul.hidden-ul").each(function () {
		$(this).slideUp('normal');
	});
	$("div.gvds-data-head-3").each(function () {
		$(this).removeClass('gvds-data-head-3-open');
	});

	if ($ul_data.css("display") == "none") {
		$ul_data.slideDown("normal");
		$(this).addClass('gvds-data-head-3-open');
	} else {
		$ul_data.hide("normal");
		$(this).removeClass('gvds-data-head-3-open');
	}
}

/* -------------------------------------------------------------------------------- */

function EngineSearch() {
	var $input_cond = $("input#search_condition");
	_def_val = $input_cond.val();
	var class_active = "search-input-active";

	$input_cond.autocomplete({
		source: "/suggest/",
		minLength: 2,
		delay: 500,
		autoFocus: false,
		open: function( event, ui ) {
			$('.ui-menu').width( $(this).css("width") );
		},
		select: function (event, ui) {
			$input_cond.val(ui.item.value);
			location.href = ui.item.url;
			return false;
		}
	});

	$input_cond.focus(function () {
		var val = $.trim($(this).val());
		if (val == _def_val) {
			$(this).val("");
			$(this).addClass(class_active);
		}
	});

	$input_cond.blur(function () {
		var val = $.trim($(this).val());
		if (val == "") {
			$(this).val(_def_val);
			$(this).removeClass(class_active);
		}
	});

	$input_cond.keypress(function (event) {
		if (event.which == 13) {
			var $input_cond = $("input#search_condition");
			var val = $.trim($input_cond.val());
			if (val == "" || val == _def_val) {
				alert("Введите условие поиска по каталогу ЖБИ продукции");
				return;
			}

			_search_condition = val;
			RunSearch();
		}
	});

	$input_cond.data( "autocomplete")._renderMenu = function(ul, items) {
		var that = this;
		$.each(items, function(index, item) {
			that._renderItem(ul, item);
		});

		if(items.length == 50) {
			var a = $('<a>', {
				text: 'Смотреть все результаты...',
				href: '#'
			});
			a.css("color", "#C90000");
			a.css("cursor", "pointer");
			a.click(function(){
				$(".search-form-submit > input[type=button]").trigger( "click" );
				$input_cond.data("autocomplete").close();
			});
			var $li = $('<li>');
			$li.append(a).appendTo(ul);
		}
	}

	// код взят с modx-версии
	$input_cond.data( "autocomplete" )._renderItem = function( ul, item ) {
		var a = $('<a>', {
			onclick: "document.location.href = '"+item.url+"'",
			text: item.label
		});
		if(item.razdel){
			a.css("font-weight", "bold");
		}
		if(item.desc && item.desc != '') a.prepend("<span style='color: #808080;'>[" + item.desc + "]</span> ");
		var $li = $('<li>');
		return $li.append(a).data('item.autocomplete', item).appendTo(ul);
	}

	$("div.search-form td.search-form-submit input").click(function () {
		var $input_cond = $("input#search_condition");
		var val = $.trim($input_cond.val());
		if (val == "" || val == _def_val) {
			alert("Введите условие поиска по каталогу ЖБИ продукции");
			return;
		}

		_search_condition = val;
		RunSearch();
	});

	RunSearch();
}

/* ```````````````````````````````````````````````````````````````````````````````` */

function RunSearch() {
	_search_condition = $.trim(_search_condition);
	if (_search_condition == "") {
		return;
	}

	var $input_cond = $("input#search_condition");
	$input_cond.val(_search_condition);
	$input_cond.addClass("search-input-active");

	var $pc_div = $("div.primary-content");
	if (_pc_div_html_backup == null) {
		_pc_div_html_backup = $pc_div.html();
	}
	$pc_div.empty();

	$pc_div.html('<div class="search-waiting">Подождите пожалуйста.<br />Выполняется поиск в каталоге продукции ...</div>');

	var loc_html = '<div class="location">';
	loc_html += '<span class="location section"><a href="/">СТК-ПромБетон</a></span>';
	loc_html += ' <span class="location separate">/</span> ';
	loc_html += '<span class="location section">Результаты поиска';
	loc_html += ' (<a id="SearchClose" href="javascript:void(0);" onclick="SearchClose();"><b>закрыть</b></a>)</span>';
	loc_html += '</div>';

	$.ajax({
		type: "GET",
		url: "/search/",
		data: { condition: _search_condition },
		cache: false,
		success: function (data) {
			if (data == "noresult") {
				$pc_div.html(loc_html + '<div class="search-noresult">По вашему запросу в каталоге продукции ЖБИ изделий не найдено.</div>');
				return;
			}
			history.pushState(null, document.title, '/search-results/?search=' + _search_condition);
			$pc_div.empty();
			$pc_div.html(loc_html + data);
		}
	});
}

/* ```````````````````````````````````````````````````````````````````````````````` */

function SearchClose() {
	var $pc_div = $("div.primary-content");
	$pc_div.html(_pc_div_html_backup);

	var $input_cond = $("input#search_condition");
	$input_cond.val(_def_val);
	$input_cond.removeClass("search-input-active");
}

/* -------------------------------------------------------------------------------- */

function ShowSubSectionPhoto() {
	var $div_parent = $(this).parent();
	if ($div_parent.find("div").length == 0) {
		return;
	}

	var $div = $($div_parent.find("div").get(0));

	var margin = $div_parent.width() - $div.width() - 10;
	$div.css("marginLeft", +margin + "px");
	$div.show();
}

function HideSubSectionPhoto() {
	var $div_parent = $(this).parent();
	if ($div_parent.find("div").length == 0) {
		return;
	}

	var $div = $($div_parent.find("div").get(0));

	$div.hide();
}

/* -------------------------------------------------------------------------------- */

function ShowSubSection() {
	$("div.gbi-sub-section a").each(function () {
		$(this).click(function () {
			var ssc = $(this).attr("sub-section-code");
			var $div = $("div.sub-section-" + ssc);

			if ($div.css("display") == "none") {
				$div.slideDown('normal');
				$(this).addClass("gbi-sub-section-open");
			} else {
				$div.slideUp('normal');
				$(this).removeClass("gbi-sub-section-open");
			}

			return false;
		});
	});
}

/* -------------------------------------------------------------------------------- */

function DropDownVerticalMenu() {
	$("a.show-v-dropdown").click(function () {
		var $li = $(this).parent();
		var $div_cur = $($li.find("div.menu-v-dropdown").get(0));

		if ($div_cur.css('display') != "none") {
			return true;
		}

		$("div.menu-v-dropdown").each(function () {
			$(this).slideUp('normal');
		});
		$("a.show-v-dropdown").each(function () {
			$(this).addClass('show-v-dropdown-close');
		});

		$div_cur.slideDown('normal');
		$(this).removeClass('show-v-dropdown-close');

		return false;
	});
}

/* -------------------------------------------------------------------------------- */

function ShowSectionPhoto() {
	var $cell = $(this).parent();
	var $div = $($cell.find("div").get(0));

	if ($cell.find("div").length == 0) {
		return;
	}

	var $cell_work_docs = $($cell.parent().find("td.gbi-showcase-work-docs").get(0));
	var $div_wd = $($cell_work_docs.find("div").get(0));
	var content_wd = $.trim($div_wd.html()) + "";
	if (content_wd != "") {
		$div_wd.addClass("gbi-showcase-work-docs-hover");
	}

	var margin = $cell.width() - $div.width() - 10;
	$div.css("marginLeft", +margin + "px");
	$div.show();
}

function HideSectionPhoto() {
	var $cell = $(this).parent();
	var $div = $($cell.find("div").get(0));

	if (!$div) {
		return;
	}

	var $cell_work_docs = $($cell.parent().find("td.gbi-showcase-work-docs").get(0));
	var $div_wd = $($cell_work_docs.find("div").get(0));
	$div_wd.removeClass("gbi-showcase-work-docs-hover");

	$div.hide();
}

/* -------------------------------------------------------------------------------- */

function ShowMenuDropDown() {
	$drop_layer = $($(this).find('div').get(0));
	$drop_layer.slideDown('slow');
}

function HideMenuDropDown() {
	$drop_layer = $($(this).find('div').get(0));
	$drop_layer.slideUp('normal');
}

/* -------------------------------------------------------------------------------- */

function SetResetForm() {
	$("div.order.form").find("input[type=button]").click(function () {
		$("div.order.form").find("input[type=text], select, textarea").each(function () {
			$(this).val("");
			$(this).removeClass("error-input");
		});
	});
}

function CaptureSubmit() {
	$("div.order.form").find("form").submit(function () {
		var is_empty = false;

		$("div.order.form").find(".asterisk").each(function () {
			var val = $.trim($(this).val());
			if (val == "") {
				is_empty = true;
				$(this).addClass("error-input");
			}
		});

		if (is_empty) {
			return false;
		}

		return true;
	});
}

/* -------------------------------------------------------------------------------- */
