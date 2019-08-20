console.log("12312");

var rootScope;
var navApp = angular.module("navApp", []);
navApp.controller("mainNavCtrl", function($scope, $rootScope, $http, $filter) {
  $scope.init = function() {
    $scope.menu_list = {
      //   首页
      index: false,
      index_overview: false,
      index_BigScreen: false,
      // 情报
      intelligence: false,
      intelligence_query: false,
      intelligence_extract: false,
      intelligence_share: false,
      intelligence_sourceAdmin: false,
      intelligence_apt: false,
      // 资产
      assets: false,
      assets_admin: false,
      assets_risk: false,
      // 预警
      warning: false,
      warning_threat: false,
      warning_loophole: false,
      warning_drakNet: false,
      // 报表
      report: false,
      report_creat: false,
      report_send: false,
      // 设置
      set: false,
      set_sys: false,
      set_notice: false,
      set_loopholeRelation: false,
      set_admin: false,
      set_user: false,
      set_log: false,
      api: false
    };
    $scope.get_menu();
  };
  $scope.get_menu = function() {
    $http.get("/site/menu").then(
      function success(data) {
        if (data.data.status == "success") {
          angular.forEach(data.data.data, function(item) {
            // 首页
            if (item.permissions_id == "1") {
              $scope.menu_list.index = true;
              angular.forEach(item.child_menu, function(child) {
                if (child.permissions_id == "2") {
                  $scope.menu_list.index_overview = true;
                }
                if (child.permissions_id == "14") {
                  $scope.menu_list.index_BigScreen = true;
                }
              });
            }
            // 情报
            if (item.permissions_id == "15") {
              $scope.menu_list.intelligence = true;
              angular.forEach(item.child_menu, function(child) {
                if (child.permissions_id == "16") {
                  $scope.menu_list.intelligence_query = true;
                }
                if (child.permissions_id == "24") {
                  $scope.menu_list.intelligence_extract = true;
                }
                if (child.permissions_id == "29") {
                  $scope.menu_list.intelligence_share = true;
                }
                if (child.permissions_id == "46") {
                  $scope.menu_list.intelligence_sourceAdmin = true;
                }
                if (child.permissions_id == "50") {
                  $scope.menu_list.intelligence_apt = true;
                }
              });
            }
            // 资产
            if (item.permissions_id == "54") {
              $scope.menu_list.assets = true;
              angular.forEach(item.child_menu, function(child) {
                if (child.permissions_id == "55") {
                  $scope.menu_list.assets_admin = true;
                }
                if (child.permissions_id == "72") {
                  $scope.menu_list.assets_risk = true;
                }
              });
            }
            // 预警
            if (item.permissions_id == "77") {
              $scope.menu_list.warning = true;
              angular.forEach(item.child_menu, function(child) {
                if (child.permissions_id == "78") {
                  $scope.menu_list.warning_threat = true;
                }
                if (child.permissions_id == "85") {
                  $scope.menu_list.warning_loophole = true;
                }
                if (child.permissions_id == "90") {
                  $scope.menu_list.warning_drakNet = true;
                }
              });
            }
            // 报表
            if (item.permissions_id == "127") {
              $scope.menu_list.report = true;
              angular.forEach(item.child_menu, function(child) {
                if (child.permissions_id == "128") {
                  $scope.menu_list.report_creat = true;
                }
                if (child.permissions_id == "129") {
                  $scope.menu_list.report_send = true;
                }
              });
            }
            // 设置
            if (item.permissions_id == "93") {
              $scope.menu_list.set = true;
              angular.forEach(item.child_menu, function(child) {
                if (child.permissions_id == "94") {
                  $scope.menu_list.set_sys = true;
                }
                if (child.permissions_id == "97") {
                  $scope.menu_list.set_notice = true;
                }
                if (child.permissions_id == "104") {
                  $scope.menu_list.set_loopholeRelation = true;
                }
                if (child.permissions_id == "130") {
                  $scope.menu_list.set_admin = true;
                }
                if (child.permissions_id == "110") {
                  $scope.menu_list.set_user = true;
                }
                if (child.permissions_id == "126") {
                  $scope.menu_list.set_log = true;
                }
                if (child.permissions_id == "151") {
                  $scope.menu_list.api = true;
                }
              });
            }
          });
        }
      },
      function err(rsp) {}
    );
  };

  $scope.init();
});
angular.element(document).ready(function() {
  angular.bootstrap(document.getElementById("navApp"), ["navApp"]);
});
