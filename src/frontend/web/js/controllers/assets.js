var myApp = angular.module("myApp", []);
myApp.controller("myAssets", function($scope, $http, $filter) {
  $scope.init = function() {
    console.log("myAssets");
  };

  $scope.init();
});
