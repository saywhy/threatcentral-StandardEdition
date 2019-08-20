var myApp = angular.module("myApp", []);
myApp.controller("myAssetsRisky", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.status_str = [
      {
        css: "success",
        label: "新预警"
      },
      {
        css: "danger",
        label: "未解决"
      },
      {
        css: "default",
        label: "已解决"
      }
    ];
    $scope.risk_data = {};
    $scope.search = {
      asset_ip: "",
      company: ""
    };
    $scope.assets_name_list_if = false;
    $scope.company_list_if = false;
    $scope.risk();
  };
  // 搜索
  $scope.risk = function(page) {
    $scope.company_list_if = false;
    $scope.assets_name_list_if = false;
    $http({
      method: "get",
      url: "/alert/risk-assets-statistics",
      params: {
        asset_ip: $scope.search.asset_ip,
        company: $scope.search.company,
        page: page,
        rows: 10
      }
    }).then(
      function(data) {
        $scope.risk_data = data.data.data;
        angular.forEach($scope.risk_data.data, function(item) {
          item.style = {
            width: item.sort + "%",
            borderRadius: "5px"
          };
          if (item.sort >= 90) {
            item.style.backgroundColor = "#FF5F5C";
          }
          if (item.sort >= 70 && item.sort < 90) {
            item.style.backgroundColor = "rgba(255,95,92,.8)";
          }
          if (item.sort >= 50 && item.sort < 70) {
            item.style.backgroundColor = "#E9AF38";
          }
          if (item.sort >= 30 && item.sort < 50) {
            item.style.backgroundColor = "#7ACE4C";
          }
          if (item.sort < 30) {
            item.style.backgroundColor = "rgba(122,206,76,.8)";
          }
        });
      },
      function() {}
    );
  };
  //   分组列表模糊搜索
  $scope.myKeyup_company_list = function(name) {
    $http({
      method: "get",
      url: "/assets/company-list",
      params: {
        company: name
      }
    }).then(
      function(data) {
        $scope.company_list = data.data.data;
      },
      function() {}
    );
  };
  $scope.get_company_list_focus = function() {
    $scope.myKeyup_company_list($scope.search.company);
    $scope.company_list_if = true;
    $scope.assets_name_list_if = false;
  };
  $scope.company_list_click = function(name) {
    $scope.search.company = name;
    $scope.company_list_if = false;
  };
  //  资产名称模糊搜索
  $scope.myKeyup_assets_name = function(name) {
    $http({
      method: "get",
      url: "/assets/assets-name",
      params: {
        asset_ip: name
      }
    }).then(
      function(data) {
        $scope.assets_name = data.data.data;
      },
      function() {}
    );
  };
  $scope.get_assets_name_focus = function() {
    $scope.myKeyup_assets_name($scope.search.asset_ip);
    $scope.assets_name_list_if = true;
    $scope.company_list_if = false;
  };
  $scope.assets_name_list_click = function(name) {
    $scope.search.asset_ip = name;
    $scope.assets_name_list_if = false;
  };

  $scope.if_false = function() {
    $scope.assets_name_list_if = false;
    $scope.company_list_if = false;
  };
  //   资产详情
  $scope.list_detail = function(item) {
    console.log(item);
    window.location.href = "/assets/details?asset_ip=" + item.asset_ip;
  };

  $scope.init();
});
