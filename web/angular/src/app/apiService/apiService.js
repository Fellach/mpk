(function(app) {

    app.factory('ApiService', function ($http, $state, CacheService) {
        
        var data = { stations: [], station: {} },
            
            getStations = function(){
                return $http
                    .get(baseUrl + 'stations')
                    .success(function(response){
                        data.stations = response.stations;
                    })
                    .error(function(){
                        console.log('error');
                    });
                },
            getStation = function(id){
                return $http
                    .post(baseUrl + 'stations/' + id, {date: new Date()})
                    .success(function(response){
                        if (!response.station) {
                            $state.go('home');
                            return false;
                        }
                        data.station = response.station;
                    })
                    .error(function(){
                        console.log('error');
                    });
                };
        
        CacheService.bind(data);
        
        return {
            getStations: function(){
                if (!CacheService.getStations()) {
                    getStations().then(function(){
                        CacheService.setStations();
                    });
                }                
            },
            getStation: function(id, group){
                if (!CacheService.getStation(id, group)) {
                    getStation(id).then(function(){
                        CacheService.setStation(id, group);
                    });
                }
            },
            createModel: function(){ 
                return data;
            }
        };
    });

}(angular.module("mpk.apiService", [])));