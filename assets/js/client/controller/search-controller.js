(function(appMonami) {
    'use strict';

    appMonami.controller('SearchCtrl', ['$http', '$window',
        function($http, $window) {
            let self = this;
            this.text = "";
            this.products = [];

            function search() {
                $http({
                    method: 'GET',
                    url: '/catalog/search/products',
                    params: {"text": self.text}
                }).then(function successCallback(response) {
                    self.products = response.data;
                }, function errorCallback(error) {
                    console.error(error);
                });
            }

            function showResults(text) {
                $window.location.href = "/catalog/show/search/results?text=" + text;
            }

            this.search = search;
            this.showResults = showResults;
        }]);
})(window.appMonami);