(function(app) {

    app.factory('ApiService', function ($http, CacheService) {
        
        var baseUrl = 'http://mpk.dev/app_dev.php/api/',
            data = { stations: [], station: {} },
            
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
                    .get(baseUrl + 'stations/' + id)
                    .success(function(response){
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
            getStation: function(id){
                if (!CacheService.getStation(id)) {
                    getStation(id).then(function(){
                        CacheService.setStation(id);
                    });
                }
            },
            createModel: function(){ 
                return data;
            }
        };
    });

}(angular.module("mpk.apiService", [])));