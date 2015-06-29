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
    
    
    app.filter('orderGroups', function(){
        return function(input) {
            
            var keys = Object.keys(input).sort(function(a, b) {
                return a.localeCompare(b);
            });
            
            var sorted = [];
            for (var i = 0; i < keys.length; i++) {
                sorted.push(input[keys[i]]);
                sorted[sorted.length - 1].id = keys[i];
            }
            return sorted;
        };
    });
    
    app.filter('orderPlNames', function(){
        return function(input) {
            return input.sort(function(a, b) {
                return a.name.localeCompare(b.name);
            });
        };
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
