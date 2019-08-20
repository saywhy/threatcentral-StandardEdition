/* Controllers */
var myApp = angular.module("myApp", []);
myApp.controller("Set_userController", [
  "$scope",
  "$http",
  "$rootScope",
  function($scope, $http, $rootScope) {
    // 初始化
    $scope.init = function(params) {
      $scope.policy = {};
      $scope.testIP_type = false;
      $scope.pages = {
        data: [],
        count: 0,
        maxPage: "...",
        pageNow: 1
      };
      $scope.user_pages = {
        data: [],
        count: 0,
        maxPage: "...",
        pageNow: 1
      };
      $scope.type_true = true;
      // 获取数据
      $scope.todolist = $scope.list;
      $scope.getPage(); // 获取用户列表
      $scope.getRoleList(); // 获取角色列表
      $scope.getSecurityPolicy();
      $scope.getPwdLength(); //获取密码长度
    };
    // 获取密码长度
    $scope.getPwdLength = function() {
      $http({
        method: "get",
        url: "/site/get-passwd-length"
      }).then(
        function successCallback(data) {
          $scope.pwdLength = data.data.data;
          sessionStorage.setItem("pwdLength", JSON.stringify($scope.pwdLength));
        },
        function errorCallback(data) {}
      );
    };
    //tab栏切换
    $scope.tab_click = function(num) {
      switch (num) {
        case 1:
          $scope.getPage();
          break;
        case 2:
          $scope.getRoleList();
          break;
        case 3:
          $scope.getSecurityPolicy();
          break;
        default:
          break;
      }
    };
    // 获取用户列表
    $scope.getPage = function(pageNow) {
      var loading = zeroModal.loading(4);
      pageNow = pageNow ? pageNow : 1;
      $scope.index_num = (pageNow - 1) * 10;
      $http
        .post("/user/page", {
          page: pageNow,
          rows: 10
        })
        .then(
          function success(rsp) {
            zeroModal.close(loading);
            if (rsp.data.status == "success") {
              $scope.user_pages = rsp.data;
              angular.forEach($scope.user_pages.data, function(item) {
                if (item.allow_ip != "" && item.allow_ip_segment != "") {
                  item.allow = item.allow_ip + ";" + item.allow_ip_segment;
                }
                if (item.allow_ip == "") {
                  item.allow = item.allow_ip_segment;
                }
                if (item.allow_ip_segment == "") {
                  item.allow = item.allow_ip;
                }
              });
            } else if (rsp.data.status == 600) {
              zeroModal.error(rsp.data.msg);
            } else {
              zeroModal.error(rsp.data.msg);
            }
          },
          function err(rsp) {
            zeroModal.close(loading);
          }
        );
    };
    // 获取角色管理
    $scope.getRoleList = function() {
      var loading = zeroModal.loading(4);
      $http({
        method: "get",
        url: "/user/role-list"
      }).then(
        function successCallback(data) {
          $scope.roleList = data.data.data;
          sessionStorage.setItem("role_list", JSON.stringify($scope.roleList));
          zeroModal.close(loading);
        },
        function errorCallback(data) {
          zeroModal.close(loading);
        }
      );
    };
    // 添加用户
    $scope.add_user = function() {
      window.location.href = "/user/adduser-page";
    };
    // 修改用户
    $scope.edit_user = function(item) {
      sessionStorage.setItem("edit_user", JSON.stringify(item));
      window.location.href = "/user/edituser-page";
    };
    // 添加角色
    $scope.add_role = function() {
      window.location.href = "/user/addrole-page";
    };
    // 修改角色
    $scope.edit_role = function(item) {
      sessionStorage.setItem("edit_role", JSON.stringify(item));
      window.location.href = "/user/editrole-page";
    };
    // 删除用户
    $scope.cel_user = function(item) {
      zeroModal.confirm({
        content: "确定删除" + '"' + item.username + '"吗？',
        okFn: function() {
          var rqs_data = {
            id: item.id,
            page: $scope.pages.pageNow
          };
          var loading = zeroModal.loading(4);
          $http({
            method: "delete",
            url: "/user/user-del",
            data: rqs_data
          }).then(
            function(data) {
              zeroModal.close(loading);
              if (data.data.status == "success") {
                zeroModal.success("删除用户成功");
                $scope.getPage();
              } else if (data.data.status == 600) {
                console.log(data.data.errorMessage);
              } else {
                zeroModal.error(data.data.errorMessage);
              }
            },
            function() {
              zeroModal.close(loading);
            }
          );
        },
        cancelFn: function() {}
      });
    };
    // 验证Ip格式
    $scope.testIP = function(item) {
      var reg = /^(?:(?:2[0-4][0-9]\.)|(?:25[0-5]\.)|(?:1[0-9][0-9]\.)|(?:[1-9][0-9]\.)|(?:[0-9]\.)){3}(?:(?:2[0-4][0-9])|(?:25[0-5])|(?:1[0-9][0-9])|(?:[1-9][0-9])|(?:[0-9]))$/;
      var re = new RegExp(reg);
      if (!re.test(item)) {
        console.log(item);
        $scope.testIP_type = true;
      } else {
        $scope.testIP_type = false;
      }
    };
    // 删除角色
    $scope.delRole = function(item) {
      zeroModal.confirm({
        content: "确定删除 " + '"' + item.name + '"吗？',
        okFn: function() {
          $http({
            method: "delete",
            url: "/user/del-role",
            data: {
              role_name: item.name
            }
          }).then(function(data) {
            if (data.data.status == "success") {
              zeroModal.success("删除角色成功");
              // 删除成功，更新角色列表
              $scope.getRoleList();
              $scope.getPage();
            } else if (data.data.status == 600) {
              console.log(data.data.errorMessage);
            } else {
              zeroModal.error(data.data.errorMessage);
            }
          });
        },
        cancelFn: function() {}
      });
    };

    // 停用启用
    $scope.type_choose = function(name) {
      if (name == "open") {
        $scope.type_true = true;
      }
      if (name == "closed") {
        $scope.type_true = false;
      }
    };
    // 安全策略
    $scope.getSecurityPolicy = function() {
      var loading = zeroModal.loading(4);
      $http({
        method: "get",
        url: "/site/get-security-policy"
      }).then(
        function successCallback(data) {
          zeroModal.close(loading);
          $scope.policy_list = data.data.data;
          if ($scope.policy_list.status == "1") {
            $scope.type_true = true;
          }
          if ($scope.policy_list.status == "0") {
            $scope.type_true = false;
          }
          console.log(data);
          $scope.getPwdLength(); //重新获取密码长度规则
        },
        function errorCallback(data) {
          zeroModal.close(loading);
        }
      );
    };

    // 保存安全策略
    $scope.save_policy = function() {
      var loading = zeroModal.loading(4);
      if ($scope.type_true) {
        $scope.policy_list.status = "1";
      }
      if (!$scope.type_true) {
        $scope.policy_list.status = "0";
      }
      var rsp_data = {
        min_passwd_len: $scope.policy_list.min_passwd_len,
        max_passwd_len: $scope.policy_list.max_passwd_len,
        passwd_regular_edit_time: $scope.policy_list.passwd_regular_edit_time,
        admin_faild_logon_time: $scope.policy_list.admin_faild_logon_time,
        admin_logon_delay_time: $scope.policy_list.admin_logon_delay_time,
        session_timeout: $scope.policy_list.session_timeout,
        admin_online_count: $scope.policy_list.admin_online_count,
        status: $scope.policy_list.status
      };
      $http.put("/securitypolicy/set-security-policy", rsp_data).then(
        function success(data) {
          console.log(data);
          zeroModal.close(loading);
          if (data.data.status == "success") {
            zeroModal.success("保存成功");
            $scope.getSecurityPolicy();
          } else if (data.data.status == "fail") {
            zeroModal.error(data.data.errorMessage);
          }
        },
        function err(req) {
          zeroModal.close(loading);
          console.log(req);
        }
      );
    };
    // 取消安全策略
    $scope.cancel_policy = function() {
      $scope.getSecurityPolicy();
    };
    // 安全策略检测数值
    $scope.input_blur = function(name) {
      switch (name) {
        case "min_passwd_len":
          if ($scope.policy_list.min_passwd_len < 8) {
            $scope.policy_list.min_passwd_len = 8;
          }
          if ($scope.policy_list.min_passwd_len > 11) {
            $scope.policy_list.min_passwd_len = 11;
          }
          break;
        case "max_passwd_len":
          if ($scope.policy_list.max_passwd_len < 11) {
            $scope.policy_list.max_passwd_len = 11;
          }
          if ($scope.policy_list.max_passwd_len > 30) {
            $scope.policy_list.max_passwd_len = 30;
          }
          break;
        case "passwd_regular_edit_time":
          if ($scope.policy_list.passwd_regular_edit_time < 1) {
            $scope.policy_list.passwd_regular_edit_time = 1;
          }
          if ($scope.policy_list.passwd_regular_edit_time > 90) {
            $scope.policy_list.passwd_regular_edit_time = 90;
          }
          break;
        case "admin_faild_logon_time":
          if ($scope.policy_list.admin_faild_logon_time < 1) {
            $scope.policy_list.admin_faild_logon_time = 1;
          }
          if ($scope.policy_list.admin_faild_logon_time > 5) {
            $scope.policy_list.admin_faild_logon_time = 5;
          }
          break;

        case "admin_logon_delay_time":
          if ($scope.policy_list.admin_logon_delay_time < 1) {
            $scope.policy_list.admin_logon_delay_time = 1;
          }
          if ($scope.policy_list.admin_logon_delay_time > 3600) {
            $scope.policy_list.admin_logon_delay_time = 3600;
          }
          break;
        case "session_timeout":
          if ($scope.policy_list.session_timeout < 1) {
            $scope.policy_list.session_timeout = 1;
          }
          if ($scope.policy_list.session_timeout > 480) {
            $scope.policy_list.session_timeout = 480;
          }
          break;

        case "admin_online_count":
          if ($scope.policy_list.admin_online_count < 1) {
            $scope.policy_list.admin_online_count = 1;
          }
          if ($scope.policy_list.admin_online_count > 5) {
            $scope.policy_list.admin_online_count = 5;
          }
          break;
        default:
          break;
      }
    };

    $scope.init();
  }
]);
