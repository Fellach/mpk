(function(app) {

    app.config(function ($urlRouterProvider, $locationProvider) {
        $urlRouterProvider.otherwise('/');
        
        $locationProvider.html5Mode(true);
    });

    app.run(function () {});

}(angular.module("mpk", [
    'mpk.home',
    'mpk.about',
    'templates-app',
    'templates-common',
    'ui.router.state',
    'ui.router',
    'mpk.apiService',
    'mpk.cacheService',
    'mpk.station',
])));
