var rootScope;
var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("loopholeDetailCtrl", function(
  $scope,
  $rootScope,
  $http,
  $filter,
  $sce
) {
  rootScope = $scope;
  $scope.init = function() {
    if (localStorage.getItem("loop_detail_data")) {
      $scope.html_content = localStorage.getItem("loop_detail_data");
    }
  };

  $scope.init();
});
