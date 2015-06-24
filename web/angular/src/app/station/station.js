(function(app) {

    app.config(['$stateProvider', function ($stateProvider) {
        $stateProvider.state('home.station', {
            url: 'station/:id/:name',
            views: {
                "station": {
                    controller: 'StationController',
                    templateUrl: 'station/station.tpl.html'
                }
            }
        });
    }]);
    
    app.controller('StationController', function ($scope, $stateParams, $interval, ApiService) {
        $scope.model.station = null;
        
        var init = function() {
            $scope.model = $scope.$parent.model;

            var interval = $interval(function refresh(){
                if (!$scope.model.station || new Date($scope.model.station.time).getMinutes() !== new Date().getMinutes()) {
                    ApiService.getStation($stateParams.id, $stateParams.name[0]);
                }
                return refresh;
            }(), 1000);
            
            $scope.$on('$destroy', function () { 
                $interval.cancel(interval); 
            });
        };

        init();
    });

}(angular.module("mpk.station", [
    'ui.router',
    'ui.bootstrap',
    'ngAnimate',
    'sticky'
])));