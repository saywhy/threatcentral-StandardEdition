var rootScope;
var myApp = angular.module("myApp", ["ngSanitize"]);
myApp.controller("searchCtrl", function ($scope, $http, $filter, $sce) {
    rootScope = $scope;
    $scope.init = function () {
        $scope.reputation_res_if = false;
        $scope.loophole_res_if = false;
        $scope.reputation_search = "";
        $scope.loophole_search = "";
        $scope.demo = "";
        $scope.custom = {
            title: "",
            risk: ""
        };
        $scope.tab_active = true;
        if (sessionStorage.getItem("tab_active") == "false") {
            $scope.tab_active = false;
        }
        $scope.loop_status_str = {
            NEW: {
                label: "新预警",
                css: "success"
            },
            CONFIRMED: {
                label: "已处理",
                css: "default"
            },
            IGNORED: {
                label: "已忽略",
                css: "danger"
            }
        };
        $scope.level = '';
        //漏洞级别
        $scope.custom_level = [{
                num: '',
                status: '漏洞级别'
            },
            {
                num: '高',
                status: '高'
            },
            {
                num: '中',
                status: '中'
            },
            {
                num: '低',
                status: '低'
            }
        ];
        $scope.custom_edit_data = {};
        $scope.custom_list_get(1);
        $scope.loophole_get(1);
        $scope.enter();
        $scope.get_centralmanage_self();
        $scope.get_lookup_license();
    };

    $scope.enter = function () {
        document.onkeydown = function (e) {
            // 兼容FF和IE和Opera
            var theEvent = e || window.event;
            var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
            if (code == 13) {
                //回车执行查询
                $scope.$apply(function () {
                    if ($scope.tab_active) {
                        $scope.reputation_get();
                    } else {
                        $scope.loophole_get();
                    }
                });
            }
        };
    };

    $scope.tab_click = function (item) {
        if (item == 1) {
            $scope.tab_active = true;
            sessionStorage.setItem("tab_active", "true");
        } else {
            sessionStorage.setItem("tab_active", "false");
            $scope.tab_active = false;
        }
    };
    //   获取状态设备角色
    $scope.get_centralmanage_self = function () {
        var loading = zeroModal.loading(4);
        $http({
            method: "get",
            url: "/site/dev-self"
        }).then(
            function successCallback(data) {
                zeroModal.close(loading);
                $scope.centralmanage_self = data.data.data;
                if ($scope.centralmanage_self.role_type == "manage") {
                    $scope.set_true = true;
                }
                if ($scope.centralmanage_self.role_type == "branch") {
                    $scope.set_true = false;
                }
            },
            function errorCallback(data) {
                zeroModal.close(loading);
                zeroModal.error(data.data.message);
            }
        );
    };

    //   获取license
    $scope.get_lookup_license = function () {
        console.log("12121");
        $http({
            method: "get",
            url: "/intelligence/license"
        }).then(
            function successCallback(data) {
                console.log(data);
                $scope.lookup_license = data.data.data.result;
            },
            function errorCallback(data) {}
        );
    };

    // 信誉查询
    $scope.reputation_get = function () {
        $scope.reputation_res_if = false;
        if ($scope.reputation_search == "") {
            setTimeout(() => {
                zeroModal.error("请输入IP、hash、url或者域名");
            }, 100);
        } else {
            var loading = zeroModal.loading(4);
            $http({
                method: "get",
                url: "/intelligence/reputation",
                params: {
                    indicator: $scope.reputation_search
                }
            }).then(
                function (data) {
                    zeroModal.close(loading);
                    if (data.data.status == "fail") {
                        zeroModal.error(data.data.errorMessage);
                    }
                    if (data.data.status == "success") {
                        if (data.data.data.result == null) {
                            var W = 500;
                            var H = 180;
                            var box = null;
                            box = zeroModal.show({
                                title: "",
                                content: extend,
                                width: W + "px",
                                height: H + "px",
                                ok: false,
                                cancel: false,
                                okFn: function () {},
                                onCleanup: function () {
                                    hideen_extend.appendChild(extend);
                                }
                            });
                        } else {
                            $scope.reputation_res_if = true;
                            $scope.reputation_res = data.data.data.result;
                            if ($scope.reputation_res.hoohoolab_files) {
                                $scope.reputation_res_files = true;
                            }
                            if ($scope.reputation_res.hoohoolab_ip_whois) {
                                $scope.reputation_res.whois = [];
                                $scope.reputation_res_hoohoolab_ip_whois = true;
                                for (var k in $scope.reputation_res.hoohoolab_ip_whois) {
                                    var obj = {};
                                    if (
                                        $scope.reputation_res.sources[0].indexOf("IPReputation") !=
                                        -1
                                    ) {
                                        switch (k) {
                                            //   IP Reputation
                                            case "net_range":
                                                obj.name = "网络地址范围";
                                                break;
                                            case "net_name":
                                                obj.name = "网络名称";
                                                break;
                                            case "descr":
                                                obj.name = "网络描述";
                                                break;
                                            case "created":
                                                obj.name = "网络注册时间";
                                                break;
                                            case "updated":
                                                obj.name = "网络更新时间";
                                                break;
                                            case "country":
                                                obj.name = "网络注册的国家";
                                                break;
                                            case "contact_owner_name":
                                                obj.name = "网络所有者名称";
                                                break;
                                            case "contact_owner_code":
                                                obj.name = "网络所有者编码";
                                                break;
                                            case "contact_owner_country":
                                                obj.name = "网络所有者国家";
                                                break;
                                            case "contact_owner_country":
                                                obj.name = "网络所有者国家";
                                                break;
                                            case "contact_owner_city":
                                                obj.name = "网络所有者城市";
                                                break;
                                            case "contact_owner_email":
                                                obj.name = "网络所有者邮箱";
                                                break;
                                            case "contact_abuse_name":
                                                obj.name = "滥用报告接口人";
                                                break;
                                            case "contact_abuse_email":
                                                obj.name = "滥用报告邮箱";
                                                break;
                                                break;
                                            case "asn":
                                                obj.name = "自主系统号（ASN）";
                                                break;
                                            default:
                                                obj.name = k;
                                                break;
                                        }
                                    } else {
                                        switch (k) {
                                            // Malicious URL whois
                                            // Phishing URL
                                            case "domain":
                                                obj.name = "域名";
                                                break;
                                            case "created":
                                                obj.name = "域名注册时间";
                                                break;
                                            case "updated":
                                                obj.name = "域名更新时间";
                                                break;
                                            case "expires":
                                                obj.name = "域名过期时间";
                                                break;
                                            case "name":
                                                obj.name = "注册人名称";
                                                break;
                                            case "org":
                                                obj.name = "注册人组织";
                                                break;
                                            case "country":
                                                obj.name = "注册人国家";
                                                break;
                                            case "city":
                                                obj.name = "注册人城市";
                                                break;
                                            case "email":
                                                obj.name = "注册人邮箱";
                                                break;
                                            case "email":
                                                obj.name = "注册人邮箱";
                                                break;
                                            case "registrar_name":
                                                obj.name = "注册机构名称";
                                                break;
                                            case "registrar_email":
                                                obj.name = "注册机构邮箱";
                                                break;
                                            case "NS":
                                                obj.name = "域名服务器";
                                                break;
                                            case "NS_ips":
                                                obj.name = "域名服务器IP";
                                                break;
                                            case "MX":
                                                obj.name = "邮件服务器";
                                                break;
                                            case "MX_ips":
                                                obj.name = "邮件服务器IP";
                                                break;
                                            default:
                                                obj.name = k;
                                                break;
                                        }
                                    }
                                    obj.value = $scope.reputation_res.hoohoolab_ip_whois[k];
                                    $scope.reputation_res.whois.push(obj);
                                }
                            }
                        }
                    }
                },
                function () {}
            );
        }
    };

    $scope.cel_extend = function () {
        zeroModal.closeAll();
        // zeroModal.close();
    };
    //   扩展查询
    $scope.search_extend = function (obj) {
        console.log(obj);
        var loading = zeroModal.loading(4);
        $http({
            method: "get",
            url: "/intelligence/extension",
            params: {
                indicator: obj
            }
        }).then(
            function (data) {
                console.log(data);
                zeroModal.close(loading);
                zeroModal.closeAll();
                if (data.data.data.result == null) {
                    zeroModal.error("没有查询到扩展信息");
                    return false;
                }
                for (var k in data.data.data.result) {
                    switch (k) {
                        case "DomainGeneralInfo":
                            //   window.location.href = "/ExtendedQuery.html#/domain?name=" + obj;
                            window.open("/ExtendedQuery.html#/domain?name=" + obj);
                            break;
                        case "FileGeneralInfo":
                            //   window.location.href = "/ExtendedQuery.html#/hash?name=" + obj;
                            window.open("/ExtendedQuery.html#/hash?name=" + obj);
                            break;
                        case "IpGeneralInfo":
                            //   window.location.href = "/ExtendedQuery.html#/ip?name=" + obj;
                            window.open("/ExtendedQuery.html#/ip?name=" + obj);
                            break;
                        case "UrlGeneralInfo":
                            //   window.location.href = "/ExtendedQuery.html#/url?name=" + obj;
                            window.open("/ExtendedQuery.html#/url?name=" + obj);
                            break;
                        default:
                            break;
                    }
                }
            },
            function () {}
        );
    };

    //   漏洞查询
    $scope.loophole_get = function (page) {
        // if ($scope.loophole_search == "") {
        //   setTimeout(() => {
        //     zeroModal.error("请输入漏洞名称");
        //   }, 100);
        // } else {
        if (!page) {
            page = 1;
        }
        var loading = zeroModal.loading(4);
        $http({
            method: "get",
            url: "/intelligence/loophole",
            params: {
                indicator: $scope.loophole_search,
                rows: 10,
                page: page
            }
        }).then(
            function (data) {
                zeroModal.close(loading);
                if (data.data.status == "fail") {
                    zeroModal.error(data.data.errorMessage);
                }
                console.log(data.data);
                if (data.data.status == "success") {
                    if (data.data.data.count == 0) {
                        // zeroModal.error("没有查询到有关漏洞信息");
                        $scope.loophole_res = {
                            count: 0,
                            pageNow: 1
                        };
                    } else {
                        $scope.loophole_res_if = true;
                        $scope.loophole_res = data.data.data;
                        angular.forEach($scope.loophole_res.data.result, function (item) {
                            item.html = $sce.trustAsHtml(item.content);
                        });
                        console.log($scope.loophole_res.data.result);
                    }
                }
            },
            function () {}
        );
        // }
    };
    //一键导出
    $scope.reputation_exp = function () {
        window.open('/intelligence/export-loophole');
    }

    $scope.go_loophole_detail = function (html) {
        // localStorage.setItem("loop_detail_data", html);
        $scope.html_content = html;
        // window.location.href = "/intelligence/loophole-detail";
        var W = 800;
        var H = 600;
        var box = null;
        box = zeroModal.show({
            title: "详情",
            content: html_content,
            width: W + "px",
            height: H + "px",
            ok: false,
            cancel: false,
            okFn: function () {
                $scope.add_whitelist($scope.white_list);
            },
            onCleanup: function () {
                hideenBox.appendChild(html_content);
            }
        });
    };

    //   漏洞poc下载
    $scope.loop_download = function () {
        console.log("下载");
    };

    $scope.download_poc = function (value) {
        if (value == "") {
            return false;
        }
        var funDownload = function (content, filename) {
            // 创建隐藏的可下载链接
            var eleLink = document.createElement("a");
            eleLink.download = filename;
            eleLink.style.display = "none";
            // 字符内容转变成blob地址
            var blob = new Blob([content]);
            eleLink.href = URL.createObjectURL(blob);
            // 触发点击
            document.body.appendChild(eleLink);
            eleLink.click();
            // 然后移除
            document.body.removeChild(eleLink);
        };
        funDownload(value, "poc.dat");
    };
    $scope.download_id = function (item) {
        var funDownload = function (content, filename) {
            // 创建隐藏的可下载链接
            var eleLink = document.createElement("a");
            eleLink.download = filename;
            eleLink.style.display = "none";
            // 字符内容转变成blob地址
            var blob = new Blob([content]);
            eleLink.href = URL.createObjectURL(blob);
            // 触发点击
            document.body.appendChild(eleLink);
            eleLink.click();
            // 然后移除
            document.body.removeChild(eleLink);
        };
        funDownload(item.content, item.title + ".html");
        // console.log($(item.id).get(0));

        // var pdf = new jsPDF("p", "pt", "a4");
        // // 设置打印比例 越大打印越小
        // pdf.internal.scaleFactor = 2;
        // var options = {
        //   pagesplit: true, //设置是否自动分页
        //   background: "#FFFFFF" //如果导出的pdf为黑色背景，需要将导出的html模块内容背景 设置成白色。
        // };
        // var printHtml = $(item.id).get(0); // 页面某一个div里面的内容，通过id获取div内容
        // pdf.addHTML(printHtml, 15, 15, options, function() {
        //   pdf.save(item.title + ".pdf");
        // });
    };

    //   漏洞预警详情
    $scope.loophole_detail = function (item) {
        window.location.href = "/intelligence/detail";
        sessionStorage.setItem("intelligence_detail", JSON.stringify(item));
    };

    //自定义
    $scope.custom_get = function () {
        if ($scope.custom.title == "" || $scope.custom.risk == "") {
            zeroModal.error("请输入漏洞日志名称和请输入漏洞情报名称");
        } else {
            var loading = zeroModal.loading(4);
            $http({
                method: "post",
                url: "/intelligence/custom-rule",
                data: {
                    title: $scope.custom.title,
                    risk_name: $scope.custom.risk
                }
            }).then(
                function (data) {
                    zeroModal.close(loading);
                    if (data.data.status == "fail") {
                        zeroModal.error(data.data.errorMessage);
                    }
                    if (data.data.status == "success") {
                        zeroModal.success("保存成功");
                        $scope.custom_list_get(1);
                    }
                },
                function () {}
            );
        }
    };
    //   获取自定义列表
    $scope.custom_list_get = function (page) {
        $scope.now_page = page;
        $scope.custom_search = {
            page: page,
            rows: 10
        };
        $http({
            method: "get",
            url: "/intelligence/custom-rule-list",
            params: $scope.custom_search
        }).then(
            function (data) {
                console.log(data);
                if (data.data.status == "fail") {
                    zeroModal.error(data.data.errorMessage);
                }
                if (data.data.status == "success") {
                    //   zeroModal.success("保存成功");
                    $scope.custom_data = data.data.data;
                }
            },
            function () {}
        );
    };

    // 删除自定义规则
    $scope.custom_rule_del = function (item) {
        var loading = zeroModal.loading(4);
        $http({
            method: "DELETE",
            url: "/intelligence/custom-rule-del",
            data: {
                title: item.title,
                risk_name: item.risk_name
            }
        }).then(
            function (data) {
                zeroModal.close(loading);
                if (data.data.status == "success") {
                    zeroModal.success("删除成功");
                    $scope.custom_list_get($scope.now_page);
                } else {
                    zeroModal.error("删除失败");
                }
            },
            function () {}
        );
    };

    //   编辑自定义规则
    $scope.custom_rule_edit = function (item) {
        $scope.custom_edit_data = {
            risk_name: item.risk_name,
            title: item.title
        };
        var W = 500;
        var H = 250;
        var box = null;
        box = zeroModal.show({
            title: "编辑",
            content: custom_edit,
            width: W + "px",
            height: H + "px",
            ok: true,
            cancel: true,
            okFn: function () {
                var loading = zeroModal.loading(4);
                $http({
                    method: "PUT",
                    url: "/intelligence/custom-rule-edit",
                    data: {
                        id: item.id,
                        title: $scope.custom_edit_data.title,
                        risk_name: $scope.custom_edit_data.risk_name
                    }
                }).then(
                    function (data) {
                        zeroModal.close(loading);
                        console.log(data);
                        if (data.data.status == "success") {
                            zeroModal.success("修改成功");
                            $scope.custom_list_get($scope.now_page);
                        } else {
                            zeroModal.error("修改失败");
                        }
                    },
                    function () {}
                );
            },
            onCleanup: function () {
                hideenBox_custom_edit.appendChild(custom_edit);
            }
        });
    };
    //   关联漏洞预警列表
    $scope.relation_list = function (item) {
        $scope.get_relation(1, item);
        var W = 800;
        var H = 500;
        var box = null;
        box = zeroModal.show({
            title: "关联预警",
            content: custom_relation_alert,
            width: W + "px",
            height: H + "px",
            ok: false,
            cancel: false,
            okFn: function () {},
            onCleanup: function () {
                hideenBox_relation_alert.appendChild(custom_relation_alert);
            }
        });
    };

    $scope.get_relation = function (page, item) {
        $http({
            method: "get",
            url: "/intelligence/relation-alerts",
            params: {
                id: item.id,
                page: page,
                rows: 10
            }
        }).then(
            function (data) {
                console.log(data);
                $scope.get_relation_data = data.data.data;
                console.log($scope.get_relation_data);
            },
            function () {}
        );
    };
    $scope.init();
});