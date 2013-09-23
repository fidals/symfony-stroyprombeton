var Cart = {

    productsCount: 0,

    cash: 0,

    url: {
        cart: '/metallokonstruktsii/cart/',
        edit:   '/metallokonstruktsii/cart/edit/',
        add:    '/metallokonstruktsii/cart/add/',
        remove: '/metallokonstruktsii/cart/remove/',
        clean:  '/metallokonstruktsii/cart/clean/'
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
        changeCount: function(count) {
            Cart.productsCount = count;
            if(count != 0) {
                $(Cart.part.content).html('Товаров в корзине: <b>' + count + '</b>');
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
                Cart.view.changeCount(json.count);
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
                Cart.view.changeCount(json.count)
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
                Cart.view.changeCount(json.count);
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
    }
}