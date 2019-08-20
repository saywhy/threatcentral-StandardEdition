var myApp = angular.module("myApp", []);
myApp.controller("systemnoticeCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.email = {
      alertEmail: "",
      encryption: "",
      host: "",
      password: "",
      port: 0,
      username: ""
    };
    $scope.email_true = {
      ssl: true,
      send: true,
      dark_net: true,
      system: true
    };
    $scope.input_list = [{ name: "", icon: true }];
    $scope.get_data();
  };
  $scope.validate = function(type) {
    var flag = false;
    if (type == "test") {
      flag = $scope.email.username && $scope.email.alertEmail;
    } else if (type == "save") {
      flag =
        $scope.email.username &&
        (!$scope.email.send || $scope.email.alertEmail);
    }
    return flag;
  };
  $scope.get_data = function() {
    $http.get("/email/get").then(
      function success(rsp) {
        console.log(rsp);
        $scope.email = rsp.data;
        if ($scope.email.encryption == "ssl") {
          $scope.email_true.ssl = true;
        } else {
          $scope.email_true.ssl = false;
        }
        $scope.email_true.send = $scope.email.send;
        $scope.email_true.system = $scope.email.system;
        $scope.email_true.dark_net = $scope.email.dark_net;
        if ($scope.email.alertEmail && $scope.email.alertEmail != "") {
          $scope.input_list = [];
          $scope.alertEmail_list = $scope.email.alertEmail.split(";");
          if ($scope.alertEmail_list.length == 1) {
            angular.forEach($scope.alertEmail_list, function(item, index) {
              var obj = {
                name: item,
                icon: true
              };
              $scope.input_list.push(obj);
            });
          } else {
            angular.forEach($scope.alertEmail_list, function(item, index) {
              var obj = {
                name: item,
                icon: false
              };
              if (index == $scope.alertEmail_list.length - 1) {
                obj.icon = true;
              }
              $scope.input_list.push(obj);
            });
          }
        } else if ($scope.email.alertEmail && $scope.email.alertEmail == "") {
          $scope.input_list = [{ name: "", icon: true }];
        }
      },
      function err(rsp) {}
    );
  };
  //    发送邮件
  $scope.send_email = function(type) {
    // if (!$scope.validate("test")) {
    //   zeroModal.error("请输入有效的邮箱!");
    //   return;
    // }
    $scope.rqs_data_alertEmail_list = [];
    angular.forEach($scope.input_list, function(item) {
      if (item.name != "") {
        $scope.rqs_data_alertEmail_list.push(item.name);
      }
    });
    if ($scope.rqs_data_alertEmail_list.length == 0) {
      zeroModal.error("请输入收件邮箱地址");
      return;
    }
    console.log($scope.rqs_data_alertEmail_list.join(";"));
    var rqs_data = {
      alertEmail: $scope.rqs_data_alertEmail_list.join(";"),
      encryption: "",
      host: $scope.email.host,
      password: $scope.email.password,
      port: $scope.email.port,
      username: $scope.email.username,
      send: $scope.email_true.send,
      system: $scope.email_true.system,
      dark_net: $scope.email_true.dark_net
    };
    if ($scope.email_true.ssl) {
      rqs_data.encryption = "ssl";
    } else {
      rqs_data.encryption = "";
    }
    var loading = zeroModal.loading(4);
    if (type == "test") {
      $http.post("/email/test", rqs_data).then(
        function success(rsp) {
          console.log(rsp);
          zeroModal.close(loading);
          if (rsp.data.status == "success") {
            zeroModal.success("邮件发送成功!");
          } else {
            zeroModal.error(rsp.data.errorMessage);
          }
        },
        function err(rsp) {
          zeroModal.close(loading);
          zeroModal.error("邮件发送失败!");
        }
      );
    }
    if (type == "save") {
      $http.post("/email/save", rqs_data).then(
        function success(rsp) {
          console.log(rsp);
          zeroModal.close(loading);
          if (rsp.data.status == "success") {
            zeroModal.success("保存成功!");
            $scope.get_data();
          } else {
            zeroModal.error(rsp.data.errorMessage);
          }
        },
        function err(rsp) {
          zeroModal.close(loading);
          zeroModal.error("保存失败!");
        }
      );
    }
  };

  $scope.set_choose = function(type, value) {
    switch (type) {
      case "ssl":
        if (value == "open") {
          $scope.email_true.ssl = true;
        }
        if (value == "closed") {
          $scope.email_true.ssl = false;
        }
        break;
      case "send":
        if (value == "open") {
          $scope.email_true.send = true;
        }
        if (value == "closed") {
          $scope.email_true.send = false;
        }
        break;
      case "dark_net":
        if (value == "open") {
          $scope.email_true.dark_net = true;
        }
        if (value == "closed") {
          $scope.email_true.dark_net = false;
        }
        break;
      case "system":
        if (value == "open") {
          $scope.email_true.system = true;
        }
        if (value == "closed") {
          $scope.email_true.system = false;
        }
        break;
      default:
        break;
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

  $scope.init();
});
