var myApp = angular.module("myApp", []);
myApp.controller("SyslogCtrl", [
    "$scope",
    "$http",
    "$rootScope",
    function ($scope, $http, $rootScope) {
        console.log("SyslogCtrl");
        $scope.init = function () {
            $scope.zeroModal_type = true;
            $scope.getPage();
        }
        // 获取syslog 列表
        $scope.getPage = function (pageNow) {
            var loading = zeroModal.loading(4);
            pageNow = pageNow ? pageNow : 1;
            $scope.params_data = {
                page: pageNow,
                rows: 10
            };
            $http({
                method: 'get',
                url: '/syslog/list',
                params: $scope.params_data,
            }).then(
                function success(rsp) {
                    console.log(rsp);
                    zeroModal.close(loading);
                    $scope.pages = rsp.data
                },
                function err(rsp) {
                    zeroModal.close(loading);
                }
                // if (data.status == 0) {
                //     $scope.pages = data.data;
                // } else if (data.status == 600) {
                //     console.log(data.msg);
                //     zeroModal.error(data.msg);
                // } else {
                //     zeroModal.error(data.msg);
                // }
                // zeroModal.close(loading);
            )
        };
        //   打开生成syslog弹窗
        $scope.syslog_add = function () {
            $scope.zeroModal_type = true;
            $scope.syslog = {
                trans: 3,
                port: 514,
                ip: '',
                ONBOOT: '0'
            }
            var W = 552;
            var H = 320;
            zeroModal.show({
                title: "添加SYSLOG配置",
                content: syslog,
                width: W + "px",
                height: H + "px",
                ok: false,
                cancel: false,
                okFn: function () {},
                onCleanup: function () {
                    syslog_box.appendChild(syslog);
                }
            });
        };
        // 确认添加
        $scope.confirm_syslog = function (type) {
            if ($scope.syslog.trans == 3) {
                $scope.syslog.protocol = 'udp';
            } else {
                $scope.syslog.protocol = 'tcp';
            };
            var rqs_data = {
                server_ip: $scope.syslog.ip,
                server_port: $scope.syslog.port,
                protocol: $scope.syslog.protocol,
                status: $scope.syslog.ONBOOT,
            };
            var loading = zeroModal.loading(4);
            if (type == 'add') {
                $http.post("/syslog/add-conf", rqs_data).then(function success(rsp) {
                    zeroModal.close(loading);
                    console.log(rsp);
                    if (rsp.data.status == 'fail') {
                        zeroModal.error(rsp.data.errorMessage);
                    }
                    if (rsp.data.status == 'success') {
                        zeroModal.closeAll();
                        $scope.getPage();
                        zeroModal.success('添加成功');
                    }
                }, function err(rsp) {
                    zeroModal.close(loading);
                });
            }
            if (type == 'edit') {
                rqs_data.id = $scope.edit_data.id,
                    $http({
                        method: 'PUT',
                        url: '/syslog/edit-conf',
                        data: rqs_data,
                    }).then(function (rsp) {
                        console.log(rsp);
                        zeroModal.close(loading);
                        if (rsp.data.status == 'fail') {
                            zeroModal.error(rsp.data.errorMessage);
                        }
                        if (rsp.data.status == 'success') {
                            zeroModal.closeAll();
                            zeroModal.success('修改成功');
                            $scope.getPage();
                        }
                    }, function (rsp) {
                        zeroModal.close(loading);
                    })
            }
            console.log($scope.syslog);
        }
        // 取消
        $scope.cancel_syslog = function () {
            zeroModal.closeAll();
        }
        //   打开编辑syslog弹窗
        $scope.modify = function (item) {
            $scope.zeroModal_type = false;
            console.log(item);
            $scope.edit_data = item;
            $scope.syslog = {
                trans: 3,
                port: item.server_port,
                ip: item.server_ip,
                ONBOOT: item.status
            }
            if (item.protocol == 'udp') {
                $scope.syslog.trans = 3;
            } else {
                $scope.syslog.trans = 4;
            }
            var W = 552;
            var H = 320;
            zeroModal.show({
                title: "编辑SYSLOG配置",
                content: syslog,
                width: W + "px",
                height: H + "px",
                ok: false,
                cancel: false,
                okFn: function () {},
                onCleanup: function () {
                    syslog_box.appendChild(syslog);
                }
            });

        }
        // 删除SYSLOG配置
        $scope.del = function (item) {
            zeroModal.confirm({
                content: '确定删除 "' + item.server_ip + '" 吗？',
                okFn: function () {
                    rqs_data = {
                        id: item.id
                    };
                    var loading = zeroModal.loading(4);
                    $http({
                        method: 'delete',
                        url: '/syslog/del-conf',
                        data: rqs_data,
                    }).then(function (req) {
                        console.log(req);
                        zeroModal.close(loading);
                        if (req.data.status == 'success') {
                            zeroModal.success('删除成功');
                            $scope.getPage();
                        }
                    }, function (req) {
                        zeroModal.close(loading);

                    })
                },
                cancelFn: function () {}
            });
        };
        $scope.init();
    }
]);