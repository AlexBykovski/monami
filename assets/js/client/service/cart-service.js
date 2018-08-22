(function(appMonami) {
    'use strict';

    appMonami.factory('CartService', ['$http',
        function($http) {
        let cart = {
            "products": {},
            "sum": 0,
            "sumDiscounted": 0,
            "discount": 0,
        };

        return {
            getCart: function(){
                return cart;
            },
            setCart: function(cartS){
                if(!cartS){
                    return false;
                }

                cart = typeof cartS === "string" ? angular.fromJson(cartS) : cartS;

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
                }, function errorCallback(error) {
                    console.error(error);
                });
            },
            removeFromCart: function(idProduct) {
                let self = this;
                console.log(idProduct);

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