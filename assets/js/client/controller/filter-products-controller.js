(function (appMonami) {
    'use strict';

    appMonami.controller('FilterProductCtrl', [ '$http',
        function ($http) {
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
                page: 1,
                type: 'all',
            };

            function init(idGroupS, textS, type, page) {
                idGroup = idGroupS;
                let savedSort = sessionStorage.getItem('filtres') ? JSON.parse(sessionStorage.getItem('filtres')).sort : null;
                let savedCount = sessionStorage.getItem('filtres') ? JSON.parse(sessionStorage.getItem('filtres')).count : null;
                let savedLocation = sessionStorage.getItem('location') ? sessionStorage.getItem('location') : null;

                if (textS) {
                    self.filters.text = textS;
                }

                if (type) {
                    self.filters.type = type;
                }

                if (page) {
                    self.filters.page = parseInt(page);
                }

                if (savedSort && location.pathname === savedLocation) {
                    self.filters.sort = savedSort;
                }

                if (savedCount && location.pathname === savedLocation) {
                    self.filters.count = savedCount;
                }

                updateProducts();
            }

            function updateProducts() {
                let url = '/catalog/' + idGroup + '/filter/';

                if (self.filters.text) {
                    url = '/catalog/search/results';
                }

                sessionStorage.setItem('filtres', JSON.stringify(self.filters));
                sessionStorage.setItem('location', location.pathname);

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
                if (page < 1 || page > this.countPages) {
                    return false;
                }

                this.filters.page = page;

                if (this.filters.type && this.filters.type !== 'all') {
                    location.href = '/catalog/type/' + this.filters.type + '?page=' + page;
                } else {
                    location.href = '/catalog/' + idGroup + '?page=' + page;
                }
            }

            this.updateProducts = updateProducts;
            this.toPage = toPage;
            this.init = init;
        } ]);
})(window.appMonami);