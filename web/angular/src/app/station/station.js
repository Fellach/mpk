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

    app.controller('StationController', function ($scope, $stateParams, $interval, ApiService) {
        
        var init = function() {
            $scope.model = $scope.$parent.model;
            
            ApiService.getStation($stateParams.id);
            
            $interval(function(){
                if (new Date($scope.model.station.time).getMinutes() !== new Date().getMinutes()) {
                    ApiService.getStation($stateParams.id);
                }
            }, 1000);
        };

        init();
    });

}(angular.module("mpk.station", [
    'ui.router'
])));