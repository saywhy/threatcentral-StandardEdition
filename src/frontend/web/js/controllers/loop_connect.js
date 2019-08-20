var myApp = angular.module("myApp", []);
myApp.controller("LoopConnectCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.search_data = {
      title: "",
      risk_name: "",
      page: 1,
      rows: 10
    };

    $scope.get_loop_list();
  };
  //   获取漏洞关联列表
  $scope.get_loop_list = function(page) {
    page = page ? page : 1;
    $scope.search_data.page = page;
    $http({
      method: "get",
      url: "/intelligence/custom-rule-list",
      params: $scope.search_data
    }).then(
      function(data) {
        console.log(data);
        if (data.data.status == "fail") {
          zeroModal.error(data.data.errorMessage);
        }
        if (data.data.status == "success") {
          $scope.loop_list = data.data.data;
          zeroModal.closeAll();
        }
      },
      function() {}
    );
  };
  $scope.add_loop = function() {
    $scope.add_lopp = {
      risk_name: "",
      title: ""
    };
    var W = 552;
    var H = 324;
    var box = null;
    box = zeroModal.show({
      title: "新建规则",
      content: custom_add,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        hideenBox_custom_add.appendChild(custom_add);
      }
    });
  };
  //   添加漏洞关联规则
  $scope.add_save = function() {
    if ($scope.add_lopp.title == "" || $scope.add_lopp.risk_name == "") {
      zeroModal.error("漏洞日志名称和漏洞情报名称不能为空");
    } else {
      var loading = zeroModal.loading(4);
      $http({
        method: "post",
        url: "/intelligence/custom-rule",
        data: {
          title: $scope.add_lopp.title,
          risk_name: $scope.add_lopp.risk_name
        }
      }).then(
        function(data) {
          zeroModal.close(loading);
          if (data.data.status == "fail") {
            zeroModal.error(data.data.errorMessage);
          }
          if (data.data.status == "success") {
            zeroModal.success("保存成功");
            $scope.get_loop_list($scope.search_data.page);
          }
        },
        function() {}
      );
    }
  };
  $scope.add_cancel = function() {
    zeroModal.closeAll();
  };
  //   编辑框
  $scope.edit_loop = function(item) {
    $scope.edit_lopp = {
      id: item.id,
      risk_name: item.risk_name,
      title: item.title
    };
    var W = 552;
    var H = 324;
    var box = null;
    box = zeroModal.show({
      title: "新建规则",
      content: custom_edit,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        hideenBox_custom_edit.appendChild(custom_edit);
      }
    });
  };
  $scope.edit_loop_save = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "PUT",
      url: "/intelligence/custom-rule-edit",
      data: {
        id: $scope.edit_lopp.id,
        title: $scope.edit_lopp.title,
        risk_name: $scope.edit_lopp.risk_name
      }
    }).then(
      function(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("修改成功");
          $scope.get_loop_list($scope.search_data.page);
        } else {
          zeroModal.error("修改失败");
        }
      },
      function() {}
    );
  };
  $scope.edit_loop_cancel = function() {
    zeroModal.closeAll();
  };

  $scope.get_relation = function(page, item) {
    $http({
      method: "get",
      url: "/intelligence/relation-alerts",
      params: {
        id: item.id,
        page: page,
        rows: 10
      }
    }).then(
      function(data) {
        $scope.get_relation_data = data.data.data;
      },
      function() {}
    );
  };
  //查看关联
  $scope.look_loop = function(item) {
    $scope.get_relation(1, item);
    var W = 1000;
    var H = 500;
    var box = null;
    box = zeroModal.show({
      title: "关联预警",
      content: custom_relation_alert,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        hideenBox_relation_alert.appendChild(custom_relation_alert);
      }
    });
  };
  //   删除规则
  $scope.cel_loop = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "DELETE",
      url: "/intelligence/custom-rule-del",
      data: {
        title: item.title,
        risk_name: item.risk_name
      }
    }).then(
      function(data) {
        zeroModal.close(loading);
        if (data.data.status == "success") {
          zeroModal.success("删除成功");
          $scope.get_loop_list($scope.search_data.page);
        } else {
          zeroModal.error("删除失败");
        }
      },
      function() {}
    );
  };
  $scope.init();
});
