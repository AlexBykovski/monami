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
            count: 16,
            text: ""
        };

        function init(idGroupS, textS) {
            idGroup = idGroupS;

            if(textS){
                self.filters.text = textS;
            }

            updateProducts();
        }

        function updateProducts() {
            let url = '/catalog/' + idGroup + '/filter/';

            if(self.filters.text){
                url = '/catalog/search/results';
            }

            $http({
                method: 'GET',
                url: url,
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