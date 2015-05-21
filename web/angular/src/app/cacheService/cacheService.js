(function(app) {

    app.factory('CacheService', function () {
        var cachedData = {},
            storageKey = 'mpk-stations',
            storageKeyDate = 'mpk-stations-date';
        
        return {
            bind: function(data){
                cachedData = data;
            },
            getStations: function(){
                var saved = JSON.parse(localStorage.getItem(storageKey));
                if (!!saved && (cachedData.stations.length === 0 || new Date(localStorage.getItem(storageKeyDate)).getDate() === new Date().getDate())){
                    cachedData.stations = saved;
                    return true;
                }
                return false;
            },
            getStation: function(id){
                for (var i = 0; i < cachedData.stations.length; i++) {
                    if (cachedData.stations[i].id === id) {
                        if (!cachedData.stations[i].details || new Date(cachedData.stations[i].details.time).getMinutes() !== new Date().getMinutes()){
                            return false;
                        }
                        cachedData.station = cachedData.stations[i].details;
                        return true;
                    }
                }
                return false;
            },
            setStations: function(){
                localStorage.setItem(storageKey, JSON.stringify(cachedData.stations));
                localStorage.setItem(storageKeyDate, new Date());
            },
            setStation: function(id){
                for (var i = 0; i < cachedData.stations.length; i++) {
                    if (cachedData.stations[i].id === id) {
                        cachedData.stations[i].details = cachedData.station;
                        break;
                    }
                }
            }
        };
    });

}(angular.module("mpk.cacheService", [])));