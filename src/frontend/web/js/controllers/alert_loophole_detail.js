var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("AlertLoopholeDetailCtrl", function(
  $scope,
  $http,
  $filter,
  $sce
) {
  $scope.init = function() {
    $scope.detail_info = JSON.parse(sessionStorage.getItem("loop_detail"));
    console.log($scope.detail_info);
    $scope.detail_info_detail = JSON.parse($scope.detail_info.detail);
    console.log($scope.detail_info_detail);
  };
  $scope.init();
});
