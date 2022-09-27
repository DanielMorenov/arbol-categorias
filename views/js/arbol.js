$( document ).ready(function() {
    
    $(".material-icons").on('click', function()
    {
        var elemento = $(this).attr('id');
        elemento = elemento.substring(6);

        if($(this).html()==='arrow_drop_down')
        {
            $(this).html("arrow_right");
            $('#' + elemento).hide(200,'swing');
        }
        else
        {
            $(this).html("arrow_drop_down");
            $('#' + elemento).show(200,'swing');
        }
    });

});
