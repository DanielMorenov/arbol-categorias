$(document).on('click', ".material-icons", function () {
    var elemento = $(this).attr('id').substring(6);

    if ($(this).html() === 'arrow_drop_down') {
        $(this).html("arrow_right");
        $('#' + elemento).hide(200, 'swing');
        return;
    }

    $(this).html("arrow_drop_down");
    $('#' + elemento).show(200, 'swing');
});
