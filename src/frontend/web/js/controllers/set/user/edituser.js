var myApp = angular.module("myApp", []);
myApp.controller("EditUserController", [
  "$scope",
  "$http",
  "$rootScope",
  function($scope, $http, $rootScope) {
    $scope.role_list = JSON.parse(sessionStorage.getItem("role_list"));
    $scope.edit_user = JSON.parse(sessionStorage.getItem("edit_user"));
    $scope.pwdLength = JSON.parse(sessionStorage.getItem("pwdLength"));
    $scope.password_placeholder =
      "请填写" +
      $scope.pwdLength.min_passwd_len +
      "-" +
      $scope.pwdLength.max_passwd_len +
      "位登录密码";
    console.log($scope.edit_user);
    $scope.edit_paswd = {
      new_password: "",
      repeat_password: ""
    };
    $scope.choose_list = [];
    angular.forEach($scope.role_list, function(item) {
      var obj = {
        name: "",
        choose: false
      };
      obj.name = item.name;
      if (item.name == $scope.edit_user.role) {
        obj.choose = true;
      }
      $scope.choose_list.push(obj);
    });

    $scope.set_choose = function(name, blean) {
      angular.forEach($scope.choose_list, function(item) {
        item.choose = false;
      });
      angular.forEach($scope.choose_list, function(item) {
        if (item.name == name) {
          item.choose = true;
          $scope.edit_user.role = name;
        }
      });
    };
    // 取消
    $scope.cancel = function() {
      window.location.href = "/seting/user";
    };
    // 保存获取token
    $scope.save = function() {
      $http
        .get("/user/get-password-reset-token?id=" + $scope.edit_user.id)
        .then(
          function success(rsp) {
            console.log(rsp);
            if (rsp.data.status == "success") {
              $scope.user_token = rsp.data.token;
              console.log($scope.user_token);
              if (
                $scope.edit_paswd.new_password != "" ||
                $scope.edit_paswd.repeat_password != ""
              ) {
                //   修改密码
                if (
                  $scope.edit_paswd.new_password !=
                  $scope.edit_paswd.repeat_password
                ) {
                  zeroModal.error("两次密码输入不一致，请重新输入");
                  return false;
                }
                if (
                  $scope.edit_paswd.new_password.length >
                    $scope.pwdLength.max_passwd_len ||
                  $scope.edit_paswd.new_password.length <
                    $scope.pwdLength.min_passwd_len
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
                $scope.edit_save(1);
              } else {
                //   不修改密码
                $scope.edit_save(2);
              }
            }
          },
          function err(rsp) {
            zeroModal.close(loading);
          }
        );
    };
    $scope.edit_save = function(num) {
      loading = zeroModal.loading(4);
      if (num == 1) {
        var post_data = {
          ResetPasswordForm: {
            password: $scope.edit_paswd.new_password,
            allow_ip: $scope.edit_user.allow_ip,
            role: $scope.edit_user.role
          }
        };
      }
      if (num == 2) {
        var post_data = {
          ResetPasswordForm: {
            allow_ip: $scope.edit_user.allow_ip,
            role: $scope.edit_user.role
          }
        };
      }
      $http({
        method: "put",
        url: "/user/reset-password?token=" + $scope.user_token,
        data: post_data
      }).then(function(data) {
        console.log(data);
        zeroModal.close(loading);
        if (data.data.status == "success") {
          zeroModal.success("修改成功！");
          window.location.href = "/seting/user";
        } else if (data.data.status == 600) {
          console.log(data.data.msg);
        } else {
          zeroModal.error("修改失败！");
        }
      });
    };
  }
]);
