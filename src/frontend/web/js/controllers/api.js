var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("myApi", function($scope, $rootScope, $http, $filter) {
  $scope.init = function() {
    $scope.set_true = true;
    console.log("123123");
    $scope.outTime = {
      startDate: moment()
        .subtract(90, "days")
        .unix(),
      endDate: moment().unix()
    };
    $scope.choosetime = {
      startDate: moment().subtract(90, "days"),
      endDate: moment()
    };
    $scope.start_time_picker();
    $scope.end_time_picker();
    $scope.get_api_list(1);
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
  $scope.edit_start_time_picker = function() {
    $("#edit_start_time_picker").daterangepicker(
      {
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        drops: "down",
        opens: "center",
        startDate: $scope.edit_choosetime.startDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        $scope.edit_outTime.startDate = start.unix();
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
  $scope.edit_end_time_picker = function() {
    $("#edit_end_time_picker").daterangepicker(
      {
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        opens: "center",
        startDate: $scope.edit_choosetime.endDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        $scope.edit_outTime.endDate = start.unix();
      }
    );
  };
  $scope.net_choose = function(name) {
    if (name == "open") {
      $scope.set_true = true;
      var loading = zeroModal.loading(4);
      $http({
        method: "put",
        url: "/api/cert-verify",
        data: {
          SSL: "2"
        }
      }).then(
        function successCallback(data) {
          zeroModal.close(loading);
          console.log(data);
          if (data.data.status == "success") {
            zeroModal.success("开启证书验证成功");
            $scope.get_api_list($scope.page_local);
          }
          if (data.data.status == "fail") {
            zeroModal.error(data.data.errorMessage);
          }
        },
        function errorCallback(data) {}
      );
    }
    if (name == "closed") {
      $scope.set_true = false;
      var loading = zeroModal.loading(4);
      $http({
        method: "put",
        url: "/api/cert-verify",
        data: {
          SSL: "0"
        }
      }).then(
        function successCallback(data) {
          zeroModal.close(loading);
          console.log(data);
          if (data.data.status == "success") {
            zeroModal.success("关闭证书验证成功");
            $scope.get_api_list($scope.page_local);
          }
          if (data.data.status == "fail") {
            zeroModal.error(data.data.errorMessage);
          }
        },
        function errorCallback(data) {}
      );
    }
  };
  //   打开生成token弹窗
  $scope.token_add = function() {
    $scope.add_token_data = {
      institution: "",
      search_count: ""
    };
    var W = 552;
    var H = 445;
    zeroModal.show({
      title: "生成TOKEN",
      content: token,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        token_box.appendChild(token);
      }
    });
  };
  // 获取列表
  $scope.get_api_list = function(page) {
    page = page ? page : 1;
    $scope.page_local = page;
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/api/list",
      params: {
        page: page,
        rows: 10
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          $scope.api_list = data.data;
          if ($scope.api_list.SSL == "2") {
            $scope.set_true = true;
          }
          if ($scope.api_list.SSL == "0") {
            $scope.set_true = false;
          }
          angular.forEach($scope.api_list.data, function(item) {
            if (item.status == "1") {
              item.choose = true;
            } else {
              item.choose = false;
            }
          });
        }
      },
      function errorCallback(data) {}
    );
  };
  //   搜索列表
  $scope.token_search = function(page) {
    page = page ? page : 1;
    $scope.page_local = page;
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/api/list",
      params: {
        page: page,
        rows: 10,
        institution: $scope.token_institution
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          $scope.api_list = data.data;
          angular.forEach($scope.api_list.data, function(item) {
            if (item.status == "1") {
              item.choose = true;
            } else {
              item.choose = false;
            }
          });
        }
      },
      function errorCallback(data) {}
    );
  };

  //   生成token
  $scope.token_save = function() {
    $scope.add_token_data.start_time = $scope.outTime.startDate;
    $scope.add_token_data.end_time = $scope.outTime.endDate;
    if ($scope.add_token_data.end_time <= $scope.add_token_data.start_time) {
      zeroModal.error("开始时间不能大于结束时间");
      return false;
    }
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/api/create-token",
      data: {
        institution: $scope.add_token_data.institution,
        start_time: $scope.add_token_data.start_time,
        end_time: $scope.add_token_data.end_time,
        search_count: $scope.add_token_data.search_count
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("生成TOKEN成功");
          $scope.get_api_list(1);
          zeroModal.closeAll();
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {}
    );
  };
  //  关闭生成token弹窗
  $scope.token_cancel = function() {
    zeroModal.closeAll();
  };
  //   禁用启用token
  $scope.choose_open = function(item) {
    if (item.choose) {
      $scope.status_token = "0";
    } else {
      $scope.status_token = "1";
    }
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/api/forbide-token",
      data: {
        institution: item.institution,
        id: item.id,
        status: $scope.status_token
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          $scope.get_api_list($scope.page_local);
        }
      },
      function errorCallback(data) {}
    );
  };
  //   刷新token
  $scope.update_token = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/api/create-token",
      data: {
        institution: item.institution,
        id: item.id
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("刷新TOKEN成功");
          $scope.get_api_list($scope.page_local);
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {}
    );
  };
  //   编辑token弹窗
  $scope.edit_token = function(item) {
    $scope.edit_token_item = item;
    var W = 552;
    var H = 445;
    zeroModal.show({
      title: "编辑TOKEN",
      content: edit_token,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        edit_token_box.appendChild(edit_token);
      }
    });
    $scope.edit_choosetime = {
      startDate: moment(new Date($scope.edit_token_item.start_time * 1000)),
      endDate: moment(new Date($scope.edit_token_item.end_time * 1000))
    };
    console.log($scope.edit_choosetime);

    $scope.edit_outTime = {
      startDate: $scope.edit_token_item.start_time,
      endDate: $scope.edit_token_item.end_time
    };
    $scope.edit_start_time_picker();
    $scope.edit_end_time_picker();
  };
  $scope.edit_token_save = function() {
    if ($scope.edit_outTime.endDate <= $scope.edit_outTime.startDate) {
      zeroModal.error("开始时间不能大于结束时间");
      return false;
    }
    console.log($scope.edit_outTime);
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/api/edit-api",
      data: {
        id: $scope.edit_token_item.id,
        start_time: $scope.edit_outTime.startDate,
        end_time: $scope.edit_outTime.endDate,
        search_count: $scope.edit_token_item.search_count
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("修改TOKEN成功");
          $scope.get_api_list($scope.page_local);
          zeroModal.closeAll();
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {}
    );
  };
  $scope.edit_token_cancel = function() {
    zeroModal.closeAll();
  };

  //   删除token
  $scope.cel_tolen = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "delete",
      url: "/api/del-token",
      data: {
        institution: item.institution,
        id: item.id
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("删除TOKEN成功");
          $scope.get_api_list($scope.page_local);
        }
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
      },
      function errorCallback(data) {}
    );
  };
  //   下载证书
  $scope.download = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/api/download-cert",
      responseType: "blob"
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        var elink = document.createElement("a");
        elink.download = "证书.zip";
        elink.style.display = "none";
        var blob = new Blob([data.data], { type: "application/vnd.ms-excel" });
        elink.href = URL.createObjectURL(blob);
        document.body.appendChild(elink);
        elink.click();
        document.body.removeChild(elink);
      },
      function errorCallback(data) {}
    );
  };
  // 复制token
  $scope.copy_token = function(token) {
    var input = document.getElementById(token);
    input.value = token; // 修改文本框的内容
    input.select(); // 选中文本
    document.execCommand("Copy"); // 执行浏览器复制命令
  };
  //证书验证
  $scope.cert_verify = function() {};
  $scope.init();
});
