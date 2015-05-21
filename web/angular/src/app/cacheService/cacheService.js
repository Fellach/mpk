(function(app) {

    app.factory('CacheService', function () {
        var cachedData = {};
        
        return {
            bind: function(data){
                cachedData = data;
            },
            getStations: function(){
                return false;
            },
            getStation: function(id){
                return false;
            }
        };
    });

}(angular.module("mpk.cacheService", [])));