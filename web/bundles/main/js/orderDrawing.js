$(function () {
    /**
     * Логика работы с файлами в форме 'Заказа изделий по индивидуальным чертежам':
     */
    var $btnSend = $('#btn-drawing-submit'),
        $fileCountNotifyTag = $('#order-file-notify'),
        $fileCountErrorTag = $('.js-error-count'),
        $fileSizeErrorTag = $('.js-error-large-file'),
        maxFileSize = 25550000, // ~25 Mb
        filesTagId = 'order_drawing_files',
        colorRed = '#C90000',
        colorGrey = '#999';

    $('#' + filesTagId).change(function() {
        var fileCount = parseInt($(this).get(0).files.length);

        if ( fileCount > 0 ) {
            $fileCountNotifyTag.html('Число файлов: ' + fileCount);
        } else {
            $fileCountNotifyTag.html('Файлы не выбраны');
        }

        if ( fileCount > 10 ) {
            $fileCountErrorTag.css('color', colorRed);
            $btnSend.attr('disabled', true);
        } else {
            $fileCountErrorTag.css('color', colorGrey);
            $btnSend.attr('disabled', false);
        }

        if ( checkFilesSize() === false ) {
            $fileSizeErrorTag.removeClass('hide').css('color', colorRed);
            $btnSend.attr('disabled', true);
        } else {
            $fileSizeErrorTag.addClass('hide');
            $btnSend.attr('disabled', false);
        }
    });

    $btnSend.on('click', function() {
        localStorage.setItem('userEmailForOrder', $('#order_drawing_email').val());
        localStorage.setItem('userPhoneForOrder', $('#order_drawing_phone').val());
    });

    function checkFilesSize() {
        var $input = document.getElementById(filesTagId);

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