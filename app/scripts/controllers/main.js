'use strict';

angular.module('assignmentRouterApp')
    .controller('MainCtrl', function ($scope, UserService) {
        $scope.role = UserService.role;
        $scope.user = UserService.user;

        $scope.logDownload = function() {
            UserService.logDownload();
        };
  });
