var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("sendCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.type_true = true;
    $scope.word_true = true;
    $scope.day_true = true;
    $scope.week_true = false;
    $scope.month_true = false;
    $scope.input_list = [
      {
        name: "",
        icon: true
      }
    ];
    $scope.config_list = {
      status: "1",
      cycle: "daily",
      report_type: "doc",
      receiver: []
    };
    $scope.get_config();
  };

  $scope.type_choose = function(name) {
    if (name == "open") {
      $scope.type_true = true;
      $scope.config_list.status = "1";
    }
    if (name == "closed") {
      $scope.type_true = false;
      $scope.config_list.status = "0";
    }
  };
  $scope.cycle = function(name) {
    if (name == "day") {
      $scope.day_true = true;
      $scope.week_true = false;
      $scope.month_true = false;
      $scope.config_list.cycle = "daily";
    }
    if (name == "week") {
      $scope.day_true = false;
      $scope.week_true = true;
      $scope.month_true = false;
      $scope.config_list.cycle = "weekly";
    }
    if (name == "month") {
      $scope.day_true = false;
      $scope.week_true = false;
      $scope.month_true = true;
      $scope.config_list.cycle = "monthly";
    }
  };
  $scope.format_choose = function(name) {
    if (name == "word") {
      $scope.word_true = true;
      $scope.config_list.report_type == "doc";
    }
    if (name == "excel") {
      $scope.word_true = false;
      $scope.config_list.report_type == "excel";
    }
  };

  $scope.add_input = function(index) {
    var obj = {
      name: "",
      icon: true
    };
    $scope.input_list.push(obj);
    $scope.input_list[index].icon = false;
  };
  $scope.del_input = function(index) {
    $scope.input_list.splice(index, 1);
  };
  //   获取配置
  $scope.get_config = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/report/get-config"
    }).then(function(data) {
      console.log(data);
      zeroModal.close(loading);
      if (data.data.status == "success") {
        $scope.config_list = data.data.data;
        if ($scope.config_list.status == "0") {
          $scope.type_true = false;
        }
        if ($scope.config_list.status == "1") {
          $scope.type_true = true;
        }
        switch ($scope.config_list.cycle) {
          case "daily":
            $scope.day_true = true;
            $scope.week_true = false;
            $scope.month_true = false;
            break;
          case "weekly":
            $scope.day_true = false;
            $scope.week_true = true;
            $scope.month_true = false;
            break;
          case "monthly":
            $scope.day_true = false;
            $scope.week_true = false;
            $scope.month_true = true;
            break;
          default:
            break;
        }
        if ($scope.config_list.report_type == "doc") {
          $scope.word_true = true;
        }
        if ($scope.config_list.report_type == "excel") {
          $scope.word_true = false;
        }

        if ($scope.config_list.receiver.length != 0) {
          $scope.input_list = [];
          angular.forEach($scope.config_list.receiver, function(item) {
            var obj = {
              name: "",
              icon: false
            };
            obj.name = item;
            $scope.input_list.push(obj);
          });
          $scope.input_list[$scope.input_list.length - 1].icon = true;
          console.log($scope.input_list);
        } else {
          $scope.input_list = [
            {
              name: "",
              icon: true
            }
          ];
        }
      }
    });
  };
  //   设置配置
  $scope.set_config = function() {
    console.log($scope.input_list);
    $scope.config_list.receiver = [];
    angular.forEach($scope.input_list, function(item) {
      if (item.name != "") {
        $scope.config_list.receiver.push(item.name);
      }
    });
    if ($scope.config_list.receiver.length == 0) {
      zeroModal.error("收件人邮箱不能为空");
      return false;
    }
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/report/set-config",
      data: {
        status: $scope.config_list.status,
        cycle: $scope.config_list.cycle,
        report_type: $scope.config_list.report_type,
        receiver: $scope.config_list.receiver
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("设置成功");
          $scope.get_config();
        }
      },
      function errorCallback(data) {}
    );
  };
  // 取消设置
  $scope.can_cel = function() {
    $scope.get_config();
  };

  $scope.init();
});
