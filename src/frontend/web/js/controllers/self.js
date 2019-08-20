var selfApp = angular.module("selfApp", []);
selfApp.controller("selfCtrl", function(
  $scope,
  $http,
  $filter,
  $sce,
  $httpParamSerializerJQLike
) {
  $scope.resetPassword = function() {
    var loading = zeroModal.loading(4);
    $http.get("/site/get-self-password-reset-token").then(
      function success(rsp) {
        zeroModal.close(loading);
        if (rsp.data.status == "success") {
          console.log(rsp);
          $scope.resetUser = {
            password: "",
            repassword: "",
            old_password: ""
          };
          var W = 540;
          var H = 480;
          zeroModal.show({
            title: "重置密码",
            content: resetSelfPassword,
            width: W + "px",
            height: H + "px",
            ok: true,
            cancel: true,
            okFn: function() {
              var flag = true;
              var pattern = /(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z0-9]).{8,30}/;
              if (!pattern.test($scope.resetUser.password)) {
                flag = false;
                $scope.resetUser_passworderror = true;
              } else {
                $scope.resetUser_passworderror = false;
              }
              if ($scope.resetUser.password != $scope.resetUser.repassword) {
                flag = false;
                $scope.resetUser_repassworderror = true;
              } else {
                $scope.resetUser_repassworderror = false;
              }
              if ($scope.resetUser.old_password.length) {
                $scope.resetUser_old_passworderror = false;
              } else {
                flag = false;
                $scope.resetUser_old_passworderror = true;
              }

              $scope.$apply();
              if (!flag) {
                return false;
              }
              var post_data = {
                ResetPasswordForm: {
                  password: $scope.resetUser.password
                },
                old_password: $scope.resetUser.old_password
              };
              var formData = {
                method: "POST",
                url: "/site/reset-self-password?token=" + rsp.data.data.token,
                data: post_data
              };
              loading = zeroModal.loading(4);
              $http({
                method: formData.method,
                url: formData.url,
                // data: $httpParamSerializerJQLike(formData.data),
                data: formData.data
                // headers: {
                //   "Content-Type": "application/x-www-form-urlencoded"
                // }
              }).then(
                function success(rsp) {
                  if (rsp.data.status == "success") {
                    zeroModal.success("密码重置成功！");
                  } else if (rsp.data.errorMessage == "Password error") {
                    zeroModal.error({
                      content: "密码重置失败！",
                      contentDetail: "您输入的旧密码不正确。"
                    });
                  } else {
                    zeroModal.error("密码重置失败！");
                  }
                  zeroModal.close(loading);
                },
                function err(rsp) {
                  zeroModal.close(loading);
                }
              );
            },
            onCleanup: function() {
              resetSelfPasswordBox.appendChild(resetSelfPassword);
            }
          });
        }
      },
      function err(rsp) {
        zeroModal.close(loading);
      }
    );
  };
});

angular.element(document).ready(function() {
  angular.bootstrap(document.getElementById("selfApp"), ["selfApp"]);
});
