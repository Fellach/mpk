(function(app) {

    app.config(['$stateProvider', function ($stateProvider) {
        $stateProvider.state('home.station', {
            url: 'station/:group/:id',
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
        $scope.model.station = null;
        
        var init = function() {
            $scope.model = $scope.$parent.model;

            ApiService.getStation($stateParams.id, $stateParams.group);
            
            var interval = $interval(function(){
                if (new Date($scope.model.station.time).getMinutes() !== new Date().getMinutes()) {
                    ApiService.getStation($stateParams.id, $stateParams.group);
                }
            }, 1000);
            
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