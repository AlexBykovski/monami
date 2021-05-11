(function (appMonami) {
    'use strict';

    appMonami.factory('CartService', [ '$http', '$cookies',
        function ($http, $cookies) {
            let cart = {
                "products": {},
                "sum": 0,
                "sumDiscounted": 0,
                "discount": 0,
                'username': '',
                'phone': '',
                'email': '',
                'address': '',
                'info': '',
            };

            let isGuest = false;

            return {
                init: function (cartS, isGuestS) {
                    isGuestS = !!parseInt(isGuestS);

                    if (isGuestS) {
                        isGuest = true;
                        let cartCookies = $cookies.getObject("guest-cart");

                        if (!cartCookies) {
                            cartCookies = cart;

                            let date = new Date();

                            date.setMonth(date.getMonth() + 1);
                            $cookies.putObject("guest-cart", cart, {
                                "path": "/",
                                "expires":  date
                            });
                        }

                        cart = cartCookies;

                        return false;
                    } else {
                        $cookies.remove("guest-cart");
                    }

                    if (!cartS) {
                        return false;
                    }

                    cart = typeof cartS === "string" ? angular.fromJson(cartS) : cartS;
                },
                getCart: function () {
                    return cart;
                },
                setCart: function (newCart) {
                    if (!newCart) {
                        return false;
                    }

                    cart = typeof newCart === "string" ? angular.fromJson(newCart) : newCart;

                    if (isGuest) {
                        let date = new Date();
                        date.setMonth(date.getMonth() + 1);

                        $cookies.putObject("guest-cart", cart, {
                            "path": "/",
                            "expires":  date
                        });
                        console.log('here error5');
                    }
                },
                addItemsToCart: function (items) {
                    let self = this;

                    angular.forEach(items, function (value, key) {

                        self.addToCart(value.productId, value.count);
                    });
                },
                addToCart: function (idProduct, count) {
                    let self = this;

                    $http({
                        method: 'POST',
                        url: '/cart/add-product-to-cart',
                        data: {
                            "idProduct": idProduct,
                            "count": count,
                        }
                    }).then(function successCallback(response) {
                        self.setCart(response.data.cart);

                        $.notify({message: response.data.text},
                            {
                                placement: {
                                    from: "top",
                                    align: "center"
                                },
                                type: response.data.type,
                            });

                    }, function errorCallback(error) {
                        console.error(error);
                    });
                },
                removeFromCart: function (idProduct, count = false) {
                    let self = this;

                    $http({
                        method: 'POST',
                        url: '/cart/remove-product-from-cart',
                        data: {
                            "idProduct": idProduct,
                            "count": count,
                        }
                    }).then(function successCallback(response) {
                        self.setCart(response.data.cart);
                    }, function errorCallback(error) {
                        console.error(error);
                    });
                },
                changeCountInCart: function (idProduct, count) {
                    let self = this;

                    $http({
                        method: 'POST',
                        url: '/cart/change-count-product-in-cart',
                        data: {
                            "idProduct": idProduct,
                            "count": count,
                        }
                    }).then(function successCallback(response) {
                        self.setCart(response.data.cart);
                    }, function errorCallback(error) {
                        console.error(error);
                    });
                },
                usePromocode: function (code) {
                    if (!code) {
                        return false;
                    }

                    let self = this;

                    $http({
                        method: 'POST',
                        url: '/cart/use-promocode',
                        data: {
                            "code": code
                        }
                    }).then(function successCallback(response) {
                        self.setCart(response.data.cart);
                        $.notify({message: response.data.message},
                            {
                                placement: {
                                    from: "top",
                                    align: "center"
                                },

                            });
                    }, function errorCallback(error) {
                        console.error(error);
                    });
                },
                saveCart: function (data) {
                    let self = this;

                    $http({
                        method: 'POST',
                        url: '/cart/save-cart',
                        data: {data}
                    }).then(function successCallback(response) {
                        self.setCart();
                        console.log('asdasd');
                        $.notify({message: 'Ваш заказ оформлен, менеджер свяжется с вами в ближайшее время.'},
                            {
                                placement: {
                                    from: "top",
                                    align: "center"
                                },

                            });
                        setTimeout(function () {
                            location.reload()
                        }, 3000);
                    }, function errorCallback(error) {
                        console.error(error);
                    });
                },
                exportToXls: function () {
                    let self = this;

                    $http({
                        method: 'POST',
                        url: '/cart/export-to-xls',
                        data: {}
                    }).then(function successCallback(response) {
                        const url = window.URL.createObjectURL(response.data.file);
                        window.open(url);
                    }, function errorCallback(error) {
                        console.error(error);
                    });
                },
                getCountProducts() {
                    return Object.keys(cart.products).length;
                }
            };
        } ]);

})(window.appMonami);