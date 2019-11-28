var myApp = angular.module("myApp", []);
myApp.controller("PrototypeCtrl", function ($scope, $http, $filter) {
    var loading = null;
    $scope.init = function () {
        $scope.select = {
            model: "1"
        };
        $scope.select_model = [{
                num: "1",
                type: "商业情报"
            },
            {
                num: "2",
                type: "开源情报"
            }
        ];
        $scope.source_data = {
            red: [],
            green: []
        };
        $scope.full_data = [];
        $scope.prototype_list = [];
        $scope.full_cyberhunt_data = [];
        $scope.get_data();
    };
    $scope.get_data = function () {
        $scope.loading = zeroModal.loading(4);
        $http.get("/proxy/config/full").then(
            function success(rsp) {
                // $scope.open_choose = false;
                $scope.full_data = [];
                $scope.full_cyberhunt_data = [];
                if (rsp.data.result) {
                    $scope.full_version = rsp.data.result.version;
                    $scope.full_data = rsp.data.result.nodes;
                    angular.forEach($scope.full_data, function (item, index) {
                        if (item != null) {
                            item.id = index;
                            if (item.properties.inputs.length == 0) {
                                item.choose = true;
                            } else {
                                item.choose = false;
                            }
                        }
                    });
                    $http.get("/proxy/status/cyberhunt").then(
                        function success(rsp) {
                            if (rsp.data.result) {
                                $scope.cyberhunt_data = rsp.data.result;
                                angular.forEach($scope.full_data, function (item) {
                                    if (item != null) {
                                        angular.forEach($scope.cyberhunt_data, function (ele) {
                                            var obj = {};
                                            if (item.name == ele.name) {
                                                obj.name = item.name;
                                                obj.prototype = item.properties.prototype;
                                                obj.prototype_name = item.properties.prototype.split(
                                                    "."
                                                )[1];
                                                if (ele.last_successful_run) {
                                                    obj.last_successful_run = ele.last_successful_run;
                                                } else {
                                                    obj.last_successful_run = "";
                                                }
                                                if (ele.last_run) {
                                                    obj.last_run = ele.last_run;
                                                } else {
                                                    obj.last_run = "";
                                                }
                                                if (ele.sub_state) {
                                                    obj.sub_state = ele.sub_state;
                                                } else {
                                                    obj.sub_state = "";
                                                }
                                                if (ele.inputs.length == 0) {
                                                    obj.choose = true;
                                                } else {
                                                    obj.choose = false;
                                                }
                                                $scope.full_cyberhunt_data.push(obj);
                                            }
                                        });
                                    }
                                });
                                $scope.get_prototype();
                            }
                        },
                        function err(rsp) {
                            $scope.apiErr(rsp);
                        }
                    );
                }
            },
            function err(rsp) {
                $scope.apiErr(rsp);
            }
        );
    };
    $scope.get_prototype = function () {
        $http.get("/proxy/prototype?f=local").then(
            function success(rsp) {
                if (rsp.data.result) {
                    $scope.source_data = {
                        red: [],
                        green: []
                    };
                    zeroModal.close($scope.loading);
                    $scope.prototype_data = rsp.data.result;
                    for (var k in $scope.prototype_data) {
                        for (var item in $scope.prototype_data[k].prototypes) {
                            var obj = {};
                            obj.key = k;
                            obj.name = item;
                            obj.class = $scope.prototype_data[k].prototypes[item].class;
                            obj.tags = $scope.prototype_data[k].prototypes[item].tags;
                            obj.config = $scope.prototype_data[k].prototypes[item].config;
                            obj.node_type = $scope.prototype_data[k].prototypes[item].node_type;
                            obj.nodeType = $scope.prototype_data[k].prototypes[item].node_type;
                            obj.indicator_types = $scope.prototype_data[k].prototypes[item].indicator_types;
                            obj.indicatorTypes = $scope.prototype_data[k].prototypes[item].indicator_types;
                            obj.description = $scope.prototype_data[k].prototypes[item].description;
                            obj.author = $scope.prototype_data[k].prototypes[item].author;
                            if ($scope.prototype_data[k].prototypes[item].tags) {
                                if (
                                    $scope.prototype_data[k].prototypes[item].tags.length == 0
                                ) {
                                    obj.type = "green";
                                } else {
                                    angular.forEach(
                                        $scope.prototype_data[k].prototypes[item].tags,
                                        function (ele) {
                                            if (ele == "ShareLevelGreen") {
                                                obj.type = "green";
                                            }
                                            if (ele == "ShareLevelRed") {
                                                obj.type = "red";
                                            }
                                        }
                                    );
                                }
                            } else {
                                obj.type = "green";
                            }
                            if ($scope.prototype_data[k].prototypes[item].config.attributes) {
                                if (
                                    $scope.prototype_data[k].prototypes[item].config.attributes
                                    .confidence
                                ) {
                                    obj.confidence =
                                        $scope.prototype_data[k].prototypes[
                                            item
                                        ].config.attributes.confidence;
                                } else {
                                    obj.confidence = 0;
                                }
                            } else {
                                obj.confidence = 0;
                            }
                            if (obj.type == "green") {
                                $scope.source_data.green.push(obj);
                            }
                            if (obj.type == "red") {
                                $scope.source_data.red.push(obj);
                            }
                        }
                    }
                    angular.forEach($scope.source_data.red, function (item) {
                        item.choose = false;
                        item.last_successful_run = "";
                        item.last_run = "";
                        item.sub_state = "";
                    });
                    angular.forEach($scope.source_data.green, function (item) {
                        item.choose = false;
                        item.last_successful_run = "";
                        item.last_run = "";
                        item.sub_state = "";
                    });
                    //   判断开启状态
                    angular.forEach($scope.source_data.red, function (item) {
                        angular.forEach($scope.full_data, function (ele) {
                            if (ele != null) {
                                if (item.name == ele.properties.prototype.split(".")[1]) {
                                    if (ele.choose) {
                                        item.choose = ele.choose;
                                    }
                                    item.id = ele.id;
                                    item.version = ele.version;
                                }
                            }
                        });
                    });
                    angular.forEach($scope.source_data.green, function (item) {
                        angular.forEach($scope.full_data, function (ele) {
                            if (ele != null) {
                                if (item.name == ele.properties.prototype.split(".")[1]) {
                                    if (ele.choose) {
                                        item.choose = ele.choose;
                                    }
                                    item.id = ele.id;
                                    item.version = ele.version;
                                }
                            }
                        });
                    });
                    //   更新时间和状态
                    angular.forEach($scope.source_data.red, function (item) {
                        angular.forEach($scope.full_cyberhunt_data, function (ele) {
                            if (item.key + '.' + item.name == ele.prototype) {
                                item.last_successful_run = ele.last_successful_run;
                                item.last_run = ele.last_run;
                                item.sub_state = ele.sub_state;
                                if (item.sub_state == "SUCCESS") {
                                    item.sub_state_cn = "成功";
                                } else if (item.sub_state == "ERROR") {
                                    item.sub_state_cn = "失败";
                                } else {
                                    item.sub_state_cn = item.sub_state;
                                }
                            }
                        });
                    });
                    angular.forEach($scope.source_data.green, function (item) {
                        angular.forEach($scope.full_cyberhunt_data, function (ele) {
                            if (item.key + '.' + item.name == ele.prototype) {
                                item.last_successful_run = ele.last_successful_run;
                                item.last_run = ele.last_run;
                                item.sub_state = ele.sub_state;
                                if (item.sub_state == "SUCCESS") {
                                    item.sub_state_cn = "成功";
                                } else if (item.sub_state == "ERROR") {
                                    item.sub_state_cn = "失败";
                                } else {
                                    item.sub_state_cn = item.sub_state;
                                }
                            }
                        });
                    });
                }
            },
            function err(rsp) {
                $scope.apiErr(rsp);
            }
        );
    };
    $scope.aaa = function () {
        console.log('aaa');

    }
    $scope.bbb = function () {
        console.log('bbb');

    }

    $scope.choose_open = function (item) {
        //   开启
        if (!item.choose) {
            var node = {
                name: item.key + "_" + item.name,
                properties: {
                    inputs: [],
                    output: true,
                    prototype: item.key + "." + item.name
                },
                version: $scope.full_version
            };
            $http.post("/proxy/config/node?r=1", node).then(
                function success(rsp) {
                    if (rsp.data.result) {
                        $scope.get_data();
                    }
                },
                function err(rsp) {}
            );
        }
        //   禁用
        if (item.choose) {
            $http
                .delete(
                    "/proxy/config/node/" + item.id + "?r=1&version=" + item.version
                )
                .then(
                    function success(rsp) {
                        if (rsp.data.result) {
                            $scope.get_data();
                        }
                    },
                    function err(rsp) {}
                );
        }
    };
    // 卡片详情
    $scope.detail = function (item) {
        if (!item.config.attributes.threat) {
            item.config.attributes.threat = 0
        }
        if (!item.config.attributes.confidence) {
            item.config.attributes.confidence = 0
        }
        var item_string = JSON.stringify(item);
        $scope.detail_info = JSON.parse(item_string);
        var W = 800;
        var H = 350;
        var box = null;
        box = zeroModal.show({
            title: "情报详情",
            content: detail_info,
            width: W + "px",
            height: H + "px",
            ok: false,
            cancel: false,
            okFn: function () {},
            onCleanup: function () {
                detail_hideenBox.appendChild(detail_info);
            }
        });
    };
    $scope.detail_cancel = function () {
        zeroModal.closeAll();
    }
    $("#inputFile_pkey").change(function () {
        var file = this.files[0];
        if (/.*\.key$/.test(file.name.toLowerCase())) {
            $scope.changeConfigData("pkey", file);
        } else {
            zeroModal.error("此文件无法导入！");
        }
    });
    // 替换证书
    $("#inputFile_cert").change(function () {
        var file = this.files[0];
        if (/.*\.crt$/.test(file.name.toLowerCase())) {
            $scope.changeConfigData("cert", file);
        } else {
            zeroModal.error("此文件无法导入！");
        }
    });
    $scope.changeConfigData = function (type, file) {
        var formData = new FormData();
        formData.append("file", file);
        var loading = zeroModal.loading(4);
        $http({
            method: "post",
            url: "/proxy/config/data/" + $scope.detail_info.key + "?t=" + type,
            data: formData,
            headers: {
                'Content-Type': undefined
            },
        }).then(
            function success(rsp) {
                zeroModal.close($scope.loading);
                if (rsp.data.result == "ok") {
                    zeroModal.success("替换成功！");
                }
            },
            function err(rsp) {
                zeroModal.close($scope.loading);
                zeroModal.error("此文件无法导入！");
            }
        );
    };
    // 保存
    $scope.detail_save = function () {
        var item = $scope.detail_info;
        var config_string = JSON.stringify(item.config);
        item.config = config_string
        var loading = zeroModal.loading(4);
        $http
            .post(
                "/proxy/prototype/" + item.key + "." + item.name + "?t=json",
                item
            )
            .then(
                function success(rsp) {
                    if (rsp.data.result == 'OK') {
                        $scope.get_prototype();
                        setTimeout(function () {
                            zeroModal.close($scope.loading);
                            zeroModal.closeAll();
                        }, 2000)
                    }
                    if (rsp.data.error) {
                        setTimeout(function () {
                            zeroModal.close($scope.loading);
                            zeroModal.closeAll();
                            zeroModal.error(rsp.data.error.message);
                        }, 2000)
                    }
                },
                function err(rsp) {
                    $scope.apiErr(rsp);
                }
            );
    };

    $scope.init();
});