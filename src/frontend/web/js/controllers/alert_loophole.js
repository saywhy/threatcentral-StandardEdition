var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("AlertLoopholeCtrl", function($scope, $http, $filter, $sce) {
  $scope.init = function() {
    $scope.select_loophole_name_if = false;
    $scope.item_operation = "-1";
    $scope.select_choose = "CONFIRMED";
    $scope.choose_all = true;
    $scope.choose_count_array = [];
    $scope.disabled_select = true;

    $scope.risk_searchData = {
      asset_ip: ""
    };
    $scope.loop_serch_data = {
      page: 1,
      rows: 10,
      device_ip: "",
      loophole_name: "",
      company: "",
      poc: ""
    };
    $scope.status_str = {
      NEW: {
        css: "success",
        label: "新预警"
      },
      CONFIRMED: {
        css: "danger",
        label: "处置中"
      },
      IGNORED: {
        css: "default",
        label: "已忽略"
      },
      RESOLVED: {
        css: "default",
        label: "已解决"
      }
    };
    $scope.select_status = [
      {
        num: "CONFIRMED",
        type: "处置中"
      },
      {
        num: "RESOLVED",
        type: "已解决"
      },
      {
        num: "IGNORED",
        type: "已忽略"
      }
    ];
    $scope.company_select_loophole = [
      {
        num: "",
        type: "请选择所属"
      }
    ];
    $scope.poc_selsect = [
      {
        num: "",
        type: "请选择有无POC"
      },
      {
        num: "有",
        type: "有"
      },
      {
        num: "无",
        type: "无"
      }
    ];
    //   漏洞预警
    $scope.loop_top();
    $scope.loop_serch();
    // 漏洞受影响资产
    $scope.get_device_ip_list();
    $scope.get_loophole_name();
    $scope.get_select_loophole_company();
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
          $scope.loop_serch();
        });
      }
    };
  };
  //   获取受影响资产列表
  $scope.get_device_ip_list = function(ip) {
    $http({
      method: "get",
      url: "/alert/select-device-ip",
      params: {
        device_ip: $scope.loop_serch_data.device_ip
      }
    }).then(
      function(data) {
        if (data.data.status == "success") {
          $scope.select_device_ip = data.data.data;
        }
      },
      function() {}
    );
  };
  //   获取漏洞名称列表
  $scope.get_loophole_name = function(ip) {
    $http({
      method: "get",
      url: "/alert/select-loophole-name",
      params: {
        loophole_name: $scope.loop_serch_data.loophole_name
      }
    }).then(
      function(data) {
        if (data.data.status == "success") {
          $scope.select_loophole_name = data.data.data;
        }
      },
      function() {}
    );
  };

  //获取漏洞资产分组列表;
  $scope.get_select_loophole_company = function() {
    $http({
      method: "get",
      url: "/alert/select-loophole-company"
    }).then(
      function(data) {
        if (data.data.status == "success") {
          angular.forEach(data.data.data, function(item) {
            if (item.company != null && item.company != "") {
              var obj_loop_company = {};
              obj_loop_company.num = item.company;
              obj_loop_company.type = item.company;
              $scope.company_select_loophole.push(obj_loop_company);
            }
          });
        }
      },
      function() {}
    );
  };
  // 漏洞受影响资产搜索;
  $scope.get_device_ip_focus = function() {
    $scope.select_device_ip_if = true;
    $scope.select_loophole_name_if = false;
  };
  $scope.select_device_ip_item = function(item) {
    $scope.loop_serch_data.device_ip = item;
    $scope.select_device_ip_if = false;
  };
  $scope.myKeyup_device_ip = function(ip) {
    $scope.get_device_ip_list();
  };
  // 漏洞名称搜索;
  $scope.get_loophole_name_focus = function() {
    $scope.select_loophole_name_if = true;
    $scope.select_device_ip_if = false;
  };
  $scope.select_loophole_name_item = function(item) {
    $scope.loop_serch_data.loophole_name = item;
    $scope.select_loophole_name_if = false;
  };
  $scope.myKeyup_loophole_name = function(ip) {
    $scope.get_loophole_name();
    // $scope.get_loophole_name_list();
  };
  $scope.loop_blur_input = function(ip) {
    $scope.select_device_ip_if = false;
    $scope.select_loophole_name_if = false;
  };
  //   漏洞预警详情
  $scope.loophole_detail = function(item) {
    window.location.href = "/alert/loophole-detail";
    sessionStorage.setItem("loop_detail", JSON.stringify(item));
  };

  $scope.loop_top = function() {
    $http({
      method: "get",
      url: "/alert/loophole-statistics"
    }).then(
      function(data) {
        $scope.loop_top_data = data.data.data;
        $scope.echarts_bar($scope.loop_top_data);
      },
      function() {}
    );
  };
  // 漏洞导出报表
  $scope.loop_download = function() {
    var tt = new Date().getTime();
    var url = "/alert/loophole-export";
    var form = $("<form>"); //定义一个form表单
    form.attr("style", "display:none");
    form.attr("target", "_blank");
    form.attr("method", "get"); //请求类型
    form.attr("action", url); //请求地址
    $("body").append(form); //将表单放置在web中
    var input3 = $("<input>");
    input3.attr("type", "hidden");
    input3.attr("name", "device_ip");
    input3.attr("value", $scope.loop_serch_data.device_ip);
    form.append(input3);

    var input5 = $("<input>");
    input5.attr("type", "hidden");
    input5.attr("name", "loophole_name");
    input5.attr("value", $scope.loop_serch_data.loophole_name);
    form.append(input5);

    var input6 = $("<input>");
    input6.attr("type", "hidden");
    input6.attr("name", "company");
    input6.attr("value", $scope.loop_serch_data.company);
    form.append(input6);

    var input7 = $("<input>");
    input7.attr("type", "hidden");
    input7.attr("name", "poc");
    input7.attr("value", $scope.loop_serch_data.poc);
    form.append(input7);
    form.submit(); //表单提交
  };

  $scope.loop_serch = function(page_now) {
    $scope.item_operation = "-1";
    $scope.select_device_ip_if = false;
    $scope.select_loophole_name_if = false;
    if (!page_now) {
      page_now = 1;
    }
    $scope.loop_serch_data.page = page_now;
    var params = {
      poc: $scope.loop_serch_data.poc,
      page: page_now,
      rows: 10,
      device_ip: $scope.loop_serch_data.device_ip,
      loophole_name: $scope.loop_serch_data.loophole_name,
      company: $scope.loop_serch_data.company
    };
    $http({
      method: "get",
      url: "/alert/loophole-list",
      params: params
    }).then(
      function(data) {
        $scope.loophole = data.data.data;
        angular.forEach($scope.loophole.data, function(item) {
          switch (item.risk_status) {
            case "UNREPAIRED":
              item.risk_status_cn = "未修复";
              break;
            case "REPAIRED":
              item.risk_status_cn = "已修复";
              break;
            case "DETECTING":
              item.risk_status_cn = "检测中";
              break;
            default:
              break;
          }
          item.choose_status = true;
          item.detail_res = JSON.parse(item.detail);
        });
      },
      function() {}
    );
  };
  // 默认处理漏洞预警是未解决
  $scope.setAriaID = function(item, $event) {
    $event.stopPropagation();
    if ($scope.ariaID == item.id) {
      $scope.ariaID = null;
    } else {
      $scope.ariaID = item.id;
    }
  };
  $scope.delAriaID = function($event) {
    $event.stopPropagation();
    setTimeout(function() {
      $scope.ariaID = null;
    }, 10);
  };

  //   上面echarts 环形图表
  $scope.echarts_bar = function(params) {
    //   #loop_total,#loop_num_7,#risk_num
    //   总漏洞数;
    var loop_total_data = [];
    if (params.all_loophole.each_level_count.high != "0") {
      loop_total_data.push({
        name: "高危",
        value: params.all_loophole.each_level_count.high,
        itemStyle: {
          normal: {
            color: "#FF5F5C"
          }
        }
      });
    }
    if (params.all_loophole.each_level_count.medium != "0") {
      loop_total_data.push({
        name: "中危",
        value: params.all_loophole.each_level_count.medium,
        itemStyle: {
          normal: {
            color: "#FEAA00"
          }
        }
      });
    }
    if (params.all_loophole.each_level_count.low != "0") {
      loop_total_data.push({
        name: "低危",
        value: params.all_loophole.each_level_count.low,
        itemStyle: {
          normal: {
            color: "#7ACE4C"
          }
        }
      });
    }

    var loop_total_myChart = echarts.init(
      document.getElementById("loop_total")
    );
    var loop_total_option = {
      grid: {
        top: 20,
        right: 20,
        bottom: 20,
        left: 20
      },
      graphic: {
        type: "text",
        left: "center",
        top: "center",
        style: {
          text: params.all_loophole.total_count, //使用“+”可以使每行文字居中
          textAlign: "center",
          font: "bolder 24px 'Microsoft YaHei'",
          fill: "#bbb",
          width: 30,
          height: 30
        }
      },
      series: [
        {
          type: "pie",
          radius: [30, 55],
          legendHoverLin: false, //是否启用图例 hover 时的联动高亮。
          hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
          avoidLabelOverlap: true, //是否启用防止标签重叠策略
          center: ["50%", "50%"],
          itemStyle: {
            normal: {
              label: {
                show: true,
                formatter: function(params) {
                  var str = "";
                  switch (params.data.name) {
                    case "高危":
                      str =
                        `{rate|` +
                        params.data.value +
                        `}` +
                        "\n" +
                        `{nameStyle|高危}`;
                      break;
                    case "中危":
                      str =
                        `{rate|` +
                        params.data.value +
                        `}` +
                        "\n" +
                        `{nameStyle|中危}`;
                      break;
                    case "低危":
                      str =
                        `{rate|` +
                        params.data.value +
                        `}` +
                        "\n" +
                        `{nameStyle|低危}`;
                      break;
                  }
                  return str;
                },
                textStyle: {
                  rich: {
                    nameStyle: {
                      fontSize: 12,
                      color: "#999",
                      align: "center"
                    },
                    rate: {
                      fontSize: 18,
                      align: "center"
                    }
                  }
                }
              },
              labelLine: {
                show: true,
                length: 12,
                length2: 24,
                lineStyle: {
                  color: "#bbb"
                }
              }
            },
            emphasis: {
              label: {}
            }
          },
          roseType: false,
          data: loop_total_data
        }
      ]
    };
    loop_total_myChart.setOption(loop_total_option);
    // 7天内新增漏洞数
    var loop_num_7_data = [];
    if (params.last_7day_loophole.each_level_count.high != "0") {
      loop_num_7_data.push({
        name: "高危",
        value: params.last_7day_loophole.each_level_count.high,
        itemStyle: {
          normal: {
            color: "#FF5F5C"
          }
        }
      });
    }
    if (params.last_7day_loophole.each_level_count.medium != "0") {
      loop_num_7_data.push({
        name: "中危",
        value: params.last_7day_loophole.each_level_count.medium,
        itemStyle: {
          normal: {
            color: "#FEAA00"
          }
        }
      });
    }
    if (params.last_7day_loophole.each_level_count.low != "0") {
      loop_num_7_data.push({
        name: "低危",
        value: params.last_7day_loophole.each_level_count.low,
        itemStyle: {
          normal: {
            color: "#7ACE4C"
          }
        }
      });
    }
    var loop_num_7_myChart = echarts.init(
      document.getElementById("loop_num_7")
    );
    var loop_num_7_option = {
      grid: {
        top: 20,
        right: 20,
        bottom: 20,
        left: 20
      },
      graphic: {
        type: "text",
        left: "center",
        top: "center",
        style: {
          text: params.last_7day_loophole.total_count, //使用“+”可以使每行文字居中
          textAlign: "center",
          font: "bolder 24px 'Microsoft YaHei'",
          fill: "#bbb",
          width: 30,
          height: 30
        }
      },
      series: [
        {
          type: "pie",
          radius: [30, 55],
          legendHoverLin: false, //是否启用图例 hover 时的联动高亮。
          hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
          avoidLabelOverlap: true, //是否启用防止标签重叠策略
          center: ["50%", "50%"],
          itemStyle: {
            normal: {
              label: {
                show: true,
                formatter: function(params) {
                  var str = "";
                  if (params.data.value != "0") {
                    switch (params.data.name) {
                      case "高危":
                        str =
                          `{rate|` +
                          params.data.value +
                          `}` +
                          "\n" +
                          `{nameStyle|高危}`;
                        break;
                      case "中危":
                        str =
                          `{rate|` +
                          params.data.value +
                          `}` +
                          "\n" +
                          `{nameStyle|中危}`;
                        break;
                      case "低危":
                        str =
                          `{rate|` +
                          params.data.value +
                          `}` +
                          "\n" +
                          `{nameStyle|低危}`;
                        break;
                    }
                  }
                  return str;
                },
                textStyle: {
                  rich: {
                    nameStyle: {
                      fontSize: 12,
                      color: "#999",
                      align: "center"
                    },
                    rate: {
                      fontSize: 18,
                      align: "center"
                    }
                  }
                }
              },
              labelLine: {
                show: true,
                length: 12,
                length2: 24,
                lineStyle: {
                  color: "#bbb"
                }
              }
            },
            emphasis: {
              label: {}
            }
          },
          roseType: false,
          data: loop_num_7_data
        }
      ]
    };
    loop_num_7_myChart.setOption(loop_num_7_option);
    // 受影响资产数字
    var risk_num_data = [];
    if (params.effect_asset.each_level_count.high != "0") {
      risk_num_data.push({
        name: "高危",
        value: params.effect_asset.each_level_count.high,
        itemStyle: {
          normal: {
            color: "#FF5F5C"
          }
        }
      });
    }
    if (params.effect_asset.each_level_count.medium != "0") {
      risk_num_data.push({
        name: "中危",
        value: params.effect_asset.each_level_count.medium,
        itemStyle: {
          normal: {
            color: "#FEAA00"
          }
        }
      });
    }
    if (params.effect_asset.each_level_count.low != "0") {
      risk_num_data.push({
        name: "低危",
        value: params.effect_asset.each_level_count.low,
        itemStyle: {
          normal: {
            color: "#7ACE4C"
          }
        }
      });
    }
    var risk_num_myChart = echarts.init(document.getElementById("risk_num"));
    var risk_num_option = {
      grid: {
        top: 20,
        right: 20,
        bottom: 20,
        left: 20
      },
      graphic: {
        type: "text",
        left: "center",
        top: "center",
        style: {
          text: params.effect_asset.total_count, //使用“+”可以使每行文字居中
          textAlign: "center",
          font: "bolder 24px 'Microsoft YaHei'",
          fill: "#bbb",
          width: 30,
          height: 30
        }
      },
      series: [
        {
          type: "pie",
          radius: [30, 55],
          legendHoverLin: false, //是否启用图例 hover 时的联动高亮。
          hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
          avoidLabelOverlap: true, //是否启用防止标签重叠策略
          center: ["50%", "50%"],
          itemStyle: {
            normal: {
              label: {
                show: true,
                formatter: function(params) {
                  var str = "";
                  switch (params.data.name) {
                    case "高危":
                      str =
                        `{rate|` +
                        params.data.value +
                        `}` +
                        "\n" +
                        `{nameStyle|高危}`;
                      break;
                    case "中危":
                      str =
                        `{rate|` +
                        params.data.value +
                        `}` +
                        "\n" +
                        `{nameStyle|中危}`;
                      break;
                    case "低危":
                      str =
                        `{rate|` +
                        params.data.value +
                        `}` +
                        "\n" +
                        `{nameStyle|低危}`;
                      break;
                  }
                  return str;
                },
                textStyle: {
                  rich: {
                    nameStyle: {
                      fontSize: 12,
                      color: "#999",
                      align: "center"
                    },
                    rate: {
                      fontSize: 18,
                      align: "center"
                    }
                  }
                }
              },
              labelLine: {
                show: true,
                length: 12,
                length2: 24,
                lineStyle: {
                  color: "#bbb"
                }
              }
            },
            emphasis: {
              label: {}
            }
          },
          roseType: false,
          data: risk_num_data
        }
      ]
    };
    risk_num_myChart.setOption(risk_num_option);
  };

  // 点击显示操作
  $scope.operation_click = function(index) {
    $scope.item_operation = index;
    console.log(index);
  };
  //   操作预警已解决
  $scope.update_alert = function(item, status) {
    $scope.item_operation = "-1";
    $scope.item_update = true;
    var dataJson = {
      id: [item.id],
      risk_process: status
    };
    $http.put("/alert/do-loophole-alarm", dataJson).then(
      function success(rsp) {
        if (rsp.data.status == "success") {
          $scope.loop_serch($scope.loop_serch_data.page);
        }
      },
      function err(rsp) {}
    );
  };
  //   批量处理
  $scope.batch_updata = function() {
    $scope.data_array = [];
    angular.forEach($scope.choose_count_array, function(item) {
      if (item.risk_process != "IGNORED" && item.risk_process != "RESOLVED") {
        $scope.data_array.push(item.id);
      }
    });
    var dataJson = {
      id: $scope.data_array,
      risk_process: $scope.select_choose
    };
    $http.put("/alert/do-loophole-alarm", dataJson).then(
      function success(rsp) {
        if (rsp.data.status == "success") {
          $scope.loop_serch($scope.loop_serch_data.page);
          $scope.choose_all = true;
          $scope.choose_count_array = [];
        }
      },
      function err(rsp) {}
    );
  };
  //   单选
  $scope.choose_click = function(index_data) {
    $scope.choose_count_array = [];
    $scope.for_status = [];
    angular.forEach($scope.loophole.data, function(item, index) {
      if (index_data == index) {
        item.choose_status = !item.choose_status;
      }
      if (
        !item.choose_status &&
        item.risk_process != "IGNORED" &&
        item.risk_process != "RESOLVED"
      ) {
        $scope.choose_count_array.push(item);
      }
      //   未全选
      if (item.choose_status) {
        $scope.choose_all = true;
      }
      if (item.risk_process != "IGNORED" && item.risk_process != "RESOLVED") {
        $scope.for_status.push(item);
      }
    });
    if ($scope.choose_count_array.length == $scope.for_status.length) {
      $scope.choose_all = false;
      $scope.disabled_select = false;
    }
    if ($scope.choose_count_array.length == 0) {
      $scope.disabled_select = true;
    } else {
      $scope.disabled_select = false;
    }
  };
  //   全选
  $scope.choose_click_all = function(status) {
    $scope.choose_count_array = [];
    if (status == "false") {
      $scope.choose_all = false;
      $scope.disabled_select = false;
      angular.forEach($scope.loophole.data, function(item, index) {
        item.choose_status = false;
        if (item.risk_process != "IGNORED" && item.risk_process != "RESOLVED") {
          $scope.choose_count_array.push(item);
        }
      });
    } else {
      $scope.choose_all = true;
      $scope.disabled_select = true;
      $scope.choose_count_array = [];
      angular.forEach($scope.loophole.data, function(item, index) {
        item.choose_status = true;
      });
    }
  };
  //   取消按钮
  $scope.cel = function() {
    $scope.disabled_select = true;
    $scope.choose_all = true;
    $scope.choose_count_array = [];
    angular.forEach($scope.loophole.data, function(item, index) {
      item.choose_status = true;
    });
  };
  $scope.click_clientip = function() {};
  $scope.choose_click_td = function() {};

  $scope.init();
});
