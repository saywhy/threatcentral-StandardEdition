var myApp = angular.module("myApp", []);
myApp.controller("logCtrl", function($scope, $http) {
  $scope.init = function() {
    $scope.pages = {
      data: [],
      count: 0,
      maxPage: "...",
      pageNow: 1,
    };
    $scope.log = {
      start_time: moment()
        .subtract(1, "days")
        .unix(),
      end_time: moment().unix(),
      username: "",
      role: "",
    };
    $scope.statusData = [{ num: "", type: "所有" }];
    $scope.timerange();
    $scope.get_role();
    $scope.get_userlog();
  };
  //   获取角色列表
  $scope.get_role = function() {
    $http({
      method: "get",
      url: "/userlog/role-list",
    }).then(function(data) {
      console.log(data);
      if (data.data.ststus == "success") {
        angular.forEach(data.data.data, function(item) {
          var obj = {};
          obj.type = item.name;
          obj.num = item.name;
          $scope.statusData.push(obj);
        });
        console.log($scope.statusData);
      }
    });
  };
  $scope.get_userlog = function(pageNow) {
    pageNow = pageNow ? pageNow : 1;
    $http({
      method: "post",
      url: "/userlog/page",
      data: {
        start_time: $scope.log.start_time,
        end_time: $scope.log.end_time,
        page: pageNow,
        rows: 10,
        username: $scope.log.username,
        role: $scope.log.role,
      },
    }).then(function(data) {
      console.log(data);
      $scope.pages = data.data;
    });
  };
  // 时间插件
  $scope.timerange = function(params) {
    $(".timerange").daterangepicker(
      {
        timePicker: true,
        startDate: moment().subtract(1, "days"),
        endDate: moment(),
        timePicker24Hour: true,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm",
          customRangeLabel: "指定时间范围",
        },
        ranges: {
          今天: [moment().startOf("day"), moment().endOf("day")],
          "7日内": [
            moment()
              .startOf("day")
              .subtract(7, "days"),
            moment().endOf("day"),
          ],
          本月: [moment().startOf("month"), moment().endOf("day")],
          今年: [moment().startOf("year"), moment().endOf("day")],
        },
      },
      function(start, end, label) {
        $scope.log.start_time = start.unix();
        $scope.log.end_time = end.unix();
      }
    );
  };
  // 下载报表
  $scope.download = function() {
    if ($scope.pages.count > 1000) {
      zeroModal.error("下载数据不能超出1000条！");
    } else {
      zeroModal.confirm({
        content: "确定下载列表吗？",
        okFn: function() {
          $http({
            method: "get",
            url: "./yiiapi/site/check-auth-exist",
            params: {
              pathInfo: "userlog/export-test",
            },
          })
            .success(function(data) {
              if (data.status == 0) {
                $scope.download_list();
              }
              if (data.status == 401) {
                zeroModal.error(data.msg);
              }
            })
            .error(function(error) {
              console.log(error);
            });
        },
        cancelFn: function() {},
      });
    }
  };
  //下载列表
  $scope.download_list = function() {
    $http({
      method: "get",
      url: "/userlog/export-test",
      params: {
        username: $scope.params_data.username,
        start_time: $scope.params_data.start_time,
        end_time: $scope.params_data.end_time,
      },
    })
      .success(function(data) {
        if (data.status == 0) {
          download_now();
        } else if (data.status == 600) {
          console.log(data.msg);
        } else {
          zeroModal.error(data.msg);
        }
      })
      .error(function(error) {
        console.log(error);
      });

    function download_now() {
      var tt = new Date().getTime();
      var url = "./yiiapi/userlog/export";
      var form = $("<form>"); //定义一个form表单
      form.attr("style", "display:none");
      form.attr("target", "name");
      form.attr("id", "form1");
      form.attr("method", "get"); //请求类型
      form.attr("action", url); //请求地址
      $("body").append(form); //将表单放置在web中

      var input1 = $("<input>");
      input1.attr("type", "hidden");
      input1.attr("username", "src_ip");
      input1.attr("value", $scope.params_data.username);
      form.append(input1);

      var input6 = $("<input>");
      input6.attr("type", "hidden");
      input6.attr("name", "start_time");
      input6.attr("value", $scope.params_data.start_time);
      form.append(input6);

      var input7 = $("<input>");
      input7.attr("type", "hidden");
      input7.attr("name", "end_time");
      input7.attr("value", $scope.params_data.end_time);
      form.append(input7);

      form.submit(); //表单提交
    }
  };
  $scope.init();
});
