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
            getStation: function(id, group){
                for (var j = 0; j < cachedData.stations[group].length; j++) {
                    if (cachedData.stations[group][j].id === id) {
                        if (!cachedData.stations[group][j].details || new Date(cachedData.stations[group][j].details.time).getMinutes() !== new Date().getMinutes()){
                            return false;
                        }
                        cachedData.station = cachedData.stations[group][j].details;
                        return true;
                    }
                }                
                return false;
            },
            setStations: function(){
                localStorage.setItem(storageKey, JSON.stringify(cachedData.stations));
                localStorage.setItem(storageKeyDate, new Date());
            },
            setStation: function(id, group){
                for (var j = 0; j < cachedData.stations[group].length; j++) {
                    if (cachedData.stations[group][j].id === id) {
                        cachedData.stations[group][j].details = cachedData.station;
                        return;
                    }
                }                
            }
        };
    });

}(angular.module("mpk.cacheService", [])));