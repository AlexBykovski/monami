(function(appMonami) {
    'use strict';

    appMonami.controller('CartCtrl', ['$http',
        function($http) {
            let self = this;
            this.products = [];
            this.maxCount = Number.MAX_SAFE_INTEGER;
            this.products = [];
            this.filters = {
                count: 16
            };

            function init() {
                updateProducts();
            }

            function updateProducts() {
                $http({
                    method: 'GET',
                    url: '/cart/filter/',
                    params: self.filters
                }).then(function successCallback(response) {
                    self.products = response.data;

                    //console.log(response);
                }, function errorCallback(error) {
                    console.error(error);
                });
            }

            this.updateProducts = updateProducts;
            this.init = init;
        }]);
})(window.appMonami);