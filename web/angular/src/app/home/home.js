(function (app) {

    app.config(function ($stateProvider) {
        $stateProvider.state('home', {
            url: '/',
            views: {
                "main": {
                    controller: 'HomeController',
                    templateUrl: 'home/home.tpl.html'
                }
            }
        });
    });

    app.controller('HomeController', function ($scope, ApiService) {

        var init = function () {
            $scope.model = ApiService.createModel();
            $scope.list = list();

            ApiService.getStations();
        };

        init();
    });

    function list() {
        return {
            closeOthers: true,
            items: [],
            
            onChanged: function () {
                if (this.search.length > 0) {
                    this.closeOthers = false;
                    for (var k in this.items) {
                        this.items[k].isOpen = true;
                    }
                } else {
                    this.reset();
                }
            },
            
            reset: function () {
                delete this.search;
                this.closeOthers = true;
                for (var k in this.items) {
                    this.items[k].isOpen = false;
                }
            }
        };
    }

}(angular.module("mpk.home", [
    'ui.router'
])));
