(function(app) {

    app.config(function ($stateProvider) {
        $stateProvider.state('home', {
            url: '/',
            views: {
                "main": {
                    controller: 'HomeController',
                    templateUrl: 'home/home.tpl.html'
                }
            },
            data:{ pageTitle: 'Home' }
        });
    });

    app.controller('HomeController', function ($scope, ApiService, $state) {
        
        var init = function() {
            $scope.model = ApiService.createModel();
            
            ApiService.getStations();
        };
        
        init();
    });

}(angular.module("mpk.home", [
    'ui.router'
])));