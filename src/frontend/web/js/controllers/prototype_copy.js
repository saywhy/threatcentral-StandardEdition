var myApp = angular.module("myApp", []);
myApp.controller("PrototypeCtrl", function ($scope, $http, $filter) {
    var loading = null;
    $scope.init = function () {
        $scope.bgList = [
            "bg-primary",
            "bg-aqua",
            "bg-green",
            "bg-yellow",
            "bg-red",
            "bg-teal",
            "bg-purple",
        ];
        if (loading == null) {
            //   loading = zeroModal.loading(4);
        }
        $scope.nodeList = null;
        $scope.prototypeData = null;
        $scope.info = null;
        $scope.getPrototypes();
        $scope.getNodes();
        setInterval(function () {
            $scope.getNodes();
        }, 60000);
    };

    $scope.getPrototypes = function () {
        $http.get("/proxy/prototype?f=local").then(
            function success(rsp) {
                if (rsp.data.result) {
                    $scope.prototypeData = rsp.data.result;
                    console.log($scope.prototypeData);

                    $scope.setData();
                } else {
                    $scope.apiErr(rsp);
                }
            },
            function err(rsp) {
                $scope.apiErr(rsp);
            }
        );
    };
    console.log(new Date());
    $scope.getNodes = function () {
        console.log(new Date());
        $http.get("/proxy/config/full").then(
            function success(rsp) {
                if (rsp.data.result) {
                    console.log(new Date());
                    console.log(rsp.data.result);

                    $scope.info = rsp.data.result;
                    $scope.nodeList = $scope.info.nodes;
                    $scope.setData();
                } else {
                    $scope.apiErr(rsp);
                }
            },
            function err(rsp) {
                $scope.apiErr(rsp);
            }
        );
    };

    $scope.setData = function () {
        console.log(new Date());
        if (
            $scope.nodeList == null ||
            $scope.prototypeData == null ||
            !$scope.info == null
        ) {
            return;
        }
        $scope.prototypes = {};
        $scope.prototypeList = {};
        console.log(new Date());
        console.log($scope.prototypeData);

        for (var orgName in $scope.prototypeData) {
            var prototypes = $scope.prototypeData[orgName].prototypes;
            for (var name in prototypes) {
                var item = prototypes[name];
                $scope.prototypeList[orgName + "." + name] = item;
                if (item.config.attributes && item.node_type == "miner") {
                    if (!$scope.prototypes[item.config.attributes.share_level]) {
                        $scope.prototypes[item.config.attributes.share_level] = {};
                    }
                    item.orgName = orgName;
                    item.name = name;
                    item.node = null;
                    $scope.prototypes[item.config.attributes.share_level][name] = item;

                    if (
                        $scope.nowPrototype &&
                        $scope.nowPrototype.orgName == item.orgName &&
                        $scope.nowPrototype.name == item.name
                    ) {
                        $scope.nowPrototype =
                            $scope.prototypes[item.config.attributes.share_level][name];
                        $scope.copyNowPrototype();
                    }
                }
            }
        }
        console.log($scope.nodeList);

        for (var id = $scope.nodeList.length - 1; id >= 0; id--) {
            var node = $scope.nodeList[id];
            if (!node) {
                continue;
            }
            node.id = id;
            if (
                node.properties.output == true &&
                node.properties.inputs.length == 0
            ) {
                $scope.prototypeList[node.properties.prototype].node = node;
            }
        }
        var rootUrl = location.protocol + "//" + location.host;
        var NodeList = [];
        var inputNodeList = [];

        function setNodeList(nodes) {
            NodeList = nodes;
            inputNodeList = [];
            for (var i = NodeList.length - 1; i >= 0; i--) {
                var node = NodeList[i];
                if (node.output == true && node.inputs.length == 0) {
                    inputNodeList.push(node);
                }
            }
            console.log(inputNodeList);
            //   updateTable(inputNodeList, "inputNodeTable");

            for (k in $scope.prototypes.red) {
                $scope.prototypes.red[k].relname =
                    $scope.prototypes.red[k].orgName +
                    "_" +
                    $scope.prototypes.red[k].name;
            }
            for (k in $scope.prototypes.green) {
                $scope.prototypes.green[k].relname =
                    $scope.prototypes.green[k].orgName +
                    "_" +
                    $scope.prototypes.green[k].name;
            }
            angular.forEach(inputNodeList, function (item) {
                var State_str = [
                    "READY",
                    "CONNECTED",
                    "REBUILDING",
                    "RESET",
                    "INIT",
                    "STARTED",
                    "CHECKPOINT",
                    "IDLE",
                    "STOPPED",
                ];
                item.state = State_str[item.state];
                for (k in $scope.prototypes.red) {
                    if ($scope.prototypes.red[k].relname == item.name) {
                        $scope.prototypes.red[k].sub_state = item.sub_state;
                        $scope.prototypes.red[k].last_run = item.last_run;
                        $scope.prototypes.red[k].state = item.state;
                    }
                }
                for (k in $scope.prototypes.green) {
                    if ($scope.prototypes.green[k].relname == item.name) {
                        $scope.prototypes.green[k].sub_state = item.sub_state;
                        $scope.prototypes.green[k].last_run = item.last_run;
                        $scope.prototypes.green[k].state = item.state;
                    }
                }
            });
            $scope.$apply();
        }
        var Tables = {};
        console.log(new Date());
        $.ajax({
            dataType: "json",
            url: "/proxy/status/cyberhunt",
            success: function (rsp) {
                setNodeList(rsp.result);
                $scope.$apply();
            },
            complete: function (rsp) {},
        });
        console.log($scope.prototypes);
        loading = null;

    };

    $scope.changed = false;
    $scope.$watch(
        "nowPrototype",
        function (newValue, oldValue, scope) {
            if ($scope.nowPrototype_old) {
                var config_old = $scope.nowPrototype_old.config;
                var config = newValue.config;
                $scope.changed = !(
                    config_old.interval == config.interval &&
                    config_old.attributes.confidence == config.attributes.confidence &&
                    config_old.attributes.threat == config.attributes.threat
                );
            }
        },
        true
    );
    $scope.changeConfigData = function (type, file) {
        var formData = new FormData();
        formData.append("file", file);
        $http({
            method: "POST",
            url: "/proxy/config/data/" + $scope.nowPrototype.orgName + "?t=" + type,
            data: formData,
            headers: {
                "Content-Type": undefined
            },
        }).then(
            function success(rsp) {
                if (rsp.data.result == "ok") {
                    zeroModal.success("私钥导入成功！");
                } else if (rsp.data.result.issuer) {
                    var begin = moment(rsp.data.result.begin).format("YYYY-MM-DD");
                    var end = moment(rsp.data.result.end).format("YYYY-MM-DD");
                    zeroModal.success({
                        content: "证书导入成功！",
                        contentDetail: rsp.data.result.subject +
                            "<br>" +
                            '<div style="margin: 5px 0 0 50px;">' +
                            '<span class="pull-left">有效时间：' +
                            begin +
                            "到" +
                            end +
                            "</span><br>" +
                            '<span class="pull-left">发行机构：' +
                            rsp.data.result.issuer +
                            "</span>" +
                            "</div>",
                    });
                }
            },
            function err(rsp) {
                zeroModal.error("此文件无法导入！");
            }
        );
    };
    $("#inputFile_cert").change(function () {
        var file = this.files[0];
        if (/.*\.crt$/.test(file.name.toLowerCase())) {
            $scope.changeConfigData("cert", file);
        } else {
            zeroModal.error("此文件无法导入！");
        }
    });
    $("#inputFile_pkey").change(function () {
        var file = this.files[0];
        if (/.*\.key$/.test(file.name.toLowerCase())) {
            $scope.changeConfigData("pkey", file);
        } else {
            zeroModal.error("此文件无法导入！");
        }
    });
    $scope.detail = function (item) {
        oldTop = nowTop;
        $scope.nowPrototype = item;
        // console.log( $scope.nowPrototype);

        if (typeof $scope.nowPrototype.config.interval == "undefined") {
            $scope.nowPrototype.config.interval = null;
        }
        if (typeof $scope.nowPrototype.config.attributes.threat == "undefined") {
            $scope.nowPrototype.config.attributes.threat = null;
        }
        $scope.copyNowPrototype();
    };
    $scope.copyNowPrototype = function () {
        $scope.changed = false;
        $scope.nowPrototype_old = angular.copy($scope.nowPrototype);
    };
    $scope.backList = function (item) {
        $scope.nowPrototype = null;
        $scope.nowPrototype_old = null;
        setTimeout(function () {
            $(".content-wrapper").scrollTop(oldTop);
        }, 5);
    };
    $scope.save = function () {
        var item = $scope.nowPrototype;
        console.log(item);

        if (loading == null) {
            //   loading = zeroModal.loading(4);
        }
        $http
            .post(
                "/proxy/prototype/" + item.orgName + "." + item.name + "?t=json",
                item
            )
            .then(
                function success(rsp) {
                    if (rsp.data.result) {
                        $scope.init();
                    } else {
                        $scope.apiErr(rsp);
                    }
                },
                function err(rsp) {
                    $scope.apiErr(rsp);
                }
            );
    };
    $scope.delNode = function (item) {
        var nodeNames = [];
        console.log($scope.nodeList);

        for (var i = $scope.nodeList.length - 1; i >= 0; i--) {
            var node = $scope.nodeList[i];
            var index = node.properties.inputs.indexOf(item.node.name);
            if (index > -1) {
                nodeNames.push(node.name);
            }
        }
        $http
            .delete(
                "/proxy/config/node/" +
                item.node.id +
                "?r=1&version=" +
                item.node.version
            )
            .then(
                function success(rsp) {
                    if (rsp.data.result) {
                        $scope.info = rsp.data.result;
                        $scope.nodeList = $scope.info.nodes;
                        $scope.setData();
                    } else {
                        $scope.apiErr(rsp);
                    }
                },
                function err(rsp) {
                    $scope.apiErr(rsp);
                }
            );
    };
    $scope.addNode = function (item) {
        var node = {
            name: item.orgName + "_" + item.name,
            properties: {
                inputs: [],
                output: true,
                prototype: item.orgName + "." + item.name,
            },
            version: $scope.info.version,
        };
        $http.post("/proxy/config/node?r=1", node).then(
            function success(rsp) {
                if (rsp.data.result) {
                    $scope.info = rsp.data.result;
                    $scope.nodeList = $scope.info.nodes;
                    $scope.setData();
                } else {
                    $scope.apiErr(rsp);
                }
            },
            function err(rsp) {
                $scope.apiErr(rsp);
            }
        );
    };
    $scope.changeStatus = function (item) {
        if (loading == null) {
            //   loading = zeroModal.loading(4);
        }
        if (item.node) {
            $scope.delNode(item);
        } else {
            $scope.addNode(item);
        }
    };
    $scope.init();

    var nowTop = 0;
    var oldTop = 0;
    $(".content-wrapper").scroll(function () {
        nowTop = $(this).scrollTop();
    });

    $scope.apiErr = function (rsp) {
        console.log(rsp);
    };
});