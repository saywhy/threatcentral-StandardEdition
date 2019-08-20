var myApp = angular.module("myApp", []);
myApp.controller("myAssetsManagement", function(
  $scope,
  $http,
  $filter,
  $sce,
  $timeout
) {
  $scope.$sce = $sce;
  $scope.init = function() {
    $scope.host_add_acttiv_true = false;
    $scope.domain_add_acttiv_true = true;
    $scope.export_risk_data = true;
    $scope.domain_search_if = true;
    $scope.host_search_if = false;
    $scope.tab_active_res = true;
    $scope.host_statusData = [
      {
        num: "存活",
        type: "存活"
      },
      {
        num: "关闭",
        type: "关闭"
      }
    ];
    $scope.domain_statusData = [
      {
        num: "200",
        type: "200"
      },
      {
        num: "404",
        type: "404"
      },
      {
        num: "403",
        type: "403"
      },
      {
        num: "0",
        type: "0"
      }
    ];
    $scope.domain_add = {
      asset_name: "",
      group_name: "",
      domain: "",
      location: "",
      staff_name: "",
      status_code: "200"
    };
    $scope.host_add = {
      host_name: "",
      asset_name: "",
      group_name: "",
      os: "",
      domain: "",
      location: "",
      staff_name: "",
      is_alive: "存活"
    };
    $scope.domain_edit_data = {
      status_code: "200"
    };
    $scope.host_edit_data = {};
    $scope.search = {
      domain_asset_name: "",
      domain_group_name: "",
      host_asset_name: "",
      host_group_name: ""
    };
    $scope.domain_name_list = [];
    $scope.domain_name_list_if = false;
    $scope.domain_get(1);
    $scope.host_get(1);
  };
  $scope.search_res = function() {
    $scope.host_group_list_if = false;
    $scope.host_name_list_if = false;
    $scope.domain_name_list_if = false;
    $scope.domain_group_list_if = false;

    if ($scope.tab_active_res) {
      $scope.domain_get(1);
      $scope.host_data = {
        count: 0,
        data: [],
        maxPage: 0,
        pageNow: 1
      };
    } else {
      $scope.host_get(1);
      $scope.domain_data = {
        count: 0,
        data: [],
        maxPage: 0,
        pageNow: 1
      };
    }
  };

  $scope.tab_active = function(num) {
    if (num == 1) {
      //   网站资产
      $scope.domain_search_if = true;
      $scope.host_search_if = false;
      $scope.tab_active_res = true;
      $scope.domain_name_list_if = false;
      $scope.domain_group_list_if = false;
      $scope.host_name_list_if = false;
      $scope.host_group_list_if = false;
    }
    if (num == 2) {
      //   主机资产
      $scope.domain_search_if = false;
      $scope.host_search_if = true;
      $scope.tab_active_res = false;
      $scope.domain_name_list_if = false;
      $scope.domain_group_list_if = false;
      $scope.host_name_list_if = false;
      $scope.host_group_list_if = false;
    }
  };
  $scope.blur_if = function() {
    $scope.domain_name_list_if = false;
    $scope.domain_group_list_if = false;
    $scope.host_name_list_if = false;
    $scope.host_group_list_if = false;
  };
  //    获取网站资产
  $scope.domain_get = function(page) {
    $scope.domain_name_list_if = false;
    $scope.domain_page = page;
    $scope.domain_search = {
      asset_name: $scope.search.domain_asset_name,
      group_name: $scope.search.domain_group_name,
      page: page,
      rows: 10
    };
    $http({
      method: "get",
      url: "/assets/website-assets-list",
      params: $scope.domain_search
    }).then(
      function(data) {
        $scope.domain_data = data.data.data;
        angular.forEach($scope.domain_data.data, function(item) {
          //   item.location_cn = unescape(item.location.replace(/\u/g, "%u"));
        });
      },
      function() {}
    );
  };
  //  网站资产名称模糊搜索
  $scope.myKeyup_domain_name = function(name) {
    $scope.domain_name_list_if = true;
    $http({
      method: "get",
      url: "/assets/website-asset-name",
      params: {
        asset_name: name
      }
    }).then(
      function(data) {
        console.log(data);
        $scope.domain_name_list = data.data.data;
      },
      function() {}
    );
  };
  $scope.domain_name_mouseleaver = function() {
    $scope.domain_name_list_if = false;
  };

  //   获取焦点
  $scope.get_domain_name_focus = function() {
    $scope.myKeyup_domain_name($scope.search.domain_asset_name);
    $scope.domain_name_list_if = true;
    $scope.domain_group_list_if = false;
  };
  $scope.domain_name_list_click = function(name) {
    $scope.search.domain_asset_name = name;
    $scope.domain_name_list_if = false;
  };
  $scope.domain_group_mouseleaver = function() {
    $scope.domain_group_list_if = false;
  };
  // 网站资产分组模糊搜索
  $scope.myKeyup_domain_group = function(name) {
    console.log(name);
    $scope.domain_group_list_if = true;
    $http({
      method: "get",
      url: "/assets/website-asset-group",
      params: {
        group_name: name
      }
    }).then(
      function(data) {
        console.log(data);
        $scope.domain_group_list = data.data.data;
      },
      function() {}
    );
  };
  //   获取焦点
  $scope.get_group_name_focus = function() {
    $scope.myKeyup_domain_group($scope.search.domain_group_name);
    $scope.domain_name_list_if = false;
    $scope.domain_group_list_if = true;
  };
  $scope.group_name_list_click = function(name) {
    $scope.search.domain_group_name = name;
    $scope.domain_group_list_if = false;
  };

  //   编辑网站资产
  $scope.edit_domain = function(item) {
    $scope.domain_edit_data = angular.copy(item);
    var W = 650;
    var H = 350;
    var box = null;
    box = zeroModal.show({
      title: "编辑网站资产",
      content: domain_edit,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        // 编辑网站资产
        $http({
          method: "put",
          url: "/assets/website-asset-edit",
          data: {
            id: $scope.domain_edit_data.id,
            asset_name: $scope.domain_edit_data.asset_name,
            group_name: $scope.domain_edit_data.group_name,
            status_code: $scope.domain_edit_data.status_code,
            domain: $scope.domain_edit_data.domain,
            location: $scope.domain_edit_data.location,
            staff_name: $scope.domain_edit_data.staff_name
          }
        }).then(
          function(data) {
            if (data.data.status == "success") {
              zeroModal.success("修改网站资产成功");
              $scope.domain_get($scope.domain_page);
            } else {
              zeroModal.error(data.data.errorMessage);
            }
          },
          function() {}
        );
      },
      onCleanup: function() {
        hideenBox_domain_edit.appendChild(domain_edit);
      }
    });
  };
  //   删除网站资产
  $scope.del_domain = function(name) {
    zeroModal.confirm({
      content: "确定删除网站资产" + name + "吗？",
      okFn: function() {
        $http({
          method: "del",
          url: "/assets/website-asset-del",
          data: {
            asset_name: name
          }
        }).then(
          function(data) {
            if (data.data.status == "success") {
              zeroModal.success("删除网站资产成功");
              $scope.domain_get($scope.domain_page);
            } else {
              zeroModal.error(data.data.errorMessage);
            }
          },
          function() {}
        );
      },
      cancelFn: function() {}
    });
  };
  //    获取主机资产
  $scope.host_get = function(page) {
    $scope.host_page = page;
    $scope.host_search = {
      asset_name: $scope.search.host_asset_name,
      group_name: $scope.search.host_group_name,
      page: page,
      rows: 10
    };
    $http({
      method: "get",
      url: "/assets/host-assets-list",
      params: $scope.host_search
    }).then(
      function(data) {
        $scope.host_data = data.data.data;
      },
      function() {}
    );
  };
  //   编辑主机资产
  $scope.edit_host = function(item) {
    $scope.host_edit_data = angular.copy(item);
    var W = 650;
    var H = 350;
    var box = null;
    box = zeroModal.show({
      title: "编辑主机资产",
      content: host_edit,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        // 编辑主机资产
        $http({
          method: "put",
          url: "/assets/host-asset-edit",
          data: {
            id: $scope.host_edit_data.id,
            asset_name: $scope.host_edit_data.asset_name,
            host_name: $scope.host_edit_data.host_name,
            os: $scope.host_edit_data.os,
            group_name: $scope.host_edit_data.group_name,
            is_alive: $scope.host_edit_data.is_alive,
            domain: $scope.host_edit_data.domain,
            location: $scope.host_edit_data.location,
            staff_name: $scope.host_edit_data.staff_name
          }
        }).then(
          function(data) {
            if (data.data.status == "success") {
              zeroModal.success("修改主机资产成功");
              $scope.host_get($scope.host_page);
            } else {
              zeroModal.error(data.data.errorMessage);
            }
          },
          function() {}
        );
      },
      onCleanup: function() {
        hideenBox_host_edit.appendChild(host_edit);
      }
    });
  };
  //   删除主机资产
  $scope.del_host = function(name) {
    zeroModal.confirm({
      content: "确定删除主机资产" + name + "吗？",
      okFn: function() {
        $http({
          method: "del",
          url: "/assets/host-asset-del",
          data: {
            asset_name: name
          }
        }).then(
          function(data) {
            if (data.data.status == "success") {
              zeroModal.success("删除网站资产成功");
              $scope.host_get($scope.host_page);
            } else {
              zeroModal.error(data.data.errorMessage);
            }
          },
          function() {}
        );
      },
      cancelFn: function() {}
    });
  };

  //  z主机资产名称模糊搜索
  $scope.myKeyup_host_name = function(name) {
    $scope.host_name_list_if = true;
    $http({
      method: "get",
      url: "/assets/host-asset-name",
      params: {
        asset_name: name
      }
    }).then(
      function(data) {
        console.log(data);
        $scope.host_name_list = data.data.data;
      },
      function() {}
    );
  };
  $scope.host_name_mouseleaver = function() {
    $scope.host_name_list_if = false;
  };

  //   获取焦点
  $scope.get_host_name_focus = function() {
    $scope.myKeyup_host_name($scope.search.host_asset_name);
    $scope.host_name_list_if = true;
    $scope.host_group_list_if = false;
  };
  $scope.host_name_list_click = function(name) {
    $scope.search.host_asset_name = name;
    $scope.host_name_list_if = false;
  };
  // 主机资产分组模糊搜索
  $scope.myKeyup_host_group = function(name) {
    console.log(name);
    $scope.host_group_list_if = true;
    $http({
      method: "get",
      url: "/assets/host-asset-group",
      params: {
        group_name: name
      }
    }).then(
      function(data) {
        console.log(data);
        $scope.host_group_list = data.data.data;
      },
      function() {}
    );
  };
  //   获取焦点
  $scope.get_host_group_focus = function() {
    $scope.myKeyup_host_group($scope.search.host_group_name);
    $scope.host_name_list_if = false;
    $scope.host_group_list_if = true;
  };
  $scope.host_group_list_click = function(name) {
    $scope.search.host_group_name = name;
    $scope.host_group_list_if = false;
  };

  // 添加资产
  $scope.add = function() {
    var W = 650;
    var H = 350;
    var box = null;
    box = zeroModal.show({
      title: "添加资产",
      content: add_risk,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        //   添加主机资产
        if ($scope.host_add_acttiv_true) {
          console.log($scope.host_add);
          if ($scope.host_add.asset_name == "") {
            zeroModal.error("主机地址不能为空");
          } else {
            $http({
              method: "post",
              url: "/assets/host-assets-add",
              data: $scope.host_add
            }).then(
              function(data) {
                if (data.data.status == "success") {
                  zeroModal.success("添加主机资产成功");
                  $scope.host_get(1);
                  $scope.host_add = {
                    host_name: "",
                    asset_name: "",
                    group_name: "",
                    os: "",
                    domain: "",
                    location: "",
                    staff_name: "",
                    is_alive: "存活"
                  };
                  $scope.domain_add = {
                    asset_name: "",
                    group_name: "",
                    domain: "",
                    location: "",
                    staff_name: "",
                    status_code: "200"
                  };
                } else {
                  zeroModal.error(data.data.errorMessage);
                }
              },
              function() {}
            );
          }
        }
        // 添加网站资产
        if ($scope.domain_add_acttiv_true) {
          if ($scope.domain_add.asset_name == "") {
            zeroModal.error("资产名称不能为空");
          } else {
            $http({
              method: "post",
              url: "/assets/website-assets-add",
              data: $scope.domain_add
            }).then(
              function(data) {
                if (data.data.status == "success") {
                  zeroModal.success("添加网站资产成功");
                  $scope.domain_get(1);
                  $scope.domain_add = {
                    asset_name: "",
                    group_name: "",
                    domain: "",
                    location: "",
                    staff_name: "",
                    status_code: "200"
                  };
                  $scope.host_add = {
                    host_name: "",
                    asset_name: "",
                    group_name: "",
                    os: "",
                    domain: "",
                    location: "",
                    staff_name: "",
                    is_alive: "存活"
                  };
                } else {
                  zeroModal.error(data.data.errorMessage);
                }
              },
              function() {}
            );
          }
        }
      },
      onCleanup: function() {
        hideenBox.appendChild(add_risk);
      }
    });
  };
  $scope.domain_add_acttiv = function() {
    $scope.host_add_acttiv_true = false;
    $scope.domain_add_acttiv_true = true;
  };
  $scope.host_add_acttiv = function() {
    $scope.host_add_acttiv_true = true;
    $scope.domain_add_acttiv_true = false;
  };
  // 同步资产
  $scope.sync_risk = function() {
    var loading = zeroModal.loading(4);
    $http({
      method: "get",
      url: "/assets/synchronous-assets"
    }).then(
      function(data) {
        zeroModal.close(loading);
        if ((data.data.status = "success")) {
          zeroModal.success("同步成功");
          // $scope.search_res();
          $scope.domain_get(1);
          $scope.host_get(1);
        }
      },
      function() {}
    );
  };

  // 导出资产
  $scope.export_risk = function() {
    if ($scope.export_risk_data) {
      // 导出主机资产
      var tt = new Date().getTime();
      var url = "/assets/host-assets-export";
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
    } else {
      // 导出网站资产
      var dd = new Date().getTime();
      var url = "/assets/website-assets-export";
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
    }
  };
  // 批量导入资产
  $scope.batch_import = function() {
    var W = 650;
    var H = 250;
    var box = null;
    box = zeroModal.show({
      title: "批量导入/导出",
      content: batch_import,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      okFn: function() {
        $scope.add_whitelist($scope.white_list);
      },
      onCleanup: function() {
        batch_import_hideenBox.appendChild(batch_import);
      }
    });
  };
  // 下载模版
  $scope.download_template = function() {
    var tt = new Date().getTime();
    var url = "/assets/asset-template";
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
  // 上传主机资产
  $scope.import_host_risk = function() {
    $("#avatar").trigger("click");
  };
  $("#avatar").change(function(target) {
    $("#avatval").val($(this).val());
    if (target.target.value) {
      // if (target.target.value.split(".")[1].indexOf("xls") == -1) {
      //   zeroModal.error(" 请重新选择.xls");
      // } else {
      $scope.import_host();
      // }
    }
  });
  $scope.import_host = function() {
    var loading = zeroModal.loading(4);
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
            }
          };
          xhr.upload.onloadstart = function() {};
        }
        return xhr;
      },
      processData: false, // 告诉jQuery不要去处理发送的数据
      contentType: false, // 告诉jQuery不要去设置Content-Type请求头
      success: function(res) {
        zeroModal.close(loading);
        if (res.status == "success") {
          $("#upload")[0].reset();
          zeroModal.success("上传成功");
          $scope.host_get(1);
        } else {
          zeroModal.error("上传失败");
        }
      },
      error: function(err) {
        console.log(err);
      }
    });
  };

  //  上传网站资产
  $scope.import_website_risk = function() {
    $("#avatar_app").trigger("click");
  };
  $("#avatar_app").change(function(target) {
    $("#avatval_app").val($(this).val());
    if (target.target.value) {
      //   if (target.target.value.split(".")[1].indexOf("yar") == -1) {
      //     zeroModal.error(" 请重新选择.excel格式的文件上传");
      //   } else {
      $scope.import_website();
      //   }
    }
  });
  $scope.import_website = function() {
    var loading = zeroModal.loading(4);
    var form = document.getElementById("upload_app"),
      formData = new FormData(form);
    $.ajax({
      url: "/info-import/import-website",
      type: "post",
      data: formData,
      xhr: function() {
        var xhr = $.ajaxSettings.xhr();
        if (xhr.upload) {
          xhr.upload.onprogress = function(progress) {
            if (progress.lengthComputable) {
            }
          };
          xhr.upload.onloadstart = function() {};
        }
        return xhr;
      },
      processData: false, // 告诉jQuery不要去处理发送的数据
      contentType: false, // 告诉jQuery不要去设置Content-Type请求头
      success: function(res) {
        zeroModal.close(loading);
        if (res.status == "success") {
          $("#upload_app")[0].reset();
          zeroModal.success("上传成功");
          $scope.domain_get(1);
        } else {
          zeroModal.error("上传失败");
        }
      },
      error: function(err) {
        console.log(err);
      }
    });
  };

  $scope.table_click = function() {
    $scope.host_group_list_if = false;
    $scope.host_name_list_if = false;
    $scope.domain_name_list_if = false;
    $scope.domain_group_list_if = false;
  };

  $scope.init();
});
