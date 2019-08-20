var myApp = angular.module("myApp", []);
myApp.controller("AddRoleController", [
  "$scope",
  "$http",
  "$rootScope",
  function($scope, $http, $rootScope) {
    console.log("AddRoleController");
    $scope.addRole = {
      name: "",
      description: "",
      permissionsList: []
    };
    // 数据类型如下
    $scope.zNodes = [
      {
        id: "1",
        name: "首页",
        children: [
          {
            name: "概览",
            id: "2"
          },
          {
            name: "可视化大屏",
            id: "14"
          }
        ]
      },
      {
        id: "15",
        name: "情报",
        children: [
          {
            name: "情报查询",
            id: "16"
          },
          {
            name: "情报提取",
            id: "24"
          },
          {
            name: "情报共享",
            id: "29"
          },
          {
            name: "情报源管理",
            id: "46"
          },
          {
            name: "APT武器库",
            id: "50"
          }
        ]
      },
      {
        name: "资产",
        id: "54",
        children: [
          {
            name: "资产管理",
            id: "55"
          },
          {
            name: "受影响资产",
            id: "72"
          }
        ]
      },
      {
        name: "预警",
        id: "77",
        children: [
          {
            name: "威胁预警",
            isSelected: false,
            id: "78"
          },
          {
            name: "漏洞预警",
            isSelected: false,
            id: "85"
          },
          {
            name: "暗网预警",
            isSelected: false,
            id: "90"
          }
        ]
      },
      {
        name: "报表",
        id: "127",
        children: [
          {
            name: "报表生成",
            id: "128"
          },
          {
            name: "报表发送",
            id: "129"
          }
        ]
      },
      {
        name: "设置",
        id: "93",
        children: [
          {
            name: "网络配置",
            id: "94"
          },
          {
            name: "威胁通知",
            id: "97"
          },
          {
            name: "漏洞关联",
            id: "104"
          },
          {
            name: "集中管理",
            id: "130"
          },
          {
            name: "账号管理",
            id: "110"
          },
          {
            name: "审计日志",
            id: "126"
          },
          {
            name: "情报API",
            id: "151"
          }
        ]
      }
    ];
    $scope.setting = {
      data: {
        key: {
          name: "name"
        }
      },
      check: {
        chkboxType: {
          Y: "ps",
          N: "ps"
        },
        enable: true
      },
      view: {
        fontCss: {
          color: "#333333",
          background: "transparent",
          border: "none"
        }
      }
    };
    $.fn.zTree.init($("#tree"), $scope.setting, $scope.zNodes);
    $scope.save = function() {
      if ($scope.addRole.name == "") {
        zeroModal.error("角色名不能为空");
        return false;
      }
      var treeObj = $.fn.zTree.getZTreeObj("tree");
      $scope.tree_nodes = treeObj.getNodes();
      $scope.addRole.permissionsList = [];
      angular.forEach($scope.tree_nodes, function(item) {
        if (item.checked) {
          $scope.addRole.permissionsList.push(item.id);
        }
        angular.forEach(item.children, function(ele) {
          if (ele.checked) {
            $scope.addRole.permissionsList.push(ele.id);
          }
        });
      });
      console.log($scope.addRole.permissionsList);
      var loading = zeroModal.loading(4);
      var post_data = {
        name: $scope.addRole.name,
        description: $scope.addRole.description,
        permissions_id: $scope.addRole.permissionsList.join(",")
      };
      console.log($scope.addRole);
      $http.post("/user/add-role", post_data).then(
        function success(data) {
          console.log(data);
          if (data.data.status == "success") {
            zeroModal.close(loading);
            // 添加成功，更新角色列表
            zeroModal.success("添加角色成功");
            window.location.href = "/seting/user";
          } else if (data.status == 600) {
            console.log(data.msg);
          } else {
            zeroModal.error(data.data.errorMessage);
          }
        },
        function err(data) {}
      );
    };
    // 取消
    $scope.cancel = function() {
      window.location.href = "/seting/user";
    };
  }
]);
