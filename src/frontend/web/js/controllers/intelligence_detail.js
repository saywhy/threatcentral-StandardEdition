var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("IntelligenceDetailCtrl", function(
  $scope,
  $http,
  $filter,
  $sce
) {
  $scope.init = function() {
    $scope.detail = JSON.parse(sessionStorage.getItem("intelligence_detail"));
    console.log($scope.detail);
    $scope.html_content = $scope.detail.content;
  };

  $scope.init();
});
