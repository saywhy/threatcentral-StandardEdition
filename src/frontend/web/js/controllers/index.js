console.log("index");
var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("indexCtrl", function($scope, $http, $filter) {
  rootScope = $scope;
  $scope.colorType = {
    high: "#962116",
    mid: "#F5BF41",
    low: "#4AA46E",
    rgbaHigh10: "rgba(150,33,22,1)",
    rgbaHigh8: "rgba(150,33,22,.8)",
    rgbaHigh2: "rgba(150,33,22,.2)",
    rgbaMid: "rgba(245,191,65,1)",
    rgbaMid8: "rgba(245,191,65,.8)",
    rgbaMid2: "rgba(245,191,65,.2)",
    rgbaLow10: "rgba(74,164,110,1)",
    rgbaLow8: "rgba(74,164,110,.8)",
    rgbaLow2: "rgba(74,164,110,.2)"
  };

  $scope.init = function() {
    $scope.showpop = false; //  弹窗
    $scope.select_model = "漏洞情报";
    $scope.title_list = [
      {
        name: "名称"
      },
      {
        name: "漏洞等级"
      },
      {
        name: "POC"
      }
    ];
    $scope.select_list = [
      {
        num: "漏洞情报",
        type: "漏洞情报"
      },
      {
        num: "信誉情报",
        type: "信誉情报"
      },
      {
        num: "暗网情报",
        type: "暗网情报"
      }
    ];
    $scope.selected = 0;
    $scope.site_top();
    $scope.alert_list();
    $scope.threat_category();
    $scope.threat_warning();
    $scope.alert_risk_assets();
    $scope.loophole_warning();
    $scope.loophole_top5();
    $scope.darknet_list();
    //   检测最新情报
    $scope.check_alert();
    //   最新情报-漏洞情报
    $scope.loophole_intelligence();
    $scope.check_alert_num = -1;
  };
  $scope.site_top = function() {
    $http.get("/alert/top").then(
      function success(rsp) {
        $scope.site_top_data = rsp.data.data;
        if ($scope.site_top_data.increase_alert_count > 0) {
          $scope.site_top_data.increase_alert_count =
            "+" + $scope.site_top_data.increase_alert_count;
          $scope.site_top_data.color = true;
        } else {
          $scope.site_top_data.color = false;
        }
      },
      function err(rsp) {}
    );
  };
  //   最新预警
  $scope.alert_list = function() {
    $http
      .post("/alert/page", {
        page: 1,
        rows: 5
      })
      .then(
        function success(rsp) {
          $scope.pages = rsp.data;
          angular.forEach($scope.pages.data, function(item) {
            switch (item.degree) {
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
                break;
            }
          });
        },
        function err(rsp) {}
      );
  };
  //   威胁类别
  $scope.threat_category = function() {
    $http.get("/alert/threat-category").then(
      function success(rsp) {
        $scope.threat_categories(rsp.data.data);
      },
      function err(rsp) {}
    );
  };

  $scope.threat_warning = function() {
    $http.get("/alert/threat-warning").then(
      function success(rsp) {
        // console.log(rsp);
        $scope.threaten(rsp.data);
      },
      function err(rsp) {}
    );
  };
  // 第一排-左边-威胁
  $scope.threaten = function(item) {
    $scope.threaten_time = [];
    $scope.threaten_count_hight = [];
    $scope.threaten_count_medium = [];
    $scope.threaten_count_low = [];
    angular.forEach(item, function(i) {
      $scope.threaten_time.push(i.statistics_time);
      $scope.threaten_count_hight.push(i.alert_details.high);
      $scope.threaten_count_medium.push(i.alert_details.medium);
      $scope.threaten_count_low.push(i.alert_details.low);
    });

    var myChart = echarts.init(document.getElementById("threaten"));
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
        data: $scope.threaten_time,
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
          name: "低",
          type: "bar",
          stack: "搜索引擎",
          itemStyle: {
            normal: {
              barBorderRadius: [2, 2, 2, 2], //柱形图圆角，初始化效果
              color: "#12DCFF"
            }
          },
          data: $scope.threaten_count_low
        },
        {
          name: "中",
          type: "bar",
          stack: "搜索引擎",
          itemStyle: {
            normal: {
              barBorderRadius: [2, 2, 2, 2], //柱形图圆角，初始化效果
              color: "#FEAA00"
            }
          },
          data: $scope.threaten_count_medium
        },
        {
          name: "高",
          type: "bar",
          barWidth: 30,
          stack: "搜索引擎",
          itemStyle: {
            normal: {
              barBorderRadius: [2, 2, 2, 2], //柱形图圆角，初始化效果
              color: "#FF5F5C"
            }
          },
          data: $scope.threaten_count_hight
        }
      ]
    };
    myChart.setOption(option);
    myChart.resize();
  };
  // 第一排-右边-威胁类别
  $scope.threat_categories = function(item) {
    $scope.item_data = [];
    angular.forEach(item, function(i, index) {
      $scope.item_data_obj = {};
      $scope.item_data_obj = {
        value: i.count - 0,
        name: i.category
      };
      if (index == 0) {
        $scope.item_data_obj.type = "highlight";
      }
      $scope.item_data.push($scope.item_data_obj);
    });
    angular.forEach($scope.item_data, function(item) {
      switch (item.name) {
        case "僵尸网络C&C":
          item.color = "#0E79FF";
          item.style = { background: "#0E79FF" };
          break;
        case "钓鱼网站":
          item.color = "#AE5BD5";
          item.style = { background: "#AE5BD5" };
          break;
        case "恶意地址":
          item.color = "#666666";
          item.style = { background: "#666666" };
          break;
        case "垃圾邮件":
          item.color = "#333333";
          item.style = { background: "#333333" };
          break;
        case "恶意文件":
          item.color = "#41B3F9";
          item.style = { background: "#41B3F9" };
          break;
        case "恶意域名":
          item.color = "#A0AEC4";
          item.style = { background: "#A0AEC4" };
          break;
        case "网络代理":
          item.color = "#707CD2";
          item.style = { background: "#707CD2" };
          break;
        case "勒索地址":
          item.color = "#FF5F5C";
          item.style = { background: "#FF5F5C" };
          break;
        case "洋葱节点":
          item.color = "#962116";
          item.style = { background: "#962116" };
          break;
        case "暗网":
          item.color = "#F5BF41";
          item.style = { background: "#F5BF41" };
          break;
        case "高危漏洞":
          item.color = "#FF5F5C";
          item.style = { background: "#FF5F5C" };
          break;
        case "中危漏洞":
          item.color = "#FEAA00";
          item.style = { background: "#FEAA00" };
          break;
        case "低危漏洞":
          item.color = "#12DCFF";
          item.style = { background: "#12DCFF" };
          break;
        default:
          item.color = "#4AA46E";
          item.style = { background: "#4AA46E" };
          break;
      }
    });
    console.log($scope.item_data);
    var index = 0; //默认选中高亮模块索引
    var myChart = echarts.init(document.getElementById("threat_categories"));
    var option = {
      grid: {
        top: 20,
        right: 20,
        bottom: 20,
        left: 20
      },
      series: [
        {
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
              show: false,
              position: "center"
            },
            emphasis: {
              show: true,
              position: "inside",
              formatter: function(item) {
                return item.percent + "%" + "\r\n" + item.data.name;
              },
              textStyle: {
                fontSize: "22"
              }
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
    myChart.resize();
    myChart.dispatchAction({
      type: "highlight",
      seriesIndex: 0,
      dataIndex: 0
    }); //设置默认选中高亮部分
    myChart.on("mouseover", function(e) {
      if (e.dataIndex != index) {
        myChart.dispatchAction({
          type: "downplay",
          seriesIndex: 0,
          dataIndex: index
        });
      }
    });
    myChart.on("mouseout", function(e) {
      index = e.dataIndex;
      myChart.dispatchAction({
        type: "highlight",
        seriesIndex: 0,
        dataIndex: e.dataIndex
      });
    });
  };

  // 第二排-左边-漏洞预警
  $scope.loophole_top5 = function() {
    $http.get("/alert/loophole-top5").then(
      function success(rsp) {
        $scope.loophole_top5_data = rsp.data.data;
      },
      function err(rsp) {}
    );
  };
  // 第三排-左边-受影响资产

  $scope.alert_risk_assets = function() {
    $http.get("/alert/risk-assets").then(
      function success(data) {
        console.log(data);
        $scope.risk_property_data = data.data.data;
        angular.forEach($scope.risk_property_data.data, function(item) {
          if ($scope.risk_property_data.total < 15) {
            $scope.risk_property_data.total = 15;
          }
          item.high_percent =
            (item.high / $scope.risk_property_data.total) * 100 + "%";
          item.medium_percent =
            (item.medium / $scope.risk_property_data.total) * 100 + "%";
          item.low_percent =
            (item.low / $scope.risk_property_data.total) * 100 + "%";
        });
      },
      function err(rsp) {}
    );
  };

  // 第三排-右边-高风险威胁指标
  $scope.loophole_warning = function() {
    $http.get("/alert/loophole-warning").then(
      function success(data) {
        console.log(data);
        $scope.loophole_warning_data = data.data.data;
        angular.forEach($scope.loophole_warning_data, function(item) {
          switch (item.category) {
            case "僵尸网络C&C":
              item.color = "#0E79FF";
              break;
            case "钓鱼网站":
              item.color = "#AE5BD5";
              break;
            case "恶意地址":
              item.color = "#666666";
              break;
            case "垃圾邮件":
              item.color = "#333333";
              break;
            case "恶意文件":
              item.color = "#41B3F9";
              break;
            case "恶意域名":
              item.color = "#A0AEC4";
              break;
            case "网络代理":
              item.color = "#707CD2";
              break;
            case "勒索地址":
              item.color = "#FF5F5C";
              break;
            case "洋葱节点":
              item.color = "#962116";
              break;
            case "暗网":
              item.color = "#F5BF41";
              break;
            case "高危漏洞":
              item.color = "#FF5F5C";
              break;
            case "中危漏洞":
              item.color = "#FEAA00";
              break;
            case "低危漏洞":
              item.color = "#12DCFF";
              break;
            default:
              item.color = "#4AA46E";
              break;
          }
        });
      },
      function err(rsp) {}
    );
  };
  $scope.select_change = function(name) {
    switch (name) {
      case "漏洞情报":
        $scope.title_list = [
          {
            name: "名称"
          },
          {
            name: "漏洞等级"
          },
          {
            name: "POC"
          }
        ];
        break;
      case "信誉情报":
        $scope.title_list = [
          {
            name: "指标"
          },
          {
            name: "威胁类型"
          },
          {
            name: "最近活跃时间"
          }
        ];
        break;
      case "暗网情报":
        $scope.title_list = [
          {
            name: "主题"
          },
          {
            name: "状态"
          },
          {
            name: "标签"
          }
        ];
        break;
      default:
        break;
    }
  };

  //   最新情报-漏洞情报
  $scope.loophole_intelligence = function() {
    $http({
      method: "get",
      url: "/alert/loophole-intelligence"
    }).then(
      function(data) {
        $scope.loophole_intelligence_data = data.data.data;
        angular.forEach($scope.loophole_intelligence_data, function(item) {
          switch (item.degree) {
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
              break;
          }
        });
      },
      function() {}
    );
  };
  //   最新情报-暗网情报
  $scope.darknet_list = function() {
    var params = {
      theme: "",
      page: 1,
      rows: 7
    };
    $http({
      method: "get",
      url: "/alert/darknet-list",
      params: params
    }).then(
      function(data) {
        $scope.darknet_list_data = data.data.data.data;
        console.log(data.data.data);
      },
      function() {}
    );
  };
  //   检测最新情报
  $scope.check_alert = function() {
    $http({
      method: "get",
      url: "/alert/check-alert"
    }).then(
      function(data) {
        if (parseInt(data.data.data) > $scope.check_alert_num) {
          $scope.check_alert_num = parseInt(data.data.data);
          $http({
            method: "get",
            url: "/alert/realtime-intelligence"
          }).then(
            function(data) {
              $scope.real_time_threat = [];
              $scope.real_time_threat = data.data.data;
            },
            function() {}
          );
        }
      },
      function() {}
    );
  };

  $scope.setinter_realtime = function() {
    setInterval(() => {
      console.log("1232");
      var item = $scope.real_time_threat.shift();
      $scope.real_time_threat.push(item);
      $scope.$apply();
    }, 2000);
  };

  $scope.init();
});

var runningData = null;
var prototypeData = null;
var nodes = null;
window.onload = function() {
  var chartDom = $(".chart-box");

  function updateChart() {
    if (runningData && prototypeData && nodes) {
      updateSankey();
    }
  }

  function getData() {
    $.ajax({
      dataType: "json",
      url: "/proxy/config/running?f=local",
      success: function(rsp) {
        runningData = rsp.result.nodes;
        updateChart();
      },
      complete: function(rsp) {}
    });
    $.ajax({
      dataType: "json",
      url: "/proxy/status/cyberhunt",
      success: function(rsp) {
        nodes = rsp.result;
        updateChart();
        // updateBarClassify();
      },
      complete: function(rsp) {}
    });
    $.ajax({
      dataType: "json",
      url: "/proxy/prototype",
      success: function(rsp) {
        prototypeData = rsp.result;
        updateChart();
      },
      complete: function(rsp) {}
    });
    $.ajax({
      dataType: "json",
      url: "/alert/top",
      success: function(rsp) {
        // updateBarIP(rsp.data);
      },
      complete: function(rsp) {}
    });

    var dt =
      moment().unix() -
      moment()
        .subtract(6, "days")
        .startOf("days")
        .unix();
    $.ajax({
      dataType: "json",
      url: "/proxy/metrics/SyslogMatcher?cf=MAX&r=2400&dt=" + dt,
      success: function(rsp) {
        updateLine(rsp.result);
      },
      complete: function(rsp) {}
    });
  }
  // getData();
};
