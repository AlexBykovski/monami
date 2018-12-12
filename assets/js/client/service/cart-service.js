(function(appMonami) {
    'use strict';

    appMonami.factory('CartService', ['$http', '$cookies',
        function($http, $cookies) {
        let cart = {
            "products": {},
            "sum": 0,
            "sumDiscounted": 0,
            "discount": 0,
        };

        let isGuest = false;

        return {
            init: function (cartS, isGuestS){
                isGuestS = !!parseInt(isGuestS);

                if(isGuestS){
                    isGuest = true;
                    let cartCookies = $cookies.getObject("guest-cart");

                    if(!cartCookies){
                        cartCookies = cart;
                        $cookies.putObject("guest-cart", cartCookies, {"path" : "/"});
                    }

                    cart = cartCookies;

                    return false;
                }
                else{
                    $cookies.remove("guest-cart");
                }

                if(!cartS){
                    return false;
                }

                cart = typeof cartS === "string" ? angular.fromJson(cartS) : cartS;
            },
            getCart: function(){
                return cart;
            },
            setCart: function(newCart){
                if(!newCart){
                    return false;
                }

                cart = typeof newCart === "string" ? angular.fromJson(newCart) : newCart;

                if(isGuest){
                    $cookies.putObject("guest-cart", cart, {"path" : "/"});
                }
            },
            addToCart: function(idProduct, count) {
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
            removeFromCart: function(idProduct) {
                let self = this;

                $http({
                    method: 'POST',
                    url: '/cart/remove-product-from-cart',
                    data: {
                        "idProduct": idProduct,
                    }
                }).then(function successCallback(response) {
                    self.setCart(response.data.cart);
                }, function errorCallback(error) {
                    console.error(error);
                });
            },
            changeCountInCart: function(idProduct, count) {
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
            usePromocode: function(code) {
                if(!code){
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
                }, function errorCallback(error) {
                    console.error(error);
                });
            },
            getCountProducts(){
                return Object.keys(cart.products).length;
            }
        };
        }]);

})(window.appMonami);