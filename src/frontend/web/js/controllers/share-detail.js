var app = angular.module("myApp", []);
var rootScope;
app.controller("shareDeatilCtrl", function($scope, $http, $filter) {
  rootScope = $scope;
  $scope.init = function() {
    console.log(share);
    $scope.describe = true;
    $scope.share_detail = share;
  };
  $scope.describe_if = function(name) {
    if (name == "down") {
      $scope.describe = true;
    }
    if (name == "up") {
      $scope.describe = false;
    }
  };

  $scope.down_load = function() {
    if ($scope.share_detail.filePath == "") {
      zeroModal.error("没有附件可下载");
    } else {
      var elink = document.createElement("a");
      elink.download = $scope.share_detail.filePath.split("/")[3];
      elink.style.display = "none";
      elink.href = "/static" + $scope.share_detail.filePath;
      document.body.appendChild(elink);
      elink.click();
      document.body.removeChild(elink);
    }
  };
  $scope.init();
});
