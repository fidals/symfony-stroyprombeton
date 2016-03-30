var singleResultId = 0;
var changeLocationOnSelect = true;
var sourceForSearch = '/suggest/';

// Преобразует строку Url с параметрами объект, где название параметра
// является ключем к его значению
function getQueryParams(qs) {
  qs = qs.split('+').join(' ');

  var params = {};
  var tokens;
  var re = /[?&]?([^=]+)=([^&]*)/g;

  while (tokens = re.exec(qs)) {
    params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
  }

  return params;
}

$(function () {
  // Делаем callback на событие popstate из history.js
  History.Adapter.bind(window, 'popstate', function (e) {
    var State = History.getState();

    // Способ парсинга url взят отсюда https://gist.github.com/jlong/2428561
    var parser = document.createElement('a');
    parser.href = State.url;
    var search = parser.search; // то что в url после знака "?"
    var params = getQueryParams(search);

    // Если Url не аяксовый поиск - то загружаем страницу как обычно
    if (parser.pathname !== '/search-results/') {
      window.location.reload();
    } else {
      RunSearch(params.search);
    }
  });

  if ($('div.menu-v-content').length > 0) {
    DropDownVerticalMenu();
  }

  $('#showcase-gbi-categories').jcarousel({
    animation: 500,
    scroll: 2,
  });

  if ($('#gbi_visual').length > 0) {
    engineGbiVisual();
  }

  $('.jcarousel-container').mousewheel(function (event, delta) {
    if (delta > 0) {
      $('.jcarousel-prev-horizontal').click();
    } else if (delta < 0) {
      $('.jcarousel-next-horizontal').click();
    }

    return false;
  });

  if ($('div.section-photo').length > 0) {
    $('a.link-gbi-section').hover(ShowSectionPhoto, HideSectionPhoto);
  }

  if ($('div.gbi-sub-section').length > 0) {
    ShowSubSection();
    $('div.gbi-sub-section a').hover(ShowSubSectionPhoto, HideSubSectionPhoto);
  }

  if ($('div.order.form').length > 0) {
    CaptureSubmit();
    SetResetForm();
  }

  _pc_div_html_backup = null;
  _def_val = null;
  _search_condition = '';

  EngineSearch();
});

function slideImg(bool, stopInt) {
  var index = parseInt($('.random_list_main > div:visible').index());
  index++;
  var indexNext = index + 1;
  if (indexNext >= $('.random_list_main > div').length) {
    indexNext = 1;
  }

  $('.random_list_main > div:nth-child(' + index + ')').fadeOut(250, function () {
    $('.random_list_main > div:nth-child(' + indexNext + ')').fadeIn(250);
  });
}

function engineGbiVisual() {
  $('#gbi_visual').jcarousel({
    animation: 500,
    scroll: 2,
  });

  _current_section = '';
  gvdsSetDefaultSection();
  $('div.gbi-visual-title a').click(gvdsSelectSection);

  $('.jcarousel-next-horizontal, .jcarousel-prev-horizontal').css('top', '260px');

  $('div.gvds-pin').hover(gvdsShowSections, gvdsHideSections);
  $('div.gvds-data-head-3').click(gvdsShowSubSections);
}

function gvdsSetDefaultSection() {
  var $layerOwn = $('div.gbi-visual li');
  var $layerStorage = $('div.gbi-visual-storage');
  var $layerView = $($layerStorage.children().get(0));
  _current_section = $layerView.attr('class').replace('gbi-visual-', '');

  $layerOwn.empty();
  var $layerViewClone = $layerView.clone(true);
  $layerViewClone.appendTo($layerOwn);

  gvdsSetCurrentSectionTitle();
}

function gvdsSetCurrentSectionTitle() {
  $('div.gbi-visual-title a').each(function () {
    $(this).removeClass('gbi-visual-title-link-selected');
  });

  $('div.gbi-visual-title a[name=' + _current_section + ']').addClass('gbi-visual-title-link-selected');
}

function gvdsSelectSection() {
  var $layerOwn = $('div.gbi-visual li');
  var $layerStorage = $('div.gbi-visual-storage');
  var layerName = $(this).attr('name');
  var $layerView = $($layerStorage.find('div.gbi-visual-' + layerName).get(0));

  if (_current_section == layerName) {
    return true;
  }

  $layerOwn.empty();
  var $layerViewClone = $layerView.clone(true);
  $layerViewClone.appendTo($layerOwn);

  _current_section = layerName;
  gvdsSetCurrentSectionTitle();

  return false;
}

function gvdsShowSections() {
  var $layerData = gvdsGetLayerData($(this));

  gvdsShPins($(this), 'hidden');
  var $layerDataClone = $layerData.clone(true);
  $(this).empty();
  $(this).append($layerDataClone);

  // Расчет верхнего отступа
  var top_set = parseInt($(this).css('top'));
  var height_canvas = $('div.gbi-visual').innerHeight();
  var height = $layerDataClone.outerHeight();
  top_set -= (height_canvas - height) / 2 - 6;

  if (height < (height_canvas / 2)) {
    top_set = 0;
  }

  $layerDataClone.css("top", "-" + top_set + "px");

  // Расчет левого отступа
  var left_set = $(this).innerWidth() - 1;

  var left = parseInt($(this).css('left'));
  var width = $layerDataClone.outerWidth();
  var width_canvas = $('#gbi_visual').innerWidth();

  var diff = width_canvas - left;
  if (diff < width + 150) {
    left_set -= width + $(this).outerWidth() - 2;
    $(this).addClass('gvds-pin-hover-to-right');
  } else {
    $(this).addClass('gvds-pin-hover-to-left');
  }

  $layerDataClone.css('left', left_set + 'px');
  $layerDataClone.fadeIn('fast');
}

function gvdsHideSections() {
  var $layerData = gvdsGetLayerData($(this));

  $(this).removeClass('gvds-pin-hover-to-left').removeClass('gvds-pin-hover-to-right');

  gvdsShPins($(this), 'visible');

  $layerData.fadeOut('fast');
  $(this).empty();
}

function gvdsGetLayerData($sourceLayer) {
  var code = $sourceLayer.attr('code');

  return $('div.gvds-data[code=' + code + ']');
}

function gvdsShPins($sourceLayer, sh) {
  $('div.gvd-pin-head').each(function () {
    if (sh === 'hidden') {
      $(this).fadeOut('fast');
    } else {
      $(this).fadeIn('fast');
    }
  });

  var style = $sourceLayer.attr('style');

  $('div.gvds-pin').each(function () {
    if ($(this).attr('style') !== style) {
      if (sh == 'hidden') {
        $(this).fadeOut('fast');
      } else {
        $(this).fadeIn('fast');
      }
    }
  });
}

function gvdsShowSubSections() {
  var code = $(this).attr('code');
  var $ulData = $('ul[code=' + code + ']');

  $('ul.hidden-ul').each(function () {
    $(this).slideUp('normal');
  });

  $('div.gvds-data-head-3').each(function () {
    $(this).removeClass('gvds-data-head-3-open');
  });

  if ($ulData.css('display') === 'none') {
    $ulData.slideDown('normal');
    $(this).addClass('gvds-data-head-3-open');
  } else {
    $ulData.hide('normal');
    $(this).removeClass('gvds-data-head-3-open');
  }
}

function EngineSearch() {
  var $inputAutocomplete = $('.js-autocomplete');

  if (!$inputAutocomplete) {
    return;
  }

  if ($inputAutocomplete.hasClass('js-autocomplete-type-value')) {
    changeLocationOnSelect = false;
    sourceForSearch = '/suggest-admin/';
  }

  _def_val = $inputAutocomplete.val();

  $inputAutocomplete.autocomplete({
    source: sourceForSearch,
    minLength: 2,
    delay: 500,
    autoFocus: false,
    open: function () {
      $('.ui-autocomplete').width($('.js-autocomplete').outerWidth());
    },

    select: function (event, ui) {
      $inputAutocomplete.val(ui.item.value);
      
      if (changeLocationOnSelect) {
        location.href = ui.item.url;
      }

      return false;
    },
  });

  $inputAutocomplete.focus(function () {
    var val = $.trim($(this).val());
    if (val === _def_val) {
      $(this).val('');
    }
  });

  $inputAutocomplete.blur(function () {
    var val = $.trim($(this).val());
    if (val == '') {
      $(this).val(_def_val);
    }
  });

  $inputAutocomplete.on('keypress', function (event) {
    var $inputAutocomplete = $('.js-autocomplete');

    if (event.which === 13 && !$inputAutocomplete.hasClass('js-autocomplete-type-value')) {
      var searchResultItems = $('.ui-autocomplete').find('.ui-menu-item').size(); // кол-во найденых товаров;
      var val = $.trim($inputAutocomplete.val());

      if (val == '' || val == _def_val) {
        alert('Введите условие поиска по каталогу ЖБИ продукции');
        return;
      }

      if (searchResultItems === 1) {
        window.location.pathname = 'gbi/products/' + singleResultId + '/';
      } else {
        _search_condition = val;

        History.pushState(null, document.title, '/search-results/?search=' + _search_condition);
        RunSearch();
      }
    }
  });

  $('#btn-search-action').click(function () {
    var $inputAutocomplete = $('.js-autocomplete');

    // кол-во найденых товаров;
    var searchResultItems = $('.ui-autocomplete').find('.ui-menu-item').size();
    var val = $.trim($inputAutocomplete.val());

    if (val == '' || val == _def_val) {
      alert('Введите условие поиска по каталогу ЖБИ продукции');
      return;
    }

    if (searchResultItems === 1) {
      window.location.pathname = 'gbi/products/' + singleResultId + '/';
    } else {
      _search_condition = val;

      History.pushState(null, document.title, '/search-results/?search=' + _search_condition);
      RunSearch();
    }
  });

  $inputAutocomplete.data('autocomplete')._renderMenu = function (ul, items) {
    var _this = this;

    if (items.length === 1) {
      // Если результат поиска - 1 товар, то запоминаем его id:
      singleResultId = items[0].id;
    }

    $.each(items, function (index, item) {
      _this._renderItem(ul, item);
    });

    if (items.length == 20) {
      var $a = $('<a>', {
        class: 'autocomplete-show-all',
        text: 'Показать все результаты...',
        href: '#',
      });

      $a.click(function () {
        $('.search-form-submit > input[type=button]').trigger('click');
        $inputAutocomplete.data('autocomplete').close();
      });

      $('<li>').append($a).appendTo(ul);
    }
  };

  // код взят с modx-версии
  $inputAutocomplete.data('autocomplete')._renderItem = function (ul, item) {
    var $a = $('<a>', { text: item.label });

    if (changeLocationOnSelect) {
      $a.attr('onclick', 'document.location.href = "' + item.url + '"');
    }

    if (item.razdel) {
      $a.addClass('autocomplete-anchor');
    }

    if (item.desc && item.desc !== '') {
      $a.prepend('<span class="autocomplete-pretext">[' + item.desc + ']</span> ');
    }

    var $li = $('<li>');

    return $li.append($a).data('item.autocomplete', item).appendTo(ul);
  };

  RunSearch();
}

function RunSearch(condition) {
  var $inputAutocomplete = $('.js-autocomplete');

  _search_condition = condition || $.trim(_search_condition);

  if (_search_condition === '') {
    return;
  }

  $inputAutocomplete.val(_search_condition);

  var $pc_div = $('div.primary-content');

  if (_pc_div_html_backup == null) {
    _pc_div_html_backup = $pc_div.html();
  }

  $pc_div.empty().html('<div class="search-waiting">Подождите пожалуйста.<br />Выполняется поиск в каталоге продукции ...</div>');

  var loc_html = '<div class="location">';
  loc_html += '<span class="location section"><a href="/">СТК-ПромБетон</a></span>';
  loc_html += ' <span class="location separate">/</span> ';
  loc_html += '<span class="location section">Результаты поиска';
  loc_html += ' (<a id="SearchClose" href="javascript:void(0);" onclick="SearchClose();"><b>закрыть</b></a>)</span>';
  loc_html += '</div>';

  $.ajax({
    type: 'GET',
    url: '/search/',
    data: { search: _search_condition },
    cache: false,
    success: function (data) {
      if (data === 'noresult') {
        $pc_div.html(loc_html + '<div class="search-noresult">По вашему запросу в каталоге продукции ЖБИ изделий не найдено.</div>');
        return;
      }

      $pc_div.empty().html(loc_html + data);

      Shadowbox.setup();
    },
  });
}

function SearchClose() {
  $('div.primary-content').html(_pc_div_html_backup);

  var $inputAutocomplete = $('.js-autocomplete');
  $inputAutocomplete.val(_def_val);
}

function ShowSubSectionPhoto() {
  var $div_parent = $(this).parent();
  if ($div_parent.find('div').length == 0) {
    return;
  }

  var $div = $($div_parent.find('div').get(0));

  var margin = $div_parent.width() - $div.width() - 10;
  $div.css('marginLeft', + margin + 'px').show();
}

function HideSubSectionPhoto() {
  var $div_parent = $(this).parent();
  if ($div_parent.find('div').length == 0) {
    return;
  }

  $($div_parent.find('div').get(0)).hide();
}

function ShowSubSection() {
  $('div.gbi-sub-section a').each(function () {
    $(this).click(function () {
      var ssc = $(this).attr('sub-section-code');
      var $div = $('div.sub-section-' + ssc);

      if ($div.css('display') == 'none') {
        $div.slideDown('normal');
        $(this).addClass('gbi-sub-section-open');
      } else {
        $div.slideUp('normal');
        $(this).removeClass('gbi-sub-section-open');
      }

      return false;
    });
  });
}

function DropDownVerticalMenu() {
  $('a.show-v-dropdown').click(function () {
    var $li = $(this).parent();
    var $div_cur = $($li.find('div.menu-v-dropdown').get(0));

    if ($div_cur.css('display') != 'none') {
      return true;
    }

    $('div.menu-v-dropdown').each(function () {
      $(this).slideUp('normal');
    });

    $('a.show-v-dropdown').each(function () {
      $(this).addClass('show-v-dropdown-close');
    });

    $div_cur.slideDown('normal');
    $(this).removeClass('show-v-dropdown-close');

    return false;
  });
}

function ShowSectionPhoto() {
  var $cell = $(this).parent();
  var $div = $($cell.find('div').get(0));

  if ($cell.find('div').length == 0) {
    return;
  }

  var $cell_work_docs = $($cell.parent().find('td.gbi-showcase-work-docs').get(0));
  var $div_wd = $($cell_work_docs.find('div').get(0));
  var content_wd = $.trim($div_wd.html()) + '';
  if (content_wd != '') {
    $div_wd.addClass('gbi-showcase-work-docs-hover');
  }

  var margin = $cell.width() - $div.width() - 10;
  $div.css('marginLeft', +margin + 'px');
  $div.show();
}

function HideSectionPhoto() {
  var $cell = $(this).parent();
  var $div = $($cell.find('div').get(0));

  if (!$div) {
    return;
  }

  var $cell_work_docs = $($cell.parent().find('td.gbi-showcase-work-docs').get(0));
  var $div_wd = $($cell_work_docs.find('div').get(0));
  $div_wd.removeClass('gbi-showcase-work-docs-hover');

  $div.hide();
}

function ShowMenuDropDown() {
  $($(this).find('div').get(0)).slideDown('slow');
}

function HideMenuDropDown() {
  $($(this).find('div').get(0)).slideUp('normal');
}

function SetResetForm() {
  $('div.order.form').find('input[type=button]').click(function () {
    $('div.order.form').find('input[type=text], select, textarea').each(function () {
      $(this).val('').removeClass('error-input');
    });
  });
}

function CaptureSubmit() {
  $('div.order.form').find('form').submit(function () {
    var is_empty = false;

    $('div.order.form').find('.asterisk').each(function () {
      var val = $.trim($(this).val());
      if (val == '') {
        is_empty = true;
        $(this).addClass('error-input');
      }
    });

    return !is_empty;
  });
}
