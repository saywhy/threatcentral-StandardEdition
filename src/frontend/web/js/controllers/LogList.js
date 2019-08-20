var myApp = angular.module("myApp", []);
myApp.controller("logCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.choosetime = {
      startDate: moment().subtract(90, "days"),
      endDate: moment()
    };
    $scope.parmas_data = {
      username: "",
      start_time: moment()
        .subtract(90, "days")
        .unix(),
      end_time: moment().unix(),
      page: 1,
      rows: 10,
      role: ""
    };
    $scope.select_type = [{ num: "", type: "所有" }];
    $scope.select_name = "";
    $scope.time_picker();
    $scope.get_role_list();
    $scope.get_page_list();
  };

  $scope.time_picker = function() {
    $("#timerange").daterangepicker(
      {
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        drops: "down",
        opens: "right",
        maxDate: $scope.choosetime.endDate,
        startDate: $scope.choosetime.startDate,
        endDate: $scope.choosetime.endDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        $scope.parmas_data.start_time = start.unix();
        $scope.parmas_data.end_time = end.unix();
      }
    );
  };

  // 获取角色列表
  $scope.get_role_list = function() {
    $http.get("/user/role-list").then(
      function success(rsp) {
        console.log(rsp);
        $scope.select_type = [{ num: "", type: "所有" }];
        if (rsp.data.status == "success") {
          $scope.role_list = rsp.data.data;
          angular.forEach($scope.role_list, function(item) {
            var obj = {
              num: item.name,
              type: item.name
            };
            $scope.select_type.push(obj);
          });
        }
      },
      function err(rsp) {}
    );
  };
  //获取列表
  $scope.get_page_list = function(pageNow) {
    pageNow = pageNow ? pageNow : 1;
    $scope.parmas_data.page = pageNow;
    $scope.parmas_data.role = $scope.select_name;
    $http.post("/userlog/page", $scope.parmas_data).then(
      function success(rsp) {
        if (rsp.data.status == "success") {
          $scope.page_list = rsp.data;
        }
      },
      function err(rsp) {}
    );
  };

  $scope.init();
});
