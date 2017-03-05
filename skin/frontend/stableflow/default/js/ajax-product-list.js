/**
 * Created by nick on 3/5/17.
 */

(function($){

    function initLoader() {
        $('.ajax-list-overlay').fadeIn(200, function(){
            $('.ajax-list-loader').show();
        });
    }

    function killLoader() {
        $('.ajax-list-loader').hide();
        $('.ajax-list-overlay').fadeOut(200);
    }

    function toolbar(){
        $('.toolbar a').each(function(index){
            $(this).click(function(){
                filterAjax($(this).attr('href'));
                console.log( index + ": " + $( this ).text() );
                return false;
            });

        });
        $('.toolbar select').each(function(index){
            $(this).removeAttr('onchange');
            $(this).change(function(){
                filterAjax($(this).val());
                console.log( index + ": " + $( this ).text() );
                return false;
            });
        });
    }

    function updatePage(data) {
        //console.log(data);
        var categoryProducts = $('.category-products');
        var listBlock = categoryProducts.parent();
        categoryProducts.remove();
        listBlock.append(data.page);
        $('#tabs-2').append(data.js);
        $('#loader').hide();
    }

    function filterAjax(s_url){
        $.ajax({
            type: "GET",
            data : {is_ajax:1},
            url: s_url,
            beforeSend: function() {
                initLoader();
            },
            success: function(data) {
                killLoader();
                updatePage(data);
                toolbar();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //console.log(errorThrown);
            }
        });
    }

    function categoryPageItemsHover() {
        var productItem = $('.products-grid .item .item-image-wrapper');
        productItem.hover(
            function() {
                $(this).find('.image-overlay').stop().fadeIn(250);
            },
            function() {
                $(this).find('.image-overlay').stop().fadeOut(250);
            }
        );
    }


    $(document).ready(function(){
        toolbar();
    });
})($j);