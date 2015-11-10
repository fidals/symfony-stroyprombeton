/**
 * Инстанс Рактива для дропдаун-корзины. Играет роль master'а в отношениях с Рактивом для страницы заказа.
 * В этом инстансе хранятся данные о продуктах в заказе.
 * @type {Ractive}
 */
var ractiveDropdown = new Ractive({
    el: '#cart-dropdown',
    template: '#template',

    data: {
        products: null,

        format: function (name) {
            if (name.length > 45) {
                return name.substring(0, 45);
            }

            return name;
        },

        getTotalPrice: function() {
            var price = this.get('products').reduce(function(sum, currentProduct) {
                return sum + currentProduct.count * currentProduct.price;
            }, 0);

            var priceOnDemand = this.get('products').filter(function(currentProduct) {
                return currentProduct.price == 0 || currentProduct.price == null;
            }, 0);

            if (price == 0) {
                return "По запросу";
            } else if (priceOnDemand.length) {
                return price + " руб. + По запросу";
            }

            return price + " руб.";
        }
    },



    lazy: true
});


/**
 * Метод для апдейта данных на беке.
 * При измении инпута updateCount мы делаем асинхронный запрос на бек с соотв. значением id продукта и нового количества в корзине.
 */
ractiveDropdown.on('updateCount', function (event) {
    var product = this.get(event.keypath);

    Cart.update(product.id, product.count);
});

/**
 * Объект Корзины с CRUD-методами, работающими по AJAX-реквестам к бэку.
 * @type {{url: {add: string, remove: string, clean: string, fetch: string}, view: {container: string, update: Function}, add: Function, remove: Function, clean: Function, fetch: Function}}
 */
var Cart = {
    url: {
        add: '/gbi/cart/add/',
        remove: '/gbi/cart/remove/',
        clean: '/gbi/cart/clean/',
        fetch: '/gbi/cart/fetch/',
        update: '/gbi/cart/update/'
    },

    view: {
        container: '.menu-basket',

        update: function (products) {
            ractiveDropdown.set({products: products});
        }
    },

    add: function (id, quantity) {
        $.post(
            Cart.url.add,
            {
                id: id,
                quantity: quantity
            },
            Cart.view.update,
            'json'
        );
        return false;
    },

    remove: function (id, quantity) {
        $.post(
            Cart.url.remove,
            {
                id: id,
                quantity: quantity
            },
            Cart.view.update,
            'json'
        );
        return false;
    },

    clean: function () {
        $.post(
            Cart.url.clean,
            {},
            Cart.view.update,
            'json'
        );
        return false;
    },

    /**
     * Функция для обновления количества заданного товара в заказе.
     *
     * @param id
     * @param count
     */
    update: function (id, count) {
        $.post(
            Cart.url.update,
            {
                id: id,
                count: count
            },
            Cart.view.update,
            'json'
        )
    },

    /**
     * Функция для получения информации о товарах в корзине. Полезна для правильного отражения дропдауна при загрузке.
     *
     * @returns {boolean}
     */
    fetch: function () {
        $.post(
            Cart.url.fetch,
            {},
            Cart.view.update,
            'json'
        );
        return false;
    }
};

$(function () {

    /**
     * Инициализация корзины при загрузке страницы.
     */
    Cart.fetch();
    /*
     * Тултипы
     */
    var $basketWrapper = $('#sticky-wrapper'),
        $basketItemsContainer = $('.mbasket-items');

    function darkTooltipInit() {
        $('#butEmptyCart')
            .darkTooltip({
                trigger: 'click',
                animation: 'fadeIn',
                gravity: 'south',
                confirm: true,
                yes: 'Да',
                no: 'Нет',
                theme: 'light',
                onYes: function () {
                    Cart.clean();
                },
                onNo: function () {
                    $basketItemsContainer.removeClass('active');
                },
                onClose: function () {
                    $basketItemsContainer.removeClass("active");
                }
            })
            .on('click', function () {
                $(this).closest('.mbasket-items').addClass('active');
            });
    }

    darkTooltipInit();

    $basketWrapper.on('click', '.shk-del', function () {
        setTimeout(darkTooltipInit, 1000);
    });

    $('.add-basket')
        .darkTooltip({
            trigger: 'click',
            opacity: 0,
            gravity: 'south',
            theme: 'light'
        })
        .on('click', function () {
            var currentTooltip = $(this).attr('id');


            $('#darktooltip-' + currentTooltip)
                .css({
                    'display': 'block'
                })
                .stop().animate({
                    'opacity': 0.9
                }, 500)
            ;

            setTimeout(function () {
                $('#darktooltip-' + currentTooltip)
                    .stop().animate({
                        'opacity': 0
                    }, 500, function () {
                        $(this).css({
                            'display': 'none'
                        });
                    });
            }, 2000);

            setTimeout(darkTooltipInit, 1000);
        });
});