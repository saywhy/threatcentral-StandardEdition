var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("AlertDarknetDetailCtrl", function(
  $scope,
  $http,
  $filter,
  $sce
) {
  $scope.init = function() {
    $scope.detail = JSON.parse(decodeURI(window.location.search.split("=")[1]));
    console.log($scope.detail);
  };

  $scope.init();
});
