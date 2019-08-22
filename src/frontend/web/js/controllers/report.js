var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("reportCtrl", function($scope, $http, $filter) {
  rootScope = $scope;
  $scope.init = function() {
    $scope.pages = {
      data: [],
      count: 0,
      maxPage: "...",
      pageNow: 1
    };
    $scope.report = {
      type: "0",
      cycle: "0",
      format: "1"
    };

    $scope.base64 = {
      common:
        "data:image/png;base64,/9j/4AAQSkZJRgABAQAASABIAAD/4QBARXhpZgAATU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAJ6ADAAQAAAABAAAAJQAAAAD/7QA4UGhvdG9zaG9wIDMuMAA4QklNBAQAAAAAAAA4QklNBCUAAAAAABDUHYzZjwCyBOmACZjs+EJ+/+IChElDQ19QUk9GSUxFAAEBAAACdGFwcGwEAAAAbW50clJHQiBYWVogB9wACwAMABIAOgAXYWNzcEFQUEwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAPbWAAEAAAAA0y1hcHBsZkn52TyFd5+0BkqZHjp0LAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALZGVzYwAAAQgAAABjZHNjbQAAAWwAAAAsY3BydAAAAZgAAAAtd3RwdAAAAcgAAAAUclhZWgAAAdwAAAAUZ1hZWgAAAfAAAAAUYlhZWgAAAgQAAAAUclRSQwAAAhgAAAAQYlRSQwAAAigAAAAQZ1RSQwAAAjgAAAAQY2hhZAAAAkgAAAAsZGVzYwAAAAAAAAAJSEQgNzA5LUEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAG1sdWMAAAAAAAAAAQAAAAxlblVTAAAAEAAAABwASABEACAANwAwADkALQBBdGV4dAAAAABDb3B5cmlnaHQgQXBwbGUgQ29tcHV0ZXIsIEluYy4sIDIwMTAAAAAAWFlaIAAAAAAAAPNSAAEAAAABFs9YWVogAAAAAAAAb6EAADkjAAADjFhZWiAAAAAAAABilgAAt7wAABjKWFlaIAAAAAAAACSeAAAPOwAAts5wYXJhAAAAAAAAAAAAAfYEcGFyYQAAAAAAAAAAAAH2BHBhcmEAAAAAAAAAAAAB9gRzZjMyAAAAAAABDEIAAAXe///zJgAAB5IAAP2R///7ov///aMAAAPcAADAbP/AABEIACUAJwMBIgACEQEDEQH/xAAfAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgv/xAC1EAACAQMDAgQDBQUEBAAAAX0BAgMABBEFEiExQQYTUWEHInEUMoGRoQgjQrHBFVLR8CQzYnKCCQoWFxgZGiUmJygpKjQ1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4eLj5OXm5+jp6vHy8/T19vf4+fr/xAAfAQADAQEBAQEBAQEBAAAAAAAAAQIDBAUGBwgJCgv/xAC1EQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2wBDAAICAgICAgMCAgMFAwMDBQYFBQUFBggGBgYGBggKCAgICAgICgoKCgoKCgoMDAwMDAwODg4ODg8PDw8PDw8PDw//2wBDAQICAgQEBAcEBAcQCwkLEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBD/3QAEAAP/2gAMAwEAAhEDEQA/AP38ooooAKKKKACiiigD/9D9/KKKKACiiigAooooA//R/fyiiigAooooAKKKKAP/2Q=="
    };
    $scope.word_true = true;
    $scope.report_name = "";
    $scope.choosetime = {
      startDate: moment().subtract(90, "days"),
      endDate: moment()
    };
    $scope.outTime = {
      startDate: moment()
        .subtract(90, "days")
        .unix(),
      endDate: moment().unix()
    };
    $scope.report_data = {
      report_name: ""
    };
    //获取报表 记录
    $scope.start_time_picker();
    $scope.end_time_picker();
    $scope.get_report_list();
  };

  $scope.start_time_picker = function() {
    $("#start_time_picker").daterangepicker(
      {
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        drops: "down",
        opens: "center",
        maxDate: $scope.choosetime.endDate,
        startDate: $scope.choosetime.startDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        $scope.outTime.startDate = start.unix();
      }
    );
  };
  $scope.end_time_picker = function() {
    $("#end_time_picker").daterangepicker(
      {
        singleDatePicker: true,
        showDropdowns: true,
        timePicker: true,
        timePicker24Hour: true,
        opens: "center",
        startDate: $scope.choosetime.endDate,
        maxDate: $scope.choosetime.endDate,
        locale: {
          applyLabel: "确定",
          cancelLabel: "取消",
          format: "YYYY-MM-DD HH:mm:ss"
        }
      },
      function(start, end, label) {
        console.log(start);
        console.log(end);
        $scope.outTime.endDate = start.unix();
      }
    );
  };
  $scope.word_choose = function() {
    $scope.word_true = true;
  };
  $scope.excel_choose = function() {
    $scope.word_true = false;
  };
  // 添加报表
  $scope.add_report = function() {
    if ($scope.report_data.report_name == "") {
      zeroModal.error("请填写报表名称");
      return false;
    }
    if ($scope.outTime.endDate <= $scope.outTime.startDate) {
      zeroModal.error("开始时间不能大于结束时间");
      return false;
    }
    console.log($scope.outTime);
    if ($scope.word_true) {
      $scope.report_data.report_type = "doc";
      $scope.creat_word();
    } else {
      $scope.report_data.report_type = "excel";
      $scope.creat_excel();
    }
  };
  //word
  $scope.creat_word = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/report/create-echarts-img",
      params: {
        stime: $scope.outTime.startDate,
        etime: $scope.outTime.endDate,
        report_name: $scope.report_data.report_name,
        report_type: $scope.report_data.report_type
      }
    }).then(
      function successCallback(data) {
        console.log(data);
        $scope.echarts_img_data = data.data;
        $scope.waring_trend();
        $scope.alert_caterory();
        $scope.loophole_caterory();
        $scope.darknet_caterory();
        $scope.waring_category();
        $scope.loophole_level();
        $scope.intelligence_update();
        $scope.monitor_assets_website();
        $scope.monitor_assets_host();
        zeroModal.close(loading);
        console.log("获取数据");
        console.log(new Date());
        setTimeout(function() {
          console.log("发送请求时间");
          console.log(new Date());
          var loading = zeroModal.loading(4);
          $http({
            method: "post",
            url: "/report/create-report",
            data: {
              stime: $scope.outTime.startDate,
              etime: $scope.outTime.endDate,
              report_name: $scope.report_data.report_name,
              report_type: $scope.report_data.report_type,
              waring_trend: $scope.base64.waring_trend,
              alert_caterory: $scope.base64.alert_caterory,
              waring_category: $scope.base64.waring_category,
              loophole_level: $scope.base64.loophole_level,
              intelligence_update: $scope.base64.intelligence_update,
              darknet_caterory: $scope.base64.darknet_caterory,
              loophole_caterory: $scope.base64.loophole_caterory,
              monitor_assets_host: $scope.base64.monitor_assets_host,
              monitor_assets_website: $scope.base64.monitor_assets_website
            }
          }).then(
            function successCallback(data) {
              console.log("返回接口时间");
              console.log(new Date());
              zeroModal.close(loading);
              console.log(data);
              if (data.data.status == "success") {
                // 生成成功
                zeroModal.success("保存成功!");
                $scope.get_report_list(1);
              }
            },
            function errorCallback(data) {
              zeroModal.close(loading);
              zeroModal.error("保存失败!");
            }
          );
        }, 500);
      },
      function errorCallback(data) {
        zeroModal.close(loading);
      }
    );
  };
  //excel
  $scope.creat_excel = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "post",
      url: "/report/create-report",
      data: {
        stime: $scope.outTime.startDate,
        etime: $scope.outTime.endDate,
        report_name: $scope.report_data.report_name,
        report_type: $scope.report_data.report_type
      }
    }).then(
      function successCallback(data) {
        console.log(data);
        if (data.data.status == "success") {
          // 生成成功
          zeroModal.success("保存成功!");
          $scope.get_report_list(1);
        }
        zeroModal.close(loading);
      },
      function errorCallback(data) {
        zeroModal.close(loading);
      }
    );
  };
  //获取报表列表
  $scope.get_report_list = function(pageNow) {
    var loading = zeroModal.loading(4);
    pageNow = pageNow ? pageNow : 1;
    $scope.locale_page = pageNow;
    $http({
      method: "get",
      url: "/report/list",
      params: {
        page: pageNow,
        rows: 10
      }
    }).then(
      function(data) {
        zeroModal.close(loading);
        $scope.report_list = data.data;
        console.log($scope.report_list);
      },
      function() {
        zeroModal.close(loading);
      }
    );
  };
  //   下载报表
  $scope.report_download = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/report/download-report",
      params: {
        id: item.id
      },
      responseType: "blob"
    }).then(
      function successCallback(data) {
        zeroModal.close(loading);
        console.log(data);
        if (item.report_type == "excel") {
          var elink = document.createElement("a");
          elink.download = item.report_name + ".xls";
          elink.style.display = "none";
          var blob = new Blob([data.data], {
            type: "application/vnd.ms-excel"
          });
          elink.href = URL.createObjectURL(blob);
          document.body.appendChild(elink);
          elink.click();
          document.body.removeChild(elink);
        }
        if (item.report_type == "doc") {
          var elink = document.createElement("a");
          elink.download = item.report_name + ".doc";
          elink.style.display = "none";
          var blob = new Blob([data.data], {
            type: "application/vnd.ms-excel"
          });
          elink.href = URL.createObjectURL(blob);
          document.body.appendChild(elink);
          elink.click();
          document.body.removeChild(elink);
        }
      },
      function errorCallback(data) {}
    );
  };
  //   删除报表
  $scope.report_del = function(item) {
    var loading = zeroModal.loading(4);
    $http({
      method: "delete",
      url: "/report/delete",
      data: {
        id: item.id,
        report_name: item.report_name
      }
    }).then(
      function(data) {
        zeroModal.close(loading);
        if (data.data.status == "success") {
          zeroModal.success("删除报表成功");
          $scope.get_report_list($scope.locale_page);
        }
      },
      function() {
        zeroModal.close(loading);
      }
    );
  };

  // 生成预警趋势图片
  $scope.waring_trend = function() {
    var series_data = [];
    var xAxis_data = [];
    angular.forEach($scope.echarts_img_data.waring_trend, function(item) {
      xAxis_data.push(item.date_time);
      series_data.push(item.count);
    });
    var myChart = echarts.init(document.getElementById("waring_trend"));
    var option = {
      tooltip: {
        trigger: "axis",
        axisPointer: {
          type: "shadow"
        }
      },
      grid: {
        left: "3%",
        right: "4%",
        bottom: "3%",
        top: "5%",
        containLabel: true
      },
      xAxis: {
        type: "category",
        data: xAxis_data,
        // data: ["2019-08-10", "2019-08-11"],
        axisTick: {
          show: false
        },
        splitLine: {
          show: true,
          lineStyle: {
            color: "#ECECEC",
            type: "dashed"
          }
        },
        axisLabel: {
          margin: 5,
          textStyle: {
            fontSize: 12,
            color: "#333"
          }
        },
        axisLine: {
          lineStyle: {
            color: "#ECECEC",
            type: "dashed"
          }
        }
      },
      yAxis: [
        {
          type: "value",
          minInterval: 1,
          axisTick: {
            show: false
          },
          splitLine: {
            show: true,
            lineStyle: {
              color: "#ECECEC",
              type: "dashed"
            }
          },
          axisLine: {
            lineStyle: {
              color: "#ECECEC",
              type: "dashed"
            }
          },
          axisLabel: {
            margin: 5,
            textStyle: {
              color: "#666",
              fontSize: 12
            }
          }
        }
      ],
      series: [
        {
          name: "",
          type: "bar",
          stack: "",
          animation: false,
          itemStyle: {
            normal: {
              barBorderRadius: [2, 2, 2, 2], //柱形图圆角，初始化效果
              color: "#FF5F5C"
            }
          },
          data: series_data
          //   data: [1, 0]
        }
      ]
    };
    myChart.setOption(option);
    myChart.resize();
    $scope.base64.waring_trend = myChart.getDataURL();
  };
  //威胁程度统计;
  //   alert_caterory;
  $scope.alert_caterory = function() {
    if ($scope.echarts_img_data.alert_caterory.length != 0) {
      angular.forEach($scope.echarts_img_data.alert_caterory, function(item) {
        item.name = item.degree;
        item.value = item.count;
        switch (item.name) {
          case "高":
            item.color = "#FF5F5C";
            break;
          case "中":
            item.color = "#FEAA00";
            break;
          case "低":
            item.color = "#12DCFF";
            break;
          default:
            item.color = "#12DCFF";
            break;
        }
      });
      var myChart = echarts.init(document.getElementById("alert_caterory"));
      var option = {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c} ({d}%)"
        },
        series: [
          {
            animation: false,
            name: "",
            type: "pie",
            radius: "55%",
            center: ["50%", "60%"],
            data: $scope.echarts_img_data.alert_caterory,
            label: {
              normal: {
                formatter: "{b} : {c} ({d}%)"
              }
            },
            itemStyle: {
              normal: {
                color: function(params) {
                  return params.data.color;
                }
              }
            }
          }
        ]
      };
      myChart.setOption(option);
      $scope.base64.alert_caterory = myChart.getDataURL();
    } else {
      $scope.base64.alert_caterory = $scope.base64.common;
    }
  };
  //   loophole_caterory
  $scope.loophole_caterory = function() {
    if ($scope.echarts_img_data.loophole_caterory.length != 0) {
      angular.forEach($scope.echarts_img_data.loophole_caterory, function(
        item
      ) {
        item.name = item.level;
        item.value = item.count;
        switch (item.name) {
          case "高":
            item.color = "#FF5F5C";
            break;
          case "中":
            item.color = "#FEAA00";
            break;
          case "低":
            item.color = "#12DCFF";
            break;
          default:
            item.color = "#12DCFF";
            break;
        }
      });
      var myChart = echarts.init(document.getElementById("loophole_caterory"));
      var option = {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c} ({d}%)"
        },
        series: [
          {
            animation: false,
            name: "",
            type: "pie",
            radius: "55%",
            center: ["50%", "60%"],
            data: $scope.echarts_img_data.loophole_caterory,
            label: {
              normal: {
                formatter: "{b} : {c} ({d}%)"
              }
            },
            itemStyle: {
              normal: {
                color: function(params) {
                  return params.data.color;
                }
              }
            }
          }
        ]
      };
      myChart.setOption(option);
      $scope.base64.loophole_caterory = myChart.getDataURL();
    } else {
      $scope.base64.loophole_caterory = $scope.base64.common;
    }
  };
  //   darknet_caterory
  $scope.darknet_caterory = function() {
    if ($scope.echarts_img_data.darknet_caterory.length != 0) {
      angular.forEach($scope.echarts_img_data.darknet_caterory, function(item) {
        item.name = item.level;
        item.value = item.count;
        switch (item.name) {
          case "高":
            item.color = "#FF5F5C";
            break;
          case "中":
            item.color = "#FEAA00";
            break;
          case "低":
            item.color = "#12DCFF";
            break;
          default:
            item.color = "#12DCFF";
            break;
        }
      });
      var myChart = echarts.init(document.getElementById("darknet_caterory"));
      var option = {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c} ({d}%)"
        },
        series: [
          {
            animation: false,
            name: "",
            type: "pie",
            radius: "55%",
            center: ["50%", "60%"],
            data: $scope.echarts_img_data.darknet_caterory,
            label: {
              normal: {
                formatter: "{b} : {c} ({d}%)"
              }
            },
            itemStyle: {
              normal: {
                color: function(params) {
                  return params.data.color;
                }
              }
            }
          }
        ]
      };
      myChart.setOption(option);
      $scope.base64.darknet_caterory = myChart.getDataURL();
    } else {
      $scope.base64.darknet_caterory = $scope.base64.common;
    }
  };
  //   waring_category;
  $scope.waring_category = function() {
    $scope.item_data = [];
    angular.forEach($scope.echarts_img_data.waring_category, function(i) {
      $scope.item_data_obj = {};
      $scope.item_data_obj = {
        value: i.count,
        name: i.category
      };
      $scope.item_data.push($scope.item_data_obj);
    });
    angular.forEach($scope.item_data, function(item) {
      switch (item.name) {
        case "僵尸网络C&C":
          item.color = "#0E79FF";
          item.style = {
            background: "#0E79FF"
          };
          break;
        case "钓鱼网站":
          item.color = "#AE5BD5";
          item.style = {
            background: "#AE5BD5"
          };
          break;
        case "恶意地址":
          item.color = "#666666";
          item.style = {
            background: "#666666"
          };
          break;
        case "垃圾邮件":
          item.color = "#333333";
          item.style = {
            background: "#333333"
          };
          break;
        case "恶意文件":
          item.color = "#41B3F9";
          item.style = {
            background: "#41B3F9"
          };
          break;
        case "恶意域名":
          item.color = "#A0AEC4";
          item.style = {
            background: "#A0AEC4"
          };
          break;
        case "网络代理":
          item.color = "#707CD2";
          item.style = {
            background: "#707CD2"
          };
          break;
        case "勒索地址":
          item.color = "#FF5F5C";
          item.style = {
            background: "#FF5F5C"
          };
          break;
        case "洋葱节点":
          item.color = "#962116";
          item.style = {
            background: "#962116"
          };
          break;
        case "暗网":
          item.color = "#F5BF41";
          item.style = {
            background: "#F5BF41"
          };
          break;
        case "高危漏洞":
          item.color = "#FF5F5C";
          item.style = {
            background: "#FF5F5C"
          };
          break;
        case "中危漏洞":
          item.color = "#FEAA00";
          item.style = {
            background: "#FEAA00"
          };
          break;
        case "低危漏洞":
          item.color = "#12DCFF";
          item.style = {
            background: "#12DCFF"
          };
          break;
        default:
          item.color = "#4AA46E";
          item.style = {
            background: "#4AA46E"
          };
          break;
      }
    });
    console.log($scope.item_data);
    var myChart = echarts.init(document.getElementById("waring_category"));
    var option = {
      grid: {
        top: 20,
        right: 20,
        bottom: 20,
        left: 20
      },
      series: [
        {
          animation: false,
          type: "pie",
          radius: [70, 120],
          legendHoverLin: false, //是否启用图例 hover 时的联动高亮。
          hoverAnimation: true, //是否开启 hover 在扇区上的放大动画效果。
          overOffset: 8, //高亮扇区的偏移距离。
          avoidLabelOverlap: false, //是否启用防止标签重叠策略
          center: ["50%", "50%"],
          roseType: false,
          data: $scope.item_data,
          minAngle: 2,
          label: {
            normal: {
              formatter: "{b} : {c} ({d}%)"
            }
          },
          itemStyle: {
            normal: {
              color: function(params) {
                return params.data.color;
              }
            }
          }
        }
      ]
    };
    myChart.setOption(option);
    $scope.base64.waring_category = myChart.getDataURL();
  };

  //   loophole_level
  $scope.loophole_level = function() {
    $scope.loophole_level_data = [];
    for (var k in $scope.echarts_img_data.loophole_level) {
      var obj = {
        value: $scope.echarts_img_data.loophole_level[k]
      };
      switch (k) {
        case "high":
          obj.color = "#FF5F5C";
          obj.name = "高";
          break;
        case "medium":
          obj.color = "#FEAA00";
          obj.name = "中";
          break;
        case "low":
          obj.color = "#12DCFF";
          obj.name = "低";
          break;
        default:
          break;
      }
      $scope.loophole_level_data.push(obj);
    }
    var myChart = echarts.init(document.getElementById("loophole_level"));
    var option = {
      tooltip: {
        trigger: "item",
        formatter: "{b} : {c} ({d}%)"
      },
      series: [
        {
          animation: false,
          name: "",
          type: "pie",
          radius: "55%",
          center: ["50%", "60%"],
          data: $scope.loophole_level_data,
          label: {
            normal: {
              formatter: "{b} : {c} ({d}%)"
            }
          },
          itemStyle: {
            normal: {
              color: function(params) {
                return params.data.color;
              }
            }
          }
        }
      ]
    };
    myChart.setOption(option);
    $scope.base64.loophole_level = myChart.getDataURL();
  };

  //   monitor_assets_host
  $scope.monitor_assets_host = function() {
    if ($scope.echarts_img_data.monitor_assets_host.length != 0) {
      angular.forEach($scope.echarts_img_data.monitor_assets_host, function(
        item
      ) {
        item.name = item.client_ip;
        item.value = item.count;
      });
      var myChart = echarts.init(
        document.getElementById("monitor_assets_host")
      );
      var option = {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c} ({d}%)"
        },
        series: [
          {
            animation: false,
            name: "",
            type: "pie",
            radius: "55%",
            center: ["50%", "60%"],
            data: $scope.echarts_img_data.monitor_assets_host,
            label: {
              normal: {
                formatter: "{b} : {c} ({d}%)"
              }
            }
          }
        ]
      };
      myChart.setOption(option);
      $scope.base64.monitor_assets_host = myChart.getDataURL();
    } else {
      $scope.base64.monitor_assets_host = $scope.base64.common;
    }
  };
  //   monitor_assets_website
  $scope.monitor_assets_website = function() {
    if ($scope.echarts_img_data.monitor_assets_website.length != 0) {
      angular.forEach($scope.echarts_img_data.monitor_assets_website, function(
        item
      ) {
        item.name = item.client_ip;
        item.value = item.count;
      });
      var myChart = echarts.init(
        document.getElementById("monitor_assets_website")
      );
      var option = {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c} ({d}%)"
        },
        series: [
          {
            animation: false,
            name: "",
            type: "pie",
            radius: "55%",
            center: ["50%", "60%"],
            data: $scope.echarts_img_data.monitor_assets_website,
            label: {
              normal: {
                formatter: "{b} : {c} ({d}%)"
              }
            }
          }
        ]
      };
      myChart.setOption(option);
      $scope.base64.monitor_assets_website = myChart.getDataURL();
    } else {
      $scope.base64.monitor_assets_website = $scope.base64.common;
    }
  };
  // 情报更新
  //   intelligence_update
  $scope.intelligence_update = function() {
    var yAxis_data = [];
    $scope.hoohoolab_BotnetCAndCURL = [];
    $scope.hoohoolab_IPReputation = [];
    $scope.hoohoolab_MaliciousHash = [];
    $scope.hoohoolab_MaliciousURL = [];
    $scope.hoohoolab_MobileMaliciousHash = [];
    angular.forEach($scope.echarts_img_data.intelligence_update, function(
      item
    ) {
      $scope.hoohoolab_BotnetCAndCURL.push(item.hoohoolab_BotnetCAndCURL);
      $scope.hoohoolab_IPReputation.push(item.hoohoolab_IPReputation);
      $scope.hoohoolab_MaliciousHash.push(item.hoohoolab_MaliciousHash);
      $scope.hoohoolab_MaliciousURL.push(item.hoohoolab_MaliciousURL);
      $scope.hoohoolab_MobileMaliciousHash.push(
        item.hoohoolab_MobileMaliciousHash
      );
      item.time_cn = $filter("date")(item.updated_at * 1000, "yyyy-MM-dd ");
      yAxis_data.push(item.statistics_date);
    });
    var series_data = [
      {
        animation: false,
        name: "Saic_BotnetCAndCURL",
        type: "bar",
        stack: "总量",
        barWidth: 10,
        itemStyle: {
          normal: {
            color: "#0E79FF"
          }
        },
        data: $scope.hoohoolab_BotnetCAndCURL
      },
      {
        animation: false,
        name: "Saic_IPReputation",
        type: "bar",
        stack: "总量",
        barWidth: 10,
        itemStyle: {
          normal: {
            color: "#AE5BD5"
          }
        },
        data: $scope.hoohoolab_IPReputation
      },
      {
        animation: false,
        name: "Saic_MaliciousHash",
        type: "bar",
        stack: "总量",
        barWidth: 10,
        itemStyle: {
          normal: {
            color: "#41B3F9"
          }
        },
        data: $scope.hoohoolab_MaliciousHash
      },
      {
        animation: false,
        name: "Saic_MaliciousURL",
        type: "bar",
        stack: "总量",
        barWidth: 10,
        itemStyle: {
          normal: {
            color: "#A0AEC4"
          }
        },
        data: $scope.hoohoolab_MaliciousURL
      },
      {
        animation: false,
        name: "Saic_MobileMaliciousHash",
        type: "bar",
        stack: "总量",
        barWidth: 10,
        itemStyle: {
          normal: {
            color: "#FF5F5C"
          }
        },
        data: $scope.hoohoolab_MobileMaliciousHash
      }
    ];
    console.log(yAxis_data);
    var myChart = echarts.init(document.getElementById("intelligence_update"));
    var option = {
      legend: {
        show: true
      },
      grid: {
        left: "10",
        right: "20",
        bottom: "10",
        top: "30",
        containLabel: true
      },
      xAxis: {
        type: "value",
        show: true,
        axisTick: {
          show: false
        },
        splitLine: {
          show: true,
          lineStyle: {
            color: "#ECECEC",
            type: "dashed"
          }
        },
        axisLabel: {
          margin: 5,
          textStyle: {
            fontSize: 12,
            color: "#333"
          }
        },
        axisLine: {
          lineStyle: {
            color: "#ECECEC",
            type: "dashed"
          }
        }
      },
      yAxis: {
        type: "category",
        data: yAxis_data,
        axisTick: {
          show: false
        },
        splitLine: {
          show: true,
          lineStyle: {
            color: "#ECECEC",
            type: "dashed"
          }
        },
        axisLine: {
          lineStyle: {
            color: "#ECECEC",
            type: "dashed"
          }
        },
        axisLabel: {
          margin: 5,
          textStyle: {
            color: "#666",
            fontSize: 12
          }
        }
      },
      series: series_data
    };
    myChart.setOption(option, true);
    $scope.base64.intelligence_update = myChart.getDataURL();
  };

  $scope.init();
});
