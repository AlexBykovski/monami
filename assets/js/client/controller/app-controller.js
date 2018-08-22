(function(appMonami) {
    'use strict';

    appMonami.controller('AppCtrl', ['CartService',
        function(CartService) {
            this.CartService = CartService;
        }]);
})(window.appMonami);