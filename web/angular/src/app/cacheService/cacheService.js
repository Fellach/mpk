(function(app) {

    app.factory('CacheService', function () {
        var cachedData = {};
        
        return {
            bind: function(data){
                cachedData = data;
            },
            getStations: function(){
                return cachedData.stations.length > 0;
            },
            getStation: function(id){
                return false;
            },
            setStations: function(){
                
            },
            setStation: function(){
                
            }
        };
    });

}(angular.module("mpk.cacheService", [])));