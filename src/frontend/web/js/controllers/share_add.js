var loading_file = null;
if (!FileReader.prototype.readAsBinaryString) {
  FileReader.prototype.readAsBinaryString = function(fileData) {
    var binary = "";
    var pt = this;
    var reader = new FileReader();
    reader.onload = function(e) {
      var bytes = new Uint8Array(reader.result);
      var length = bytes.byteLength;
      for (var i = 0; i < length; i++) {
        binary += String.fromCharCode(bytes[i]);
      }
      reader.result = binary;
      pt.onload({
        target: {
          result: binary
        }
      });
    };
    reader.readAsArrayBuffer(fileData);
  };
}

function readXLS(file, $scope) {
  var reader = new FileReader();
  reader.onload = function(e) {
    var data = e.target.result;
    var workbook = XLSX.read(data, {
      type: "binary"
    });
    var data = "";
    for (var i = workbook.SheetNames.length - 1; i >= 0; i--) {
      var sheetName = workbook.SheetNames[i];
      var sheet = workbook.Sheets[sheetName];
      for (var key in sheet) {
        var v = sheet[key];
        if (v.w) {
          data += " " + v.w;
        }
      }
    }
    $scope.selectIndicators(getIndicators(data), true);
  };
  reader.readAsBinaryString(file);
}

function readTXT(file, $scope) {
  console.log(12312);
  var reader = new FileReader();
  reader.onload = function(e) {
    var data = e.target.result;
    console.log(data);
    $scope.selectIndicators(getIndicators(data), true);
  };
  reader.readAsText(file);
}

function readAjaxContent(file, $scope, $http) {
  var fd = new FormData();
  fd.append("file", file);
  console.log(file);

  $http
    .post("/share/read-file", fd, {
      transformRequest: angular.identity,
      headers: {
        "Content-Type": undefined
      }
    })
    .then(
      function success(rsp) {
        console.log(rsp);
        if (rsp.data.status == "success") {
          var dom = document.createElement("div");
          dom.innerHTML = rsp.data.data;
          var test_list = dom.getElementsByTagName("p");
          var data = "";
          for (var i = 0; i < test_list.length; i++) {
            var p = test_list[i];
            data += test_list[i].innerText + "\n";
          }
          $scope.selectIndicators(getIndicators(data));
        } else {
          zeroModal.close(loading_file);
          zeroModal.error("此文件无法提取！");
        }
      },
      function err(rsp) {
        zeroModal.close(loading_file);
        zeroModal.error("此文件无法提取！");
      }
    );
}

function getIndicators(data) {
  var list = [];
  var obj = {};

  function pushToList(indicators, type) {
    if (!obj[indicators]) {
      obj[indicators] = {
        indicators: indicators,
        userName: "userName",
        type: type,
        confidence: 100,
        threat: 5
      };
      list.push(obj[indicators]);
      return true;
    }
    return false;
  }

  //IP
  var ipList = data.match(/\d+\.\d+\.\d+\.\d+/g);
  if (ipList) {
    for (var i = ipList.length - 1; i >= 0; i--) {
      pushToList(ipList[i], "IPv4");
    }
  } else {
    ipList = [];
  }
  //domain
  var domainList = data.match(
    /([a-zA-Z0-9][-a-zA-Z0-9]{0,62}|\*)(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+/g
  );
  if (domainList) {
    for (var i = domainList.length - 1; i >= 0; i--) {
      if (
        ipList.indexOf(domainList[i]) == -1 &&
        !/^(-?\d+)(\.\d+)?$/.test(domainList[i]) &&
        !/.*(\.exe|\.txt|\.dll|\.gz|\.zip|\.rar|\.jpg|\.png|\.bmp|\.doc|\.docx|\.xlsx|\.xls|\.ppt|\.pptx)$/.test(
          domainList[i].toLowerCase()
        )
      ) {
        pushToList(domainList[i], "domain");
      }
    }
  }

  //url
  var urlList = data.match(
    /[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+(:\d+\/|\/)\w*/g
  );
  if (urlList) {
    for (var i = urlList.length - 1; i >= 0; i--) {
      pushToList(urlList[i], "URL");
    }
  }

  //md5
  var md5List = data.match(/[a-fA-F0-9]{32}/g);
  if (md5List) {
    for (var i = md5List.length - 1; i >= 0; i--) {
      var md5 = md5List[i].toLowerCase();
      pushToList(md5, "md5");
    }
  }
  if (Object.keys(list).length == 0) {
    zeroModal.error("此文件不包含指标信息！");
    return null;
  } else {
    return {
      list: list,
      obj: obj
    };
  }
}
var myApp = angular.module("myApp", []);
var rootScope;
myApp.controller("ShareAddCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.share = {
      name: "",
      tagNames: [],
      gid: null,
      groupName: "",
      filePath: "",
      describe: "",
      data: [],
      textarea_if: true,
      tag_if: true,
      textarea_info: "",
      tag_list: [
        {
          name: "恶意地址",
          status: false
        },
        {
          name: "僵尸网络",
          status: false
        },
        {
          name: "垃圾邮件",
          status: false
        },
        {
          name: "网络代理",
          status: false
        },
        {
          name: "钓鱼网站",
          status: false
        },
        {
          name: "tor入口节点",
          status: false
        },
        {
          name: "远控木马",
          status: false
        },
        {
          name: "勒索软件",
          status: false
        }
      ]
    };
    $scope.share_file = false;
    $scope.choose_all = false;
    $scope.share_parmas = {
      name: "",
      tagNames: [],
      gid: null,
      textarea_ioc_info: "",
      groupName: "",
      filePath: "",
      describe: "",
      data: []
    };
    $scope.ioc_if = {
      input: true,
      btn: true
    };
  };

  $scope.textarea_if = function(name) {
    if (name == "down") {
      $scope.share.textarea_if = true;
    }
    if (name == "up") {
      $scope.share.textarea_if = false;
    }
  };
  $scope.tag_if = function(name) {
    if (name == "down") {
      $scope.share.tag_if = true;
    }
    if (name == "up") {
      $scope.share.tag_if = false;
    }
  };
  $scope.tag_item_click = function(item) {
    item.status = !item.status;
  };
  $scope.textarea_change = function() {
    console.log("12312");
    if ($scope.share_parmas.textarea_ioc_info != "") {
      $scope.ioc_input_btn_if = true;
    }
  };

  $scope.send = function() {
    console.log($scope.share_parmas);
    if ($scope.share_parmas.textarea_ioc_info != "") {
      $scope.share_parmas.data = $scope.share_parmas.data.concat(
        getIndicators($scope.share_parmas.textarea_ioc_info).list
      );
    }
    console.log($scope.share_parmas.data);

    if ($scope.share_parmas.name == "") {
      zeroModal.error("请填写标题名称");
      return false;
    }
    angular.forEach($scope.share.tag_list, function(item) {
      if (item.status) {
        $scope.share_parmas.tagNames.push(item.name);
      }
    });
    if ($scope.share_parmas.tagNames.length == 0) {
      zeroModal.error("请至少选择一个标签");
      return false;
    }
    //==============
    var loading = null;
    if ($scope.share_file) {
      function submit() {
        if (
          postStatusList["IPv4"] == 1 ||
          postStatusList["domain"] == 1 ||
          postStatusList["URL"] == 1 ||
          postStatusList["md5"] == 1 ||
          postStatusList["file"] == 1
        ) {
          return;
        }
        if (
          postStatusList["IPv4"] == 3 ||
          postStatusList["domain"] == 3 ||
          postStatusList["URL"] == 3 ||
          postStatusList["md5"] == 3 ||
          postStatusList["file"] == 3
        ) {
          zeroModal.error("保存失败！");
          return;
        }
        var loading = zeroModal.loading(4);
        $http.post("/share/insert", $scope.share_parmas).then(
          function success(rsp) {
            zeroModal.close(loading);
            if ((rsp.data.status = "success")) {
              zeroModal.success("发布成功");
              window.location.href = "/share/index";
            }
          },
          function err(rsp) {
            zeroModal.close(loading);
          }
        );
      }
      var postDatas = {
        IPv4: [],
        domain: [],
        URL: [],
        md5: []
      };
      var postStatusList = {
        IPv4: 0,
        domain: 0,
        URL: 0,
        md5: 0,
        file: 0
      };
      var sourceNames = {
        IPv4: "BlackListIPv4_indicators",
        domain: "BlackListDomain_indicators",
        URL: "BlackListURL_indicators",
        md5: "BlackListMD5_indicators"
      };
      var getType = {
        BlackListIPv4_indicators: "IPv4",
        BlackListDomain_indicators: "domain",
        BlackListURL_indicators: "URL",
        BlackListMD5_indicators: "md5"
      };
      console.log($scope.share_parmas.data);

      angular.forEach($scope.share_parmas.data, function(item) {
        postDatas[item.type].push(item);
      });
      console.log(postDatas);
      //-------------往后台发送数据------------------
      var traced_parmas = {
        IPv4: [],
        URL: [],
        domain: [],
        md5: []
      };
      //   angular.forEach(postDatas, function(item) {
      //       if(item.){

      //       }
      //   });

      //   $http.post("/proxy/traced/indicators?t=type", traced_parmas).then(
      //     function success(rsp) {
      //       console.log(rsp);
      //     },
      //     function err(rsp) {}
      //   );
      //-------------------------------
      for (var type in postDatas) {
        var postData = postDatas[type];
        if (postData.length > 0) {
          postStatusList[type] = 1;
          console.log(sourceNames);
          console.log(postData);
          $http
            .post(
              "/proxy/config/data/" + sourceNames[type] + "/append?t=yaml",
              postData
            )
            .then(
              function success(rsp) {
                console.log(rsp);
                var sourceName = rsp.config.url.match(
                  /BlackList\S*indicators/
                )[0];
                var Type = getType[sourceName];
                if (rsp.data.result == "ok") {
                  postStatusList[Type] = 2;
                } else {
                  postStatusList[Type] = 3;
                }
                submit();
              },
              function err(rsp) {
                postStatusList[Type] = 3;
                submit();
              }
            );
        }
      }
      if ($scope.share_parmas.filePath == "") {
        postStatusList["file"] = 1;
        var fd = new FormData();
        fd.append("file", $scope.file);
        $http
          .post("/share/add-file", fd, {
            transformRequest: angular.identity,
            headers: {
              "Content-Type": undefined
            }
          })
          .then(
            function success(rsp) {
              if (rsp.data.status == "success") {
                $scope.share_parmas.filePath = rsp.data.path;
                postStatusList["file"] = 2;
              } else {
                postStatusList["file"] = 3;
              }
              submit();
            },
            function err(rsp) {
              postStatusList[Type] = 3;
              submit();
            }
          );
      }
    } else {
      var loading = zeroModal.loading(4);
      $http.post("/share/insert", $scope.share_parmas).then(
        function success(rsp) {
          zeroModal.close(loading);
          if ((rsp.data.status = "success")) {
            zeroModal.success("发布成功");
            window.location.href = "/share/index";
          }
        },
        function err(rsp) {
          zeroModal.close(loading);
        }
      );
    }
  };

  $scope.open_zeroModal = function() {
    console.log($scope.choose_all);

    $scope.choose_all = false;
    console.log($scope.choose_all);
    $("#InputFile").val("");
    var W = 1000;
    var H = 560;
    zeroModal.show({
      title: "提取指标",
      content: token,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {},
      onCleanup: function() {
        token_box.appendChild(token);
      }
    });
  };
  //   保存
  $scope.token_save = function() {
    $scope.share_parmas.data = [];
    angular.forEach($scope.indicatorsList, function(item) {
      if (item.choose == true) {
        $scope.share_parmas.data.push(item);
      }
    });
    zeroModal.closeAll();
    $scope.choose_all = false;
  };
  $scope.token_cancel = function() {
    zeroModal.closeAll();
    $scope.choose_all = false;
  };
  //  全选
  $scope.choose_click_all = function(type) {
    if (type == "true") {
      $scope.choose_all = false;
      angular.forEach($scope.indicatorsList, function(item) {
        item.choose = false;
      });
    }
    if (type == "false") {
      angular.forEach($scope.indicatorsList, function(item) {
        item.choose = true;
      });
      $scope.choose_all = true;
    }
  };
  $scope.choose_click = function(index, type) {
    if (type == "true") {
      $scope.indicatorsList[index].choose = false;
      $scope.choose_all = false;
      angular.forEach($scope.indicatorsList, function(item) {
        if (item.choose == true) {
          $scope.choose_all = true;
        }
      });
    }
    if (type == "false") {
      $scope.indicatorsList[index].choose = true;
      $scope.choose_all = true;
      angular.forEach($scope.indicatorsList, function(item) {
        if (item.choose == false) {
          $scope.choose_all = false;
        }
      });
    }
  };
  $scope.selectIndicators = function(data, apply) {
    if (data) {
      $scope.indicatorsList = data.list;
      $scope.indicatorsObj = data.obj;
      $scope.indicatorsKeyList = [];
      if (apply) {
        $scope.$apply();
      }
      zeroModal.close(loading_file);
      console.log($scope.indicatorsList);
      angular.forEach($scope.indicatorsList, function(item) {
        item.choose = false;
      });
      $scope.open_zeroModal();
    }
  };
  //   $scope.files_choose = document.getElementById("InputFile");
  //   $scope.files_choose.click();

  $("#InputFile").change(function() {
    $scope.share_file = true;
    var file = this.files[0];
    $scope.file = file;
    $scope.share.name = file.name;
    $scope.share.filePath = "";
    loading_file = zeroModal.loading(4);
    if (
      file.type ==
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
      /.*(\.xlsx|\.xls)$/.test(file.name.toLowerCase())
    ) {
      readXLS(file, $scope);
    } else if (
      /^text\/.*/.test(file.type) ||
      /.*(\.ioc|\.yara)$/.test(file.name.toLowerCase())
    ) {
      readTXT(file, $scope);
    } else if (
      file.type == "application/msword" ||
      file.type ==
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
    ) {
      readAjaxContent(file, $scope, $http);
    } else if (file.type == "application/pdf") {
      readAjaxContent(file, $scope, $http);
    } else {
      zeroModal.close(loading_file);
      var fd = new FormData();
      fd.append("file", $scope.file);
      var loading = zeroModal.loading(4);

      $http
        .post("/share/add-file", fd, {
          transformRequest: angular.identity,
          headers: {
            "Content-Type": undefined
          }
        })
        .then(
          function success(rsp) {
            zeroModal.close(loading);
            console.log(rsp);
            if (rsp.data.status == "success") {
              zeroModal.success("上传成功");
            } else {
              zeroModal.error("上传失败");
            }
          },
          function err(rsp) {
            zeroModal.error("上传失败");
          }
        );
    }
    console.log($scope.share);
  });

  $scope.init();
});
