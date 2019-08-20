var myApp = angular.module("myApp", []);
myApp.controller("netCtrl", function($scope, $http) {
  $scope.init = function() {
    $scope.changed = false;
    $scope.watching = false;
    $scope.isIPv4 = function(ipv4) {
      return /^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/.test(
        ipv4
      );
    };
    $scope.get_net("init");
    // 分割线
    $scope.select_model = [];
    $scope.gain_type = [
      {
        num: "static",
        type: "手动设置"
      },
      {
        num: "dhcp",
        type: "自动获取"
      }
    ];
    $scope.select_net_name = "";
    $scope.select_gain_type = "";
    $scope.set_true = true;
    $scope.network = {
      IPADDR: "192.168.1.11",
      NETMASK: "255.255.255.0",
      GATEWAY: "192.168.1.1",
      DNS1: "10.203.104.41",
      DNS2: ""
    };
    $scope.network_type = {
      btn: "修改",
      disabled: true
    };
    $scope.proxy_list = {};
    $scope.get_proxy();
  };

  $scope.get_net = function(name) {
    $http.get("/seting/get-network").then(
      function success(rsp) {
        if (rsp.data.data.result) {
          $scope.network_list = rsp.data.data.result;
          console.log($scope.network_list);
          $scope.select_model = [];
          for (var k in $scope.network_list) {
            var obj = {
              num: "",
              type: ""
            };
            obj.num = k;
            obj.type = k;
            $scope.select_model.push(obj);
          }
          if (name == "save") {
            for (var k in $scope.network_list) {
              if (k == $scope.select_net_name) {
                $scope.network = {
                  IPADDR: $scope.network_list[k].IPADDR,
                  NETMASK: $scope.network_list[k].NETMASK,
                  GATEWAY: $scope.network_list[k].GATEWAY,
                  DNS1: $scope.network_list[k].DNS1,
                  DNS2: $scope.network_list[k].DNS2
                };
                if ($scope.network_list[k].ONBOOT == "yes") {
                  $scope.set_true = true;
                }
                if ($scope.network_list[k].ONBOOT == "no") {
                  $scope.set_true = false;
                }
                $scope.select_gain_type = $scope.network_list[k].BOOTPROTO;
              }
            }
          } else {
            $scope.select_net_name = $scope.select_model[0].num;
            if (
              $scope.network_list[$scope.select_model[0].num].ONBOOT == "yes"
            ) {
              $scope.set_true = true;
            }
            if (
              $scope.network_list[$scope.select_model[0].num].ONBOOT == "no"
            ) {
              $scope.set_true = false;
            }
            if (
              $scope.network_list[$scope.select_model[0].num].BOOTPROTO ==
              "dhcp"
            ) {
              $scope.select_gain_type = "dhcp";
            }
            if (
              $scope.network_list[$scope.select_model[0].num].BOOTPROTO ==
              "static"
            ) {
              $scope.select_gain_type = "static";
            }
            $scope.network = {
              IPADDR: $scope.network_list[$scope.select_model[0].num].IPADDR,
              NETMASK: $scope.network_list[$scope.select_model[0].num].NETMASK,
              GATEWAY: $scope.network_list[$scope.select_model[0].num].GATEWAY,
              DNS1: $scope.network_list[$scope.select_model[0].num].DNS1,
              DNS2: $scope.network_list[$scope.select_model[0].num].DNS2
            };
          }
          console.log($scope.network);
          $scope.old_data = JSON.stringify($scope.network);
          $scope.old_select_gain_type = $scope.select_gain_type;
          $scope.old_set_true = $scope.set_true;
          console.log($scope.old_data);
        }
      },
      function err(rsp) {}
    );
  };

  //   获取代理
  $scope.get_proxy = function() {
    var loading = zeroModal.loading(4);
    $http.get("/seting/get-proxy").then(
      function success(rsp) {
        zeroModal.close(loading);
        console.log(rsp);
        if (rsp.data.status == "success") {
          $scope.proxy_list = rsp.data.data.result;
          console.log($scope.proxy_list);
        }
      },
      function err(rsp) {}
    );
  };

  //   设置代理
  $scope.save_proxy = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "put",
      url: "/seting/set-proxy",
      data: {
        HTTP_PROXY: $scope.proxy_list.HTTP_PROXY,
        HTTPS_PROXY: $scope.proxy_list.HTTPS_PROXY
      }
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (data.data.status == "success") {
          zeroModal.success("设置代理成功");
          $scope.get_proxy();
        }
        if (data.data.status == "fail") {
          zeroModal.success(data.data.errorMessage);
        }
      },
      function errorCallback(data) {}
    );
    $http.put("/seting/get-proxy").then(
      function success(rsp) {
        console.log(rsp);
        if (rsp.data.status == "success") {
          $scope.proxy_list = rsp.data.result;
        }
      },
      function err(rsp) {}
    );
  };

  // 分割线
  $scope.net_choose = function(name) {
    if ($scope.network_type.disabled) {
      return false;
    }
    if (name == "open") {
      $scope.set_true = true;
    }
    if (name == "closed") {
      $scope.set_true = false;
    }
  };

  // 点击修改
  $scope.edit_network = function() {
    $scope.network_type.disabled = false;
  };
  // 取消
  $scope.cancel_network = function() {
    $scope.network_type.disabled = true;
    console.log(JSON.parse($scope.old_data));
    $scope.network = {};
    $scope.network = JSON.parse($scope.old_data);

    $scope.select_gain_type = $scope.old_select_gain_type;
    $scope.set_true = $scope.old_set_true;
  };
  //   点击保存
  $scope.save_network = function() {
    $scope.loading = zeroModal.loading(4);
    if ($scope.set_true) {
      $scope.network.ONBOOT = "yes";
    } else {
      $scope.network.ONBOOT = "no";
    }
    var rqs_data = {};
    rqs_data[$scope.select_net_name] = {
      ONBOOT: $scope.network.ONBOOT,
      BOOTPROTO: $scope.select_gain_type,
      IPADDR: $scope.network.IPADDR,
      NETMASK: $scope.network.NETMASK,
      GATEWAY: $scope.network.GATEWAY,
      DNS1: $scope.network.DNS1,
      DNS2: $scope.network.DNS2
    };
    console.log(rqs_data);
    $http.put("/seting/put-network", rqs_data).then(
      function success(rsp) {
        console.log(rsp);
        zeroModal.close($scope.loading);
        if (rsp.data.status == "success") {
          zeroModal.success("网络设置成功");
          $scope.network_type.disabled = true;
        }
        $scope.get_net("save");
      },
      function err(rsp) {}
    );
  };

  $scope.select_net_name_change = function(name) {
    console.log(name);
    for (var k in $scope.network_list) {
      if (name == k) {
        $scope.network = {
          IPADDR: $scope.network_list[k].IPADDR,
          NETMASK: $scope.network_list[k].NETMASK,
          GATEWAY: $scope.network_list[k].GATEWAY,
          DNS1: $scope.network_list[k].DNS1,
          DNS2: $scope.network_list[k].DNS2
        };
        $scope.select_net_name = name;
        if ($scope.network_list[k].ONBOOT == "yes") {
          $scope.set_true = true;
        }
        if ($scope.network_list[k].ONBOOT == "no") {
          $scope.set_true = false;
        }
        $scope.select_gain_type = $scope.network_list[k].BOOTPROTO;
      }
    }
    $scope.old_data = JSON.stringify($scope.network);
    $scope.old_select_gain_type = $scope.select_gain_type;
    $scope.old_set_true = $scope.set_true;
  };
  $scope.init();
});
