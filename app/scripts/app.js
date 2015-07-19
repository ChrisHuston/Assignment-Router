'use strict';

angular
  .module('assignmentRouterApp', [
    'ngRoute',
    'ngSanitize',
    'ngTouch', 'ngGrid', 'flow'
  ])
  .config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/main.html',
            controller: 'MainCtrl'
        })
      .when('/admin', {
        templateUrl: 'views/admin.html',
        controller: 'AdminCtrl'
      })
      .when('/users', {
        templateUrl: 'views/users.html',
        controller: 'UsersCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
