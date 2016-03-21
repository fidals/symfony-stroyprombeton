var sortableBlock = $('#sortable');

$(function() {
    sortableBlock
        .sortable({
            placeholder : 'ui-state-highlight'
        })
        .disableSelection();

    $('input[name="ff[]"]').change(function() {
        if ($(this).is(':checked')) {
            sortableBlock.append('<li class="ui-state-default sortf_' +
                $(this).val()+'"><input type="hidden" name="sortff[]" value="' +
                $(this).val()+'">' + $("#fs_" + $(this).val()).html()+'</li>'
            );
        } else {
            $('.sortf_'+$(this).val()).remove();
        }
    });

    $('.js-hide-sort').click(function() {
        var expire = new Date(new Date().getTime() + 3600000*24*10);
        var $self = $('.js-hide-sort');

        if ($('.js-trhide').eq(0).css('display') === 'none') {
            $('.js-trhide').slideToggle();
            $self.html('скрыть');
            document.cookie = 'hide_sort=1;expires='+expire.toGMTString();
        } else {
            $('.js-trhide').slideToggle();
            $self.html('показать');
            document.cookie = 'hide_sort=0;expires='+expire.toGMTString();
        }
    });
});