
var num_cats = 0;

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

    $(".main-category").on('click', function()
    {
        let cat_name = $(this).val();
        if($(this).prop('checked')) addCat(cat_name);
        else remCat(cat_name);
    });

});

function addCat(cat_name)
{
    $('#nube_cat').append("<span id='nube_" + camelize(cat_name) + "'>" + cat_name + "</span>");
    if(num_cats===0) $('#sincat').remove();
    num_cats++;
}
function remCat(cat_name)
{
    $('#nube_' + camelize(cat_name)).remove();
    num_cats--;
    if(num_cats===0) $('#nube_cat').append("<span id='sincat'>Sin categorías</span>");
}
function camelize(str) {
    return str.replace(/\W+(.)/g, function(match, chr)
     {
          return chr.toUpperCase();
      });
  }

