var myApp = angular.module("myApp", []);
myApp.controller("RiskyDetails", function($scope, $http, $filter) {
  $scope.init = function() {
    console.log(window.location.search.split("=")[1]);
    $scope.alerts_list();
    $scope.loopholes_list();
    $scope.status_str = [
      {
        css: "success",
        label: "新预警"
      },
      {
        css: "danger",
        label: "处置中"
      },
      {
        css: "default",
        label: "已解决"
      }
    ];
  };
  // 漏洞预警列表
  $scope.loopholes_list = function(page) {
    $http({
      method: "get",
      url: "/assets/asset-loopholes",
      params: {
        asset_ip: window.location.search.split("=")[1],
        page: page,
        rows: 10
      }
    }).then(
      function(data) {
        console.log(data.data);
        $scope.loopholes_list_data = data.data;
        angular.forEach($scope.loopholes_list_data.data, function(item) {
          switch (item.risk_status) {
            case "UNREPAIRED":
              item.risk_status_cn = "未修复";
              break;
            case "REPAIRED":
              item.risk_status_cn = "已修复";
              break;
            case "DETECTING":
              item.risk_status_cn = "检测中";
              break;
            default:
              break;
          }
        });
      },
      function() {}
    );
  };
  // 威胁预警列表
  $scope.alerts_list = function(page) {
    $http({
      method: "get",
      url: "/assets/asset-alerts",
      params: {
        asset_ip: window.location.search.split("=")[1],
        // asset_ip: "",
        page: page,
        rows: 10
      }
    }).then(
      function(data) {
        console.log(data.data);
        $scope.alerts_list_data = data.data;
      },
      function() {}
    );
  };

  $scope.alert_detail = function(item) {
    window.location.href = "/alert/detail?id=" + item.id;
  };
  $scope.loop_detail = function(item) {
    window.location.href = "/alert/loophole-detail";
    sessionStorage.setItem("loop_detail", JSON.stringify(item));
  };
  $scope.showLength = function(str, length) {
    if (!length) {
      length = 60;
    }
    return str.substr(0, length) + "...";
  };
  $scope.init();
});
