$(function () {
  var ractiveOrder = new Ractive({
    el: '#order-table',
    template: '#ractive-order',
    data: ractiveDropdown,
    adapt: ['Ractive']
  });

  ractiveOrder.on('productCountPlus', function (event) {
    var product = this.get(event.keypath);

    product.count += 1;
    this.set(event.keypath, product);
    Cart.update(product.id, product.count);
  });

  ractiveOrder.on('productCountMinus', function (event) {
    var product = this.get(event.keypath);

    product.count -= 1;
    if (product.count < 1) {
      product.count = 1;
      this.set(event.keypath, product);
    }

    this.set(event.keypath, product);
    Cart.update(product.id, product.count);
  });

  /**
   * Логика работы с файлами в форме:
   */
  var $btnSend = $('#btn-order-send'),
    $fileCountNotifyTag = $('#order-file-notify'),
    $fileCountErrorTag = $('.js-error-count'),
    $fileSizeErrorTag = $('.js-error-large-file'),
    maxFileSize = 25550000; // ~25 Mb

  $('#order_files').change(function() {
    var fileCount = parseInt($(this).get(0).files.length);

    if ( fileCount > 0 ) {
      $fileCountNotifyTag.html('Число файлов: ' + fileCount);
    } else {
      $fileCountNotifyTag.html('Файлы не выбраны');
    }

    if ( fileCount > 10 ) {
      $fileCountErrorTag.css('color', '#C90000');
      $btnSend.attr('disabled', true);
    } else {
      $fileCountErrorTag.css('color', '#999');
      $btnSend.attr('disabled', false);
    }

    if ( checkFilesSize() === false ) {
      $fileSizeErrorTag.removeClass('hide').css('color', '#C90000');
      $btnSend.attr('disabled', true);
    } else {
      $fileSizeErrorTag.addClass('hide');
      $btnSend.attr('disabled', false);
    }
  });

  $btnSend.on('click', function() {
    localStorage.setItem('userEmailForOrder', $('#order_email').val());
    localStorage.setItem('userPhoneForOrder', $('#order_phone').val());
  });

  function checkFilesSize() {
    var $input = document.getElementById("order_files");

    // сначала - проверка на поддержку в браузерах
    if ($input.files && $input.files.length > 0) {
      for (var i = 0, len = $input.files.length; i < len; i++) {
        if ($input.files[i].size > maxFileSize) {
          return false;
        }
      }
    }

    return true;
  }
});