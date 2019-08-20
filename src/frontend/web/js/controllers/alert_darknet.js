var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("AlertDarknetCtrl", function($scope, $http, $filter, $sce) {
  $scope.init = function() {
    $scope.select_loophole_name_if = false;
    $scope.searchData = {
      theme: ""
    };
    $scope.get_dark_list(1);
    $scope.get_theme_list();
    $scope.enter();
  };
  $scope.enter = function() {
    document.onkeydown = function(e) {
      // 兼容FF和IE和Opera
      var theEvent = e || window.event;
      var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
      if (code == 13) {
        //回车执行查询
        $scope.$apply(function() {
          $scope.get_dark_list(1);
          $(".search_input").blur();
        });
      }
    };
  };
  // 暗网预警列表
  $scope.get_dark_list = function(page) {
    $scope.select_loophole_name_if = false;
    $scope.postData = {
      theme: $scope.searchData.theme,
      page: page,
      rows: 10
    };
    $http({
      method: "get",
      url: "/alert/darknet-list",
      params: $scope.postData
    }).then(
      function(data) {
        if (data.data.status == "success") {
          $scope.darknet_list_data = data.data.data;
        }
      },
      function() {}
    );
  };
  //预警描述列表
  $scope.get_theme_list = function() {
    $http({
      method: "get",
      url: "/alert/darknet-theme",
      params: {
        theme: $scope.searchData.theme
      }
    }).then(
      function(data) {
        if (data.data.status == "success") {
          $scope.theme_list = data.data.data;
        }
      },
      function() {}
    );
  };

  // 搜索;
  $scope.get_loophole_name_focus = function() {
    $scope.select_loophole_name_if = true;
  };
  $scope.get_loophole_name_blur = function() {
    // $scope.select_loophole_name_if = false;
  };
  $scope.myKeyup_loophole_name = function(name) {
    $scope.get_theme_list();
  };
  $scope.select_loophole_name_item = function(item) {
    $scope.searchData.theme = item;
    $scope.select_loophole_name_if = false;
  };
  $scope.dark_detail = function(item) {
    window.location.href =
      "/alert/darknet-detail?content=" + JSON.stringify(item);
  };

  $scope.darknet_download = function(name) {
    var tt = new Date().getTime();
    var url = "/alert/darknet-download";
    var form = $("<form>"); //定义一个form表单
    form.attr("style", "display:none");
    form.attr("target", "_blank");
    form.attr("method", "get"); //请求类型
    form.attr("action", url); //请求地址
    $("body").append(form); //将表单放置在web中
    var input1 = $("<input>");
    input1.attr("type", "hidden");
    input1.attr("name", "file_name");
    input1.attr("value", name);
    form.append(input1);
    form.submit(); //表单提交
  };

  $scope.set_model = function(item) {
    $scope.ssl_data = true;
    console.log(item);
    $scope.model_data = item;
    var W = 552;
    var H = 333;
    var box = null;
    box = zeroModal.show({
      title: "邮箱账号验证配置",
      content: darknet_model,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        darknet_hideenBox.appendChild(darknet_model);
      }
    });
  };
  $scope.ssl_change = function(name) {
    console.log($scope.ssl_data);
  };
  $scope.model_save = function() {
    zeroModal.closeAll();
  };
  $scope.model_cel = function() {
    zeroModal.closeAll();
  };
  $scope.init();
});
