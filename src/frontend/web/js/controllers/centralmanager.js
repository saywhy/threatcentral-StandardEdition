var myApp = angular.module("myApp", []);
myApp.controller("centralmanagerCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.branch_data = {
      company_name: "",
      device_ip: ""
    };
    $scope.role_type = {
      manage: false,
      branch: false
    };
    $scope.set_true = true;
    $scope.outTime = {
      startDate: moment()
        .subtract(90, "days")
        .unix(),
      endDate: moment().unix()
    };
    console.log($scope.outTime);

    $scope.choosetime = {
      startDate: moment().subtract(90, "days"),
      endDate: moment()
    };
    $scope.start_time_picker();
    $scope.end_time_picker();
    $scope.get_centralmanage_self();
    $scope.get_centralmanage_list();
  };
  $scope.role_choose = function(name) {
    // 分支机构不允许切换管理中心
    if (!$scope.role_type.branch) {
      if (name == "master") {
        $scope.set_true = true;
      }
    }
    if (name == "branch") {
      $scope.set_true = false;
    }
  };
  $scope.alert_box = function(item) {
    $scope.alert_item = item;
    var W = 552;
    var H = 324;
    zeroModal.show({
      title: "告警获取",
      content: alert_time,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        alert_time_box.appendChild(alert_time);
      }
    });
  };
  //   取消弹窗
  $scope.alert_time_cancel = function() {
    zeroModal.closeAll();
  };
  //   获取列表
  $scope.get_centralmanage_list = function(page) {
    var loading = zeroModal.loading(4);
    page = page ? page : "1";
    $scope.page_location = page;
    $http({
      method: "get",
      url: "/centralmanage/page",
      params: {
        page: page,
        rows: 10
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        $scope.centralmanage_list = data.data.data;
        angular.forEach($scope.centralmanage_list.data, function(item) {
          switch (item.connection_status) {
            case "unexamine":
              item.connection_status_cn = "未审核";
              break;
            case "connect":
              item.connection_status_cn = "已连接";
              break;
            case "break":
              item.connection_status_cn = "已断开";
              break;
            default:
              break;
          }
        });
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  //   获取状态设备角色
  $scope.get_centralmanage_self = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/centralmanage/self"
    }).then(
      function successCallback(data) {
        console.log(data);
        zeroModal.close(loading);
        $scope.centralmanage_self = data.data.data;
        if ($scope.centralmanage_self.role_type == "manage") {
          $scope.role_type.manage = true;
          $scope.set_true = true;
        }
        if ($scope.centralmanage_self.role_type == "branch") {
          $scope.role_type.branch = true;
          $scope.set_true = false;
          $scope.branch_data = {
            company_name: $scope.centralmanage_self.company_name,
            device_ip: $scope.centralmanage_self.device_ip
          };
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  //   审核
  $scope.centralmanage_examine = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/centralmanage/change-status",
      data: {
        id: item.id,
        connection_status: "connect"
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        if (data.data.status == "success") {
          $scope.get_centralmanage_list($scope.page_location);
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  //   断开
  $scope.centralmanage_connect = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/centralmanage/change-status",
      data: {
        id: item.id,
        connection_status: "connect"
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        if (data.data.status == "success") {
          $scope.get_centralmanage_list($scope.page_location);
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  //   连接
  $scope.centralmanage_break = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/centralmanage/change-status",
      data: {
        id: item.id,
        connection_status: "break"
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        if (data.data.status == "success") {
          $scope.get_centralmanage_list($scope.page_location);
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  //   删除分支
  $scope.del_branch = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "delete",
      url: "/centralmanage/del",
      data: {
        id: item.id
      }
    }).then(
      function successCallback(data) {
        console.log(data);
        zeroModal.close(loading);
        if (data.data.status == "success") {
          zeroModal.success("删除成功");
          $scope.get_centralmanage_list($scope.page_location);
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };

  //   切换到分支
  $scope.change_role = function() {
    if ($scope.branch_data.company_name == "") {
      zeroModal.error("公司名称不能为空!");
      return false;
    }
    if ($scope.branch_data.device_ip == "") {
      zeroModal.error("管理中心IP不能为空!");
      return false;
    }
    if (!$scope.checkIP($scope.branch_data.device_ip)) {
      zeroModal.error("管理中心IP地址格式不正确");
      return false;
    }

    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/centralmanage/change-role",
      data: {
        role_type: "branch",
        company_name: $scope.branch_data.company_name,
        manage_ip: $scope.branch_data.device_ip
      }
    }).then(
      function successCallback(data) {
        console.log(data);
        zeroModal.close(loading);
        if (data.data.status == "success") {
          zeroModal.success("切换分支成功");
          $scope.get_centralmanage_self();
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  //   重置
  $scope.reset = function() {
    $scope.branch_data = {
      company_name: "",
      device_ip: ""
    };
  };
  $scope.start_time_picker = function() {
    $("#start_time_picker").daterangepicker(
      {
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        drops: "down",
        opens: "center",
        maxDate: $scope.choosetime.endDate,
        startDate: $scope.choosetime.startDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        $scope.outTime.startDate = start.unix();
      }
    );
  };
  $scope.end_time_picker = function() {
    $("#end_time_picker").daterangepicker(
      {
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        opens: "center",
        startDate: $scope.choosetime.endDate,
        maxDate: $scope.choosetime.endDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        $scope.outTime.endDate = start.unix();
      }
    );
  };
  //   下载
  $scope.download_alert = function() {
    console.log($scope.alert_item);
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/centralmanage/download-alert",
      params: {
        stime: $scope.outTime.startDate,
        etime: $scope.outTime.endDate,
        client_ip: $scope.alert_item.device_ip
        // client_ip: "47.105.196.251"
      }
    }).then(
      function successCallback(data) {
        console.log(data);
        zeroModal.close(loading);
        if (data.data.status == "success") {
          $scope.remoteapi_download($scope.alert_item.device_ip);
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error(data.data.message);
      }
    );
  };
  $scope.remoteapi_download = function(device_ip) {
    console.log(device_ip);
    var tt = new Date().getTime();
    var url = "https://" + device_ip + ":8443/remoteapi/download";
    var form = $("<form>"); //定义一个form表单
    form.attr("style", "display:none");
    form.attr("target", "_blank");
    form.attr("method", "get"); //请求类型
    form.attr("action", url); //请求地址
    $("body").append(form); //将表单放置在web中
    var input1 = $("<input>");
    input1.attr("type", "hidden");
    input1.attr("name", "file_name");
    input1.attr("value", "alert");
    form.append(input1);
    form.submit(); //表单提交
  };

  //   检测IP地址
  $scope.checkIP = function(ip) {
    var exp = /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    var reg = ip.match(exp);
    if (reg == null) {
      return false;
    } else {
      return true;
    }
  };

  $scope.init();
});
