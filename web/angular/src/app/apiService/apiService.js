(function(app) {

    app.factory('ApiService', function ($http) {
        var baseUrl = 'http://mpk.dev/app_dev.php/api/',
            data = {};
        return {
            getStations: function(){
                $http
                    .get(baseUrl + 'get_stations')
                    .success(function(d){
                        data.stations = d.stations;
                    })
                    .error(function(){
                        console.log('error');
                    });
            },
            getStation: function(id){
                $http
                    .get(baseUrl + 'get_station/' + id)
                    .success(function(d){
                        data.station = d.station;
                    })
                    .error(function(){
                        console.log('error');
                    });
            },
            getData: function(){ 
                return data;
            }
        };
    });

}(angular.module("mpk.apiService", [])));