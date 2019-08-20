var myApp = angular.module("myApp", []);
myApp.controller("infoImportCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    console.log("121");
    $scope.yara_file = false;
    $scope.info_import_get();
  };
  $scope.yara_replace = function() {
    $("#avatar").trigger("click");
  };
  $("#avatar").change(function(target) {
    $("#avatval").val($(this).val());
    if (target.target.value) {
      if (target.target.value.split(".")[1].indexOf("xls") == -1) {
        zeroModal.error(" 请重新选择.xls");
      } else {
        $scope.upload();
      }
    }
  });
  $scope.app_replace = function() {
    $("#avatar_app").trigger("click");
  };
  $("#avatar_app").change(function(target) {
    $("#avatval_app").val($(this).val());
    if (target.target.value) {
      if (target.target.value.split(".")[1].indexOf("yar") == -1) {
        zeroModal.error(" 请重新选择.excel格式的文件上传");
      } else {
        $scope.upload_app();
      }
    }
  });

  $scope.info_import_get = function(params) {
    $http.get("/info-import/get").then(
      function success(data) {
        console.log(data);
        if (data.data.status == "success") {
          $scope.yara_file = true;
        }
        if (data.data.status == "faild") {
          $scope.yara_file = false;
        }
      },
      function err(rsp) {}
    );
  };
  // 下载
  $scope.download = function() {
    var tt = new Date().getTime();
    var url = "/info-import/download-file";
    var form = $("<form>"); //定义一个form表单
    form.attr("style", "display:none");
    form.attr("target", "");
    form.attr("method", "get"); //请求类型
    form.attr("action", url); //请求地址
    $("body").append(form); //将表单放置在web中
    var input1 = $("<input>");
    input1.attr("type", "hidden");
    form.append(input1);
    form.submit(); //表单提交
  };
  // 上传文件
  $scope.upload = function() {
    var form = document.getElementById("upload"),
      formData = new FormData(form);
    $.ajax({
      url: "/info-import/import",
      type: "post",
      data: formData,
      xhr: function() {
        var xhr = $.ajaxSettings.xhr();
        if (xhr.upload) {
          xhr.upload.onprogress = function(progress) {
            if (progress.lengthComputable) {
              // console.log(progress.lengthComputable);
              // console.log(progress.loaded );
              // console.log(progress.total);
            }
          };
          xhr.upload.onloadstart = function() {
            // console.log('started...');
          };
        }
        return xhr;
      },
      processData: false, // 告诉jQuery不要去处理发送的数据
      contentType: false, // 告诉jQuery不要去设置Content-Type请求头
      success: function(res) {
        console.log(res);
        // res = JSON.parse(res);
        // if (typeof res == "string") {
        //   res = JSON.parse(res);
        // }
        if (res.status == "success") {
          $("#upload")[0].reset();
          zeroModal.success("上传成功");
          $scope.$apply(function() {
            $scope.yara_file = true;
          });
        } else {
          zeroModal.error("上传失败");
        }
      },
      error: function(err) {
        console.log(err);
      },
    });
  };
  $scope.upload_app = function() {
    var form = document.getElementById("upload_app"),
      formData = new FormData(form);
    $.ajax({
      url: "./yiiapi/yararule/upload",
      type: "post",
      data: formData,
      xhr: function() {
        var xhr = $.ajaxSettings.xhr();
        if (xhr.upload) {
          xhr.upload.onprogress = function(progress) {
            if (progress.lengthComputable) {
              // console.log(progress.lengthComputable);
              // console.log(progress.loaded );
              // console.log(progress.total);
            }
          };
          xhr.upload.onloadstart = function() {
            // console.log('started...');
          };
        }
        return xhr;
      },
      processData: false, // 告诉jQuery不要去处理发送的数据
      contentType: false, // 告诉jQuery不要去设置Content-Type请求头
      success: function(res) {
        // res = JSON.parse(res);
        if (typeof res == "string") {
          res = JSON.parse(res);
        }
        if (res.status == 0) {
          $("#upload")[0].reset();
          zeroModal.success("上传成功");
          $scope.get_date();
        } else {
          zeroModal.error(res.msg);
        }
      },
      error: function(err) {
        console.log(err);
      },
    });
  };
  //删除
  $scope.del = function() {
    $http.get("/info-import/delete-file").then(
      function success(data) {
        console.log(data);
        if (data.data.status == "success") {
          $("#upload")[0].reset();
          zeroModal.success("删除成功");
          //   $scope.$apply(function() {
          $scope.yara_file = false;
          //   });
        } else {
          zeroModal.error("删除失败");
        }
      },
      function err(rsp) {}
    );
  };
  $scope.init();
});
