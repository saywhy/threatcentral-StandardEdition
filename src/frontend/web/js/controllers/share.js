var myApp = angular.module("myApp", []);
var rootScope;
myApp.controller("shareCtrl", function ($scope, $http, $filter) {
    $scope.init = function () {
        $scope.listObj = {};
        $scope.list = [];
        $scope.listCount = 0;
        $scope.btn_show = true;
        $scope.btn_text = "加载更多";
        $scope.wds = [];
        $scope.searchWd = "";
        $scope.get_list();
        $scope.enter();
    };

    $scope.enter = function () {
        document.onkeydown = function (e) {
            // 兼容FF和IE和Opera
            var theEvent = e || window.event;
            var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
            if (code == 13) {
                $scope.search();
            }
        };
    };
    $scope.push2list = function (item) {
        if (!$scope.listObj[item.id]) {
            item.timeString = moment(item.created_at, "X").fromNow();
            $scope.list.push(item);
            $scope.listObj[item.id] = item;
        }
    };
    $scope.search = function () {
        $scope.wds = $scope.searchWd.split(/\s+/);
        $scope.listObj = {};
        $scope.list = [];
        $scope.listCount = 0;
        $scope.get_list();
    };
    $scope.get_list = function () {
        var postData = {
            wds: $scope.wds,
            offSet: Object.keys($scope.listObj).length
        };
        if ($scope.listCount != 0 && $scope.listCount <= postData.offSet) {
            $scope.btn_show = false;
            $scope.btn_text = "加载完成";
            return;
        }
        $http.post("/share/list", postData).then(
            function success(rsp) {
                console.log(rsp);
                if (rsp.data.status == "success") {
                    $scope.listCount = rsp.data.count;
                    if ($scope.listCount == 0) {
                        $scope.btn_show = false;
                    }
                    angular.forEach(rsp.data.data, function (item) {
                        $scope.push2list(item);
                    });
                }
            },
            function err(rsp) {}
        );
    };

    $scope.add_more = function () {
        $scope.get_list();
    };
    //   共享情报提交
    $scope.add = function (item) {
        window.location.href = "/share/add";
    };
    $scope.detail = function (item) {
        window.location.href = "/share/detail?id=" + item.id;
    };
    // 跳转评论详情
    $scope.goto_comment = function (item) {
        sessionStorage.setItem("share_comment", JSON.stringify(item));
        window.location.href = "/share/comment-detail";
    };

    $scope.del = function (item, index) {
        zeroModal.confirm({
            content: "确定删除这个分享吗？",
            okFn: function () {
                var postData = {
                    id: item.id,
                    wds: $scope.wds
                };
                var loading = zeroModal.loading(4);
                $http.post("/share/del", postData).then(
                    function success(rsp) {
                        console.log(rsp);
                        if (rsp.data.status == "success") {
                            $scope.list.splice(index, 1);
                            delete $scope.listObj["" + item.id];
                        }
                        zeroModal.close(loading);
                    },
                    function err(rsp) {
                        zeroModal.close(loading);
                    }
                );
            },
            cancelFn: function () {}
        });
    };
    $scope.init();
});