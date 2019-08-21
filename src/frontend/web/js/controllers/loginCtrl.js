var myApp = angular.module("myApp", []);
myApp.controller("loginCtrl", function($scope, $http, $filter, $sce) {
  $scope.init = function() {
    $scope.user = {
      username: "",
      password: "",
      code: ""
    };
    $scope.success_code = false;
    $scope.errorMessage = {
      username: "",
      password: ""
    };
    setTimeout(function() {
      $scope.verCode();
    }, 200);
    $scope.select_if = true;
    if (localStorage.getItem("username")) {
      $scope.user.username = localStorage.getItem("username");
    }
    if (localStorage.getItem("password")) {
      $scope.user.password = localStorage.getItem("password");
    }
    sessionStorage.setItem("tab_active", "true");
    document.onkeydown = function(e) {
      // 兼容FF和IE和Opera
      var theEvent = e || window.event;
      var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
      if (code == 13) {
        //回车执行查询
        console.log(13);
        $scope.$apply(function() {
          if ($scope.user.username == "") {
            $scope.errorMessage.username = "用户名不能为空";
          } else if ($scope.user.password == "") {
            $scope.errorMessage.password = "密码不能为空";
          } else {
            $scope.login_in();
          }
        });
      }
    };
  };

  $scope.verCode = function() {
    var nums = [
      "2",
      "3",
      "4",
      "5",
      "6",
      "7",
      "8",
      "9",
      "A",
      "B",
      "C",
      "D",
      "E",
      "F",
      "G",
      "H",
      "J",
      "K",
      "M",
      "N",
      "P",
      "Q",
      "R",
      "S",
      "T",
      "U",
      "V",
      "W",
      "X",
      "Y",
      "Z",
      "a",
      "b",
      "c",
      "d",
      "e",
      "f",
      "g",
      "h",
      "j",
      "k",
      "m",
      "n",
      "p",
      "q",
      "r",
      "s",
      "t",
      "u",
      "v",
      "w",
      "x",
      "y",
      "z"
    ];
    var str = "";
    var verVal = drawCode();
    // 绘制验证码
    function drawCode(str) {
      var canvas = document.getElementById("verifyCanvas"); //获取HTML端画布
      var context = canvas.getContext("2d"); //获取画布2D上下文
      context.fillStyle = "cornflowerblue"; //画布填充色
      context.fillRect(0, 0, canvas.width, canvas.height); //清空画布
      context.fillStyle = "white"; //设置字体颜色
      context.font = "25px Arial"; //设置字体
      var rand = new Array();
      var x = new Array();
      var y = new Array();
      for (var i = 0; i < 4; i++) {
        rand.push(rand[i]);
        rand[i] = nums[Math.floor(Math.random() * nums.length)];
        x[i] = i * 20 + 10;
        y[i] = Math.random() * 20 + 20;
        context.fillText(rand[i], x[i], y[i]);
      }
      str = rand.join("").toUpperCase();
      $scope.NumCode = str;
      //画3条随机线
      for (var i = 0; i < 3; i++) {
        drawline(canvas, context);
      }
      // 画30个随机点
      for (var i = 0; i < 30; i++) {
        drawDot(canvas, context);
      }
      convertCanvasToImage(canvas);
      return str;
    }
    // 随机线
    function drawline(canvas, context) {
      context.moveTo(
        Math.floor(Math.random() * canvas.width),
        Math.floor(Math.random() * canvas.height)
      ); //随机线的起点x坐标是画布x坐标0位置，y坐标是画布高度的随机数
      context.lineTo(
        Math.floor(Math.random() * canvas.width),
        Math.floor(Math.random() * canvas.height)
      ); //随机线的终点x坐标是画布宽度，y坐标是画布高度的随机数
      context.lineWidth = 0.5; //随机线宽
      context.strokeStyle = "rgba(50,50,50,0.3)"; //随机线描边属性
      context.stroke(); //描边，即起点描到终点
    }
    // 随机点(所谓画点其实就是画1px像素的线，方法不再赘述)
    function drawDot(canvas, context) {
      var px = Math.floor(Math.random() * canvas.width);
      var py = Math.floor(Math.random() * canvas.height);
      context.moveTo(px, py);
      context.lineTo(px + 1, py + 1);
      context.lineWidth = 0.2;
      context.stroke();
    }
    // 绘制图片
    function convertCanvasToImage(canvas) {
      document.getElementById("verifyCanvas").style.display = "none";
      var image = document.getElementById("code_img");
      image.src = canvas.toDataURL("image/png");
      return image;
    }

    // 点击图片刷新
    document.getElementById("code_img").onclick = function() {
      resetCode();
    };

    function resetCode() {
      $("#verifyCanvas").remove();
      $("#code_img").before(
        '<canvas width="134" height="42" id="verifyCanvas"></canvas>'
      );
      verVal = drawCode();
    }
  };
  $scope.login_in = function() {
    if ($scope.user.code != "") {
      if ($scope.user.code.toUpperCase() != $scope.NumCode) {
        $scope.errorMessage.code = "验证码输入不正确";
        $scope.success_code = false;
      } else {
        $scope.success_code = true;
      }
    } else {
      $scope.errorMessage.code = "请输入验证码";
    }

    if ($scope.success_code) {
      $http({
        method: "POST",
        url: "/site/signin",
        data: {
          LoginForm: {
            username: $scope.user.username,
            password: $scope.user.password
          },
          "login-button": ""
        }
      }).then(
        function successCallback(data) {
          if (data.data.status_code == "202") {
            if ($scope.select_if) {
              localStorage.setItem("username", "");
              localStorage.setItem("password", "");
            } else {
              localStorage.setItem("username", $scope.user.username);
              localStorage.setItem("password", $scope.user.password);
            }
            window.location.href = "/site/index";
          }
          if (data.data.status_code == "1") {
            $scope.verCode();
            if (data.data.errorMessage.username) {
              $scope.errorMessage.username = data.data.errorMessage.username[0];
            }
            if (data.data.errorMessage.allow_ip) {
              $scope.errorMessage.username = data.data.errorMessage.allow_ip[0];
            }
            if (data.data.errorMessage.password) {
              $scope.errorMessage.password = data.data.errorMessage.password[0];
            }
          }
        },
        function errorCallback(data) {}
      );
    } else {
      $scope.errorMessage.code = "验证码输入不正确";
    }
  };
  $scope.username_focus = function() {
    $scope.errorMessage.username = "";
  };
  $scope.password_focus = function() {
    $scope.errorMessage.password = "";
  };
  $scope.code_blur = function() {
    if ($scope.user.code != "") {
      if ($scope.user.code.toUpperCase() != $scope.NumCode) {
        $scope.errorMessage.code = "验证码输入不正确";
        $scope.success_code = false;
      } else {
        $scope.success_code = true;
      }
    } else {
      $scope.errorMessage.code = "请输入验证码";
    }
  };
  $scope.code_focus = function() {
    $scope.errorMessage.code = "";
    $scope.success_code = false;
  };

  $scope.select = function(num) {
    console.log(num);
    $scope.select_if = !$scope.select_if;
  };
  $scope.init();
});
