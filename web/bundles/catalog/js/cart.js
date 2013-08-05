var Cart = {

    productsCount: 0,

    cash: 0,

    url: {
        cart: '/catalog/cart/',

        edit:   '/catalog/cart/edit/',
        add:    '/catalog/cart/add/',
        remove: '/catalog/cart/remove/',
        clean:  '/catalog/cart/clean/'
    },

    part: {
        content: '.cart-content',
        actions: '.cart-actions',
        counter: '.cart-products-cnt',
        json:    '.order-json'
    },

    service: {
        foreach: function(jsonObj, callback) {
            for(var key in jsonObj){
                if(jsonObj.hasOwnProperty(key)){
                    callback(key, jsonObj[key])
                }
            }
        }
    },

    view: {
        changeCount: function(count, cash) {
            Cart.productsCount = count;
            Cart.cash = cash;
            if(count != 0) {
                $(Cart.part.content).html('Товаров в корзине: <b>' + count + '</b><br>(<b id="cart-cash">' + cash + '</b> руб.)');
                $(Cart.part.actions).css({visibility: 'visible'});
            } else {
                $(Cart.part.content).html('Корзина<br>пуста');
                $(Cart.part.actions).css({visibility: 'hidden'});
            }
        },
        clean: function() {
            $(Cart.part.content).html('Корзина пуста');
            $(Cart.part.actions).css({visibility: 'hidden'});
        },
        remove: function(el) {
            $(el).remove();
        }
    },

    edit: function(productId, count) {
        $.post(
            Cart.url.edit,
            {
                productId: productId,
                count: count
            },
        function(json) {
                Cart.view.changeCount(json.count, json.cash);
            }, 'json'
        )
    },

    add: function(productId, count) {
        $.post(
            Cart.url.add,
            {
                productId: productId,
                count: count
            },
            function(json) {
                Cart.view.changeCount(json.count, json.cash)
            }, 'json'
        )
    },

    remove: function(el, productId) {
        $.post(
            Cart.url.remove,
            {
                productId: productId
            },
            function(json) {
                Cart.view.changeCount(json.count, json.cash);
                Cart.view.remove(el);
            }, 'json'
        )
    },

    clean: function() {
        $.post(
            Cart.url.clean,
            {},
            function(json) {
                Cart.view.clean();
            }, 'json'
        )
    },

    getYandexMetrika: function() {
        var productRows = $('.order-products').find('.product-order-row');
        var products = [];
        productRows.each(function(key, value){
            products.push({
                "id"      : $(value).attr('product-id'),
                "name"    : $(value).attr('product-name'),
                "price"   : $(value).attr('product-price'),
                "quantity": $(value).find('.rest-container > input').val()});
        });

        var yaParams = {
            order_id: new Date().getTime(),
            order_price: $('#cart-cash').html(),
            currency: "RUR",
            exchange_rate: 1,
            goods: products
        }
        console.log(yaParams);
    }

//    var yaParams = {
//        order_id: "12345",
//        order_price: 123.45,
//        currency: "RUR",
//        exchange_rate: 1,
//        goods:
//            [
//                {
//                    id: "1",
//                    name: "наименование товара",
//                    price: 100,
//                    quantity: 1
//                }
//            ]
//    };

}