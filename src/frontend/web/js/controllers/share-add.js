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
      pt.onload({ target: { result: binary } });
    };
    reader.readAsArrayBuffer(fileData);
  };
}
function readXLS(file, $scope) {
  var reader = new FileReader();
  reader.onload = function(e) {
    var data = e.target.result;
    var workbook = XLSX.read(data, { type: "binary" });
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
  var reader = new FileReader();
  reader.onload = function(e) {
    var data = e.target.result;
    $scope.selectIndicators(getIndicators(data), true);
  };
  reader.readAsText(file);
}

function readAjaxContent(file, $scope, $http) {
  var fd = new FormData();
  fd.append("file", file);
  $http
    .post("/share/read-file", fd, {
      transformRequest: angular.identity,
      headers: { "Content-Type": undefined }
    })
    .then(
      function success(rsp) {
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
          zeroModal.error("此文件无法导入！");
        }
      },
      function err(rsp) {
        zeroModal.close(loading_file);
        zeroModal.error("此文件无法导入！");
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
        userName: userName,
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
    return { list: list, obj: obj };
  }
}
var myApp = angular.module("myApp", []);
var rootScope;

myApp.controller("myCtrl", function($scope, $http, $filter) {
  rootScope = $scope;
  $scope.tab = "import";
  $scope.tabs = ["import", "select", "setData", "setTags"];
  $scope.share = {
    name: "",
    tagNames: [],
    gid: null,
    groupName: "",
    filePath: "",
    describe: "",
    data: null
  };
  $scope.tagTop = TagTop;
  $scope.$watch("tab", function(newValue, oldValue, scope) {
    var noData =
      !$scope.indicatorsKeyList || $scope.indicatorsKeyList.length == 0;
    if (newValue == "setData") {
      if (noData) {
        $scope.back();
      } else {
        $scope.getIndicatorsList();
      }
    }
    if (newValue == "setTags" && noData) {
      $scope.back();
    }
    if (newValue == "select") {
      if (!$scope.indicatorsList || $scope.indicatorsList.length == 0) {
        $scope.back();
      }
    }
  });
  $scope.next = function() {
    var index = $scope.tabs.indexOf($scope.tab);
    $scope.tab = $scope.tabs[index + 1];
  };
  $scope.back = function() {
    var index = $scope.tabs.indexOf($scope.tab);
    $scope.tab = $scope.tabs[index - 1];
  };
  $scope.getIndicatorsList = function() {
    console.log("123123");
    var postData = {
      IPv4: [],
      domain: [],
      URL: [],
      md5: []
    };

    var ispost = false;
    for (var i in $scope.indicatorsKeyList) {
      var key = $scope.indicatorsKeyList[i];
      var obj = $scope.indicatorsObj[key];
      if (!obj.asked) {
        postData[obj.type].push(key);
      }
    }

    var count =
      postData.IPv4.length +
      postData.domain.length +
      postData.URL.length +
      postData.md5.length;
    if (count) {
      var loading = zeroModal.loading(4);
      $http.post("/proxy/traced/indicators?t=type", postData).then(
        function success(rsp) {
          console.log(rsp);

          var postList = postData.IPv4.concat(
            postData.domain,
            postData.URL,
            postData.md5
          );
          for (var i = postList.length - 1; i >= 0; i--) {
            var key = postList[i];
            $scope.indicatorsObj[key].asked = true;
          }
          for (var key in rsp.data.result) {
            $scope.indicatorsObj[key] = rsp.data.result[key];
            $scope.indicatorsObj[key].old = true;
            $scope.indicatorsObj[key].asked = true;
            $scope.indicatorsObj[key].indicators = key;
          }
          zeroModal.close(loading);
        },
        function err(rsp) {
          zeroModal.close(loading);
        }
      );
    }
  };

  $scope.selectIndicators = function(data, apply) {
    if (data) {
      $scope.indicatorsList = data.list;
      $scope.indicatorsObj = data.obj;
      $scope.indicatorsKeyList = [];
      $scope.next();
      if (apply) {
        $scope.$apply();
      }
      zeroModal.close(loading_file);
    }
  };
  $scope.selectOne = function(item) {
    var index = $scope.indicatorsKeyList.indexOf(item.indicators);
    if (index == -1) {
      $scope.indicatorsKeyList.push(item.indicators);
    } else {
      $scope.indicatorsKeyList.splice(index, 1);
    }
  };
  $scope.selectAll = function() {
    if ($scope.indicatorsKeyList.length == $scope.indicatorsList.length) {
      $scope.indicatorsKeyList = [];
    } else {
      $scope.indicatorsKeyList = [];
      for (var index in $scope.indicatorsList) {
        var item = $scope.indicatorsList[index];
        $scope.indicatorsKeyList.push(item.indicators);
      }
    }
  };

  $scope.save = function() {
    $scope.share.name = $scope.share.name.trim();
    if (!$scope.share.name || $scope.share.name == "") {
      zeroModal.error("请填写分享名称！");
      return false;
    }
    if ($scope.share.gid == null) {
      zeroModal.error("请选择用户分组！");
      return false;
    }
    $scope.share.data = [];
    for (var i = $scope.indicatorsKeyList.length - 1; i >= 0; i--) {
      var key = $scope.indicatorsKeyList[i];
      $scope.share.data.push($scope.indicatorsObj[key]);
    }

    var loading = null;
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
        zeroModal.close(loading);
        zeroModal.error("保存失败！");
        return;
      }
      $http.post("/share/insert", $scope.share).then(
        function success(rsp) {
          if (rsp.data.status == "success") {
            // window.location.href = "/share/" + rsp.data.id;
          } else {
            zeroModal.close(loading);
            zeroModal.error("保存失败！");
          }
        },
        function err(rsp) {
          zeroModal.close(loading);
          zeroModal.error("保存失败！");
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

    for (var i = $scope.share.data.length - 1; i >= 0; i--) {
      var item = $scope.share.data[i];
      postDatas[item.type].push(item);
    }

    for (var type in postDatas) {
      var postData = postDatas[type];
      if (postData.length > 0) {
        postStatusList[type] = 1;
        if (loading == null) {
          loading = zeroModal.loading(4);
        }
        $http
          .post(
            "/proxy/config/data/" + sourceNames[type] + "/append?t=yaml",
            postData
          )
          .then(
            function success(rsp) {
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
    if ($scope.share.filePath == "") {
      postStatusList["file"] = 1;
      var fd = new FormData();
      fd.append("file", $scope.file);
      $http
        .post("/share/add-file", fd, {
          transformRequest: angular.identity,
          headers: { "Content-Type": undefined }
        })
        .then(
          function success(rsp) {
            if (rsp.data.status == "success") {
              $scope.share.filePath = rsp.data.path;
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
  };

  $scope.addTagName = function(tagName) {
    if ($scope.tagText.trim() == "") {
      var tagList = [];
    } else {
      var tagList = $scope.tagText.replace(/，/g, ",").split(",");
    }
    if (tagList.indexOf(tagName) == -1) {
      tagList.push(tagName);
      $scope.tagText = tagList.join(",");
    }
  };

  $scope.showTagBox = function() {
    var W = 480;
    var H = 360;
    $scope.tagText = "";
    zeroModal.show({
      title: "请输入或选择标签",
      content: tagBox,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        if ($scope.tagText.trim() == "") {
          zeroModal.error("请输入或选择标签！");
          return false;
        }
        var tagList = $scope.tagText.replace(/，/g, ",").split(",");
        for (var i = tagList.length - 1; i >= 0; i--) {
          var tag = tagList[i].trim();
          if (tag.length > 30) {
            zeroModal.error("标签长度不要超过30个字符！");
            return false;
          }
        }
        for (var i = tagList.length - 1; i >= 0; i--) {
          var tag = tagList[i].trim();
          if ($scope.share.tagNames.indexOf(tag) == -1 && tag != "") {
            $scope.share.tagNames.push(tag);
          }
        }
        $scope.$apply();
      },
      onCleanup: function() {
        hide_box.appendChild(tagBox);
      }
    });
  };

  $scope.addGroups = function() {
    var W = 480;
    var H = 360;
    zeroModal.show({
      title: "请选择用户分组！",
      content: groupTree,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        if (!nowGroup) {
          zeroModal.error("请选择用户分组！");
          return false;
        }
        if (nowGroup.type == 1) {
          zeroModal.error("不能加入自动分组！");
          return false;
        }
        $scope.share.gid = nowGroup.id;
        $scope.share.groupName = nowGroup.text;
        $scope.$apply();
      },
      onCleanup: function() {
        hide_box.appendChild(groupTree);
      }
    });
  };
  var GroupTree = [
    {
      id: 0,
      text: "所有用户"
    }
  ];
  var Groups = {};

  for (var i = 0; i < GroupList.length; i++) {
    var group = GroupList[i];
    Groups[group.id] = group;
    group.type = "" + group.type;
    if (group.pid == 0) {
      GroupTree.push(group);
    } else {
      if (!Groups[group.pid].nodes) {
        Groups[group.pid].nodes = [];
      }
      Groups[group.pid].nodes.push(group);
    }
  }
  var treeDom;

  function updateTree() {
    treeDom = $("#groupTree").treeview({
      color: "#428bca",
      data: GroupTree,
      onNodeSelected: function(event, node) {
        nowGroup = node;
      },
      onNodeUnselected: function(event, node) {
        nowGroup = null;
      }
    });
  }
  updateTree();

  $("#select").click();
  $("#InputFile").change(function() {
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
      zeroModal.error("此文件无法导入！");
    }
  });

  $("#addTagName").tooltip();
  $("#describe").autoTextarea({});
});
