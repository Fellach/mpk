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
            console.log('wtf');
            $scope.model.station = null;
            $scope.model = $scope.$parent.model;
            
            ApiService.getStation($stateParams.id);
            
            var interval = $interval(function(){
                console.log(new Date($scope.model.station.time).getMinutes() );
                console.log(new Date().getMinutes());
                if (new Date($scope.model.station.time).getMinutes() !== new Date().getMinutes()) {
                    console.log('dwnl');
                    ApiService.getStation($stateParams.id);
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