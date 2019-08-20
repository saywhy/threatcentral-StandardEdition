var myApp = angular.module("myApp", []);
myApp.controller("AddUserController", [
  "$scope",
  "$http",
  "$rootScope",
  function($scope, $http, $rootScope) {
    $scope.new_user = {
      username: "",
      password: "",
      repeat_password: "",
      role: "admin",
      allow_ip: ""
    };
    $scope.role_list = JSON.parse(sessionStorage.getItem("role_list"));
    $scope.pwdLength = JSON.parse(sessionStorage.getItem("pwdLength"));
    $scope.password_placeholder =
      "请填写" +
      $scope.pwdLength.min_passwd_len +
      "-" +
      $scope.pwdLength.max_passwd_len +
      "位登录密码";
    $scope.choose_list = [];
    angular.forEach($scope.role_list, function(item) {
      var obj = { name: "", choose: false };
      if (item.name == "admin") {
        obj.choose = true;
      }
      obj.name = item.name;
      $scope.choose_list.push(obj);
    });
    $scope.set_choose = function(name, blean) {
      angular.forEach($scope.choose_list, function(item) {
        item.choose = false;
      });
      angular.forEach($scope.choose_list, function(item) {
        if (item.name == name) {
          item.choose = true;
          $scope.new_user.role = name;
        }
      });
    };
    // 取消
    $scope.cancel = function() {
      window.location.href = "/seting/user";
    };
    // 保存
    $scope.save = function() {
      if ($scope.new_user.username == "") {
        zeroModal.error("用户名不能为空");
        return false;
      }
      if ($scope.new_user.password == "") {
        zeroModal.error("密码不能为空");
        return false;
      }
      if ($scope.new_user.password != $scope.new_user.repeat_password) {
        zeroModal.error("两次密码输入不一致，请重新输入");
        return false;
      }
      if (
        $scope.new_user.password.length > $scope.pwdLength.max_passwd_len ||
        $scope.new_user.password.length < $scope.pwdLength.min_passwd_len
      ) {
        zeroModal.error(
          "请填写" +
            $scope.pwdLength.min_passwd_len +
            "-" +
            $scope.pwdLength.max_passwd_len +
            "位登录密码"
        );
        return false;
      }
      var loading = zeroModal.loading(4);
      rqs_data = {
        username: $scope.new_user.username,
        password: $scope.new_user.password,
        role: $scope.new_user.role,
        allow_ip: $scope.new_user.allow_ip,
        page: 1
      };
      $http.post("/user/user-add", rqs_data).then(
        function success(rsp) {
          zeroModal.close(loading);
          console.log(rsp);
          if (rsp.data.status == "success") {
            zeroModal.success("添加角色成功！");
            window.location.href = "/seting/user";
          } else if (rsp.data.status == "fail") {
            zeroModal.error({
              content: "用户添加失败",
              contentDetail: rsp.data.errorMessage
            });
          } else if (rsp.data.status == 600) {
            console.log(rsp.data.errorMessage);
          } else {
            zeroModal.error(rsp.data.errorMessage);
          }
        },
        function err(rsp) {
          zeroModal.close(loading);
        }
      );
    };
  }
]);
