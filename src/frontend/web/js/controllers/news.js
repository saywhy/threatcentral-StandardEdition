var newsApp = angular.module('newsApp', []);
newsApp.controller('newsCtrl', function($scope, $http,$filter,$sce) {
    $scope.newsList = [];
    $http.post('/news/list',{status:1}).then(function success(rsp){
        if (rsp.data.status == 'success') {
            $scope.newsList = rsp.data.data;
        }
    },function err(rsp){
    });
    $scope.countNews = function(){
        var count = 0;
        for (var i = $scope.newsList.length - 1; i >= 0; i--) {
            if($scope.newsList[i].status == 1){
                count++;
            }
        }
        return count;
    }
    $scope.remove = function(item){
        for (var i = $scope.newsList.length - 1; i >= 0; i--) {
            if($scope.newsList[i].id == item.id){
                $scope.newsList.splice(i,1);
                return;
            }
        }
    }
    $scope.showNews = function(item){
        $scope.nowNews = item;
        $scope.contentHtml = $sce.trustAsHtml($scope.nowNews.content)
        item.status = 2;
        $http.post('/news/update',item).then(function success(rsp){
        },function err(rsp){
        });
        var W = 480;
        var H = 360;
        zeroModal.show({
            title: item.title,
            content: newsDetail,
            width: W+"px",
            height: H+"px",
            onCleanup: function() {
                news_hide_box.appendChild(newsDetail);
            },
            buttons:[
                {
                    className:'zeromodal-btn zeromodal-btn-default',
                    name: '关闭',
                    fn:function(){}
                },
                {
                    className:'zeromodal-btn zeromodal-btn-danger',
                    name: '删除',
                    fn:function(){
                        item.status = 0;
                        $http.post('/news/update',item).then(function success(rsp){
                            $scope.remove(item)
                        },function err(rsp){
                        });
                        return true;
                    }
                }
            ]
                
        });
    }
    $scope.getMoment = function(item){
        return moment(item.created_at, 'X').fromNow();
    }
});

angular.element(document).ready(
    function (){
        angular.bootstrap(document.getElementById("newsApp"), ['newsApp']);
    }
);

