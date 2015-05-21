(function(app) {

    app.config(function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/');
    });

    app.run(function () {});

    app.controller('AppController', function ($scope) {
    });

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
