$(document).on( 'click', '.to-cart:not(.read_more)', function(e){
    e.preventDefault();
    var th=$(this);
    if(!th.hasClass('clicked'))
    {
        th.addClass('clicked');
        var val = $(this).attr('data-quantity');


        var tmp_props=$(this).data("props"),
            props='',
            part_props='',
            add_props='N',
            fill_prop={},
            iblockid = $(this).data('iblockid'),
            offer = $(this).data('offers'),
            rid='',
            basket_props='',
            item = $(this).attr('data-item');
        if(th.closest('.but-cell').length)
        {
            if($('.counter_block[data-item="'+item+'"]').length)
                val = $('.counter_block[data-item="'+item+'"] input').val();
        }

        if(!val)
            val = 1;
        if(offer!="Y"){
            offer = "N";
        }else{
            basket_props=$(this).closest('.prices_tab').find('.bx_sku_props input').val();
        }
        if(tmp_props){
            props=tmp_props.split(";");
        }
        if($(this).data("part_props")){
            part_props=$(this).data("part_props");
        }
        if($(this).data("add_props")){
            add_props=$(this).data("add_props");
        }
        if($('.rid_item').length){
            rid=$('.rid_item').data('rid');
        }else if($(this).data('rid')){
            rid=$(this).data('rid');
        }

        fill_prop=fillBasketPropsExt(th, 'prop', th.data('bakset_div'));

        var itemPrice = calculatePrice().base.price;
        if (itemPrice <= 0) {
            itemPrice = $('.prices_block .price:not(.discount)').data('value');
        }

        fill_prop.quantity=val;
        fill_prop.add_item='Y';
        fill_prop.rid=rid;
        fill_prop.offers=offer;
        fill_prop.iblockID=iblockid;
        fill_prop.part_props=part_props;
        fill_prop.add_props=add_props;
        fill_prop.props=JSON.stringify(props);
        fill_prop.item=item;
        fill_prop.basket_props=basket_props;
        fill_prop.price = itemPrice;

        if(th.data("empty_props")=="N"){
            showBasketError($("#"+th.data("bakset_div")).html(), BX.message("ERROR_BASKET_PROP_TITLE"), "Y", th);

            var eventdata = {action:'loadForm'};
            BX.onCustomEvent('onCompleteAction', [eventdata, th[0]]);
        } else {
            addItem(fill_prop, th, item);
            $('.set-composition_accesories .set-composition_row').each(function (index, el) {
                if ($(el).find('.set-composition_checkbox input').prop('checked')) {
                    fill_prop.item = $(el).data('id');
                    fill_prop.price = $(el).data('price');
                    //fill_prop.quantity = $(el).data('amount');
                    addItem(fill_prop, th, item);
                }
            })
        }
    }
})
$(function(){
    var price=calculatePrice();
    if (price.price > 0) {
        showPrice(price);
    }
    $('.set-composition .set-composition_checkbox input').change(function(e){
        var price=calculatePrice();
        if (price.price > 0) {
            showPrice(price);
        }
    })
})

function addItem(fill_prop, th, item) {
    $.ajax({
        type: "POST",
        url: arNextOptions['SITE_DIR'] + "ajax/item_price.php",
        data: fill_prop,
        dataType: "json",
        success: function (data) {
            getActualBasket(fill_prop.iblockID);

            var eventdata = {action: 'loadForm'};
            BX.onCustomEvent('onCompleteAction', [eventdata, th[0]]);
            arStatusBasketAspro = {};

            if (data !== null) {
                if ("STATUS" in data) {
                    if (data.MESSAGE_EXT === null)
                        data.MESSAGE_EXT = '';
                    if (data.STATUS === 'OK') {
                        // th.hide();
                        $('.to-cart[data-item=' + item + ']').hide();
                        $('.to-cart[data-item=' + item + ']').closest('.counter_wrapp').find('.counter_block').hide();
                        $('.to-cart[data-item=' + item + ']').parents('tr').find('.counter_block_wr .counter_block').hide();
                        $('.to-cart[data-item=' + item + ']').closest('.button_block').addClass('wide');
                        // th.parent().find('.in-cart').show();
                        $('.in-cart[data-item=' + item + ']').show();

                        addBasketCounter(item);
                        $('.wish_item[data-item=' + item + ']').removeClass("added");
                        $('.wish_item[data-item=' + item + ']').find(".value").show();
                        $('.wish_item[data-item=' + item + ']').find(".value.added").hide();

                        if ($("#ajax_basket").length)
                            reloadTopBasket('add', $('#ajax_basket'), 200, 5000, 'Y');

                        if ($("#basket_line .basket_fly").length) {
                            if (th.closest('.fast_view_frame').length || window.matchMedia('(max-width: 767px)').matches || $("#basket_line .basket_fly.loaded").length)
                                basketFly('open', 'N');
                            else
                                basketFly('open');
                        }

                    } else {
                        showBasketError(BX.message(data.MESSAGE) + ' <br/>' + data.MESSAGE_EXT);
                    }
                } else {
                    showBasketError(BX.message("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR"));
                }
            } else {
                // th.hide();
                $('.to-cart[data-item=' + item + ']').hide();
                $('.to-cart[data-item=' + item + ']').closest('.counter_wrapp').find('.counter_block').hide();
                $('.to-cart[data-item=' + item + ']').parents('tr').find('.counter_block_wr .counter_block').hide();
                $('.to-cart[data-item=' + item + ']').closest('.button_block').addClass('wide');
                // th.parent().find('.in-cart').show();
                $('.in-cart[data-item=' + item + ']').show();

                addBasketCounter(item);
                $('.wish_item[data-item=' + item + ']').removeClass("added");
                $('.wish_item[data-item=' + item + ']').find(".value").show();
                $('.wish_item[data-item=' + item + ']').find(".value.added").hide();

                if ($("#ajax_basket").length)
                    reloadTopBasket('add', $('#ajax_basket'), 200, 5000, 'Y');

                if ($("#basket_line .basket_fly").length) {
                    if (th.closest('.fast_view_frame').length || window.matchMedia('(max-width: 767px)').matches || $("#basket_line .basket_fly.loaded").length)
                        basketFly('open', 'N');
                    else
                        basketFly('open');
                }
            }
        }
    })
}
function calculatePrice() {
    var result = {
        'price': 0,
        'old': 0,
    };
    $('.set-composition_base .set-composition_row').each(function (index, el) {
        result.price += parseFloat($(el).data('price'));
        result.old += parseFloat($(el).data('old-price'));
    });
    result.base = {
        price: result.price,
        old: result.old,
    };
    $('.set-composition_accesories .set-composition_row').each(function (index, el) {
        if ($(el).find('.set-composition_checkbox input').prop('checked')) {
            result.price += parseFloat($(el).data('price'));
            result.old += parseFloat($(el).data('old-price'));
        }
    });
    return result;
}
function showPrice(price) {
    var html = '<div class="price_matrix_block"><div class="price_matrix_wrapper "><div class="price" data-currency="RUB" data-value="'
        + price.price + '"><span><span class="values_wrapper"><span class="price_value">'
        + formatNumber(price.price) + '</span><span class="price_currency"> руб.</span></span>'
        + '<span class="price_measure">/шт</span></span></div>';
    if (price.price < price.old) {
        html += '<div class="price discount" data-currency="RUB" data-value="'
            + price.old + '"><span class="values_wrapper"><span class="price_value">'
            + formatNumber(price.old) + '</span><span class="price_currency"> руб.</span></span></div>';
        html += '<div class="sale_block"><span class="title">Экономия</span><div class="text">'
            + '<span class="values_wrapper" data-currency="RUB" data-value="'
            + (price.old-price.price) + '"><span class="price_value">' +
            + formatNumber(price.old-price.price)
            + '</span><span class="price_currency">руб.</span></span></div>';
    }
    html += '</div></div>';
    $('.prices_block .cost.prices').html(html);
}
function formatNumber(n) {
    return n.toLocaleString('ru', {"minimumFractionDigits": 2})
        .replace(',', '.')
        .replace(' ', '&nbsp;');
}
