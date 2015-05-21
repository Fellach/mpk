(function(app) {

    app.config(['$stateProvider', function ($stateProvider) {
        $stateProvider.state('home.station', {
            url: 'station/:id',
            views: {
                "station": {
                    controller: 'StationController',
                    templateUrl: 'station/station.tpl.html'
                }
            },
            data:{ pageTitle: 'Station' }
        });
    }]);

    app.controller('StationController', function ($scope, $stateParams, ApiService) {
        
        var init = function() {
            $scope.model = $scope.$parent.model;
            
            ApiService.getStation($stateParams.id);
        };

        init();
    });

}(angular.module("mpk.station", [
    'ui.router'
])));