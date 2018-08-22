(function(appMonami) {
    'use strict';

    appMonami.controller('FilterProductCtrl', ['$http',
        function($http) {
        let self = this;
        let idGroup = "";
        this.products = [];
        this.maxCount = Number.MAX_SAFE_INTEGER;
        this.products = [];
        this.filters = {
            sort: "cost",
            count: 16
        };

        function init(idGroupS) {
            idGroup = idGroupS;
            updateProducts();
        }

        function updateProducts() {
            $http({
                method: 'GET',
                url: '/catalog/' + idGroup + '/filter/',
                params: self.filters
            }).then(function successCallback(response) {
                self.products = response.data;
            }, function errorCallback(error) {
                console.error(error);
            });
        }

        this.updateProducts = updateProducts;
        this.init = init;
        }]);
})(window.appMonami);