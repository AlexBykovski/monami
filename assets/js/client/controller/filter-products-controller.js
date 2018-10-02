(function(appMonami) {
    'use strict';

    appMonami.controller('FilterProductCtrl', ['$http',
        function($http) {
        let self = this;
        let idGroup = "";
        this.products = [];
        this.maxCount = Number.MAX_SAFE_INTEGER;
        this.countPages = Number.MAX_SAFE_INTEGER;
        this.products = [];
        this.filters = {
            sort: "cost",
            count: 16,
            text: "",
            page: 1
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
                self.products = response.data.products;
                self.countPages = response.data.countPages;
            }, function errorCallback(error) {
                console.error(error);
            });
        }

        function toPage(page) {
            console.log(page);
            if(page < 1 || page > this.countPages){
                return false;
            }

            this.filters.page = page;

            updateProducts();
        }

        this.updateProducts = updateProducts;
        this.toPage = toPage;
        this.init = init;
        }]);
})(window.appMonami);