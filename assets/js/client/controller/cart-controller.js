(function(appMonami) {
    'use strict';

    appMonami.controller('CartCtrl', ['$http',
        function($http) {
            let self = this;
            this.products = [];
            this.maxCount = Number.MAX_SAFE_INTEGER;
            this.countPages = Number.MAX_SAFE_INTEGER;
            this.products = [];
            this.filters = {
                count: 16,
                sort: "cost",
                page: 1
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
                    self.products = response.data.products;
                    self.countPages = response.data.countPages;
                }, function errorCallback(error) {
                    console.error(error);
                });
            }

            function toPage(page) {
                if(page < 1 || page > this.countPages){
                    return false;
                }

                this.filters.page = page;

                updateProducts();
            }

            this.toPage = toPage;
            this.updateProducts = updateProducts;
            this.init = init;
        }]);
})(window.appMonami);