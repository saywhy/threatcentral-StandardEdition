var app = angular.module("myApp", []);
var rootScope;
app.controller("shareCommentCtrl", function($scope, $http, $filter) {
  rootScope = $scope;
  $scope.init = function() {
    $scope.share_comment = JSON.parse(sessionStorage.getItem("share_comment"));
    console.log($scope.share_comment);
    $scope.share_comment.textarea_info = "";
    $scope.commentCount = 0;
    $scope.share_comment.pushComment = [];
    $scope.add_more_show = true;
    $scope.getComment();
  };
  //   获取评论列表
  $scope.getComment = function() {
    $scope.postData_offset = $scope.share_comment.pushComment.length;
    var postData = {
      sid: $scope.share_comment.id,
      offset: $scope.postData_offset
    };
    console.log($scope.commentCount);
    console.log(postData.offset);
    if ($scope.commentCount != 0 && $scope.commentCount <= postData.offset) {
      $scope.add_more_show = false;
      return false;
    }
    $http.post("/share/get-comment", postData).then(
      function success(rsp) {
        if (rsp.data.status == "success") {
          $scope.share_comment.comment = rsp.data;
          $scope.share_comment.comment.data = rsp.data.data;
          $scope.commentCount = rsp.data.count;
          if ($scope.commentCount == 0) {
            $scope.add_more_show = false;
          }
          angular.forEach($scope.share_comment.comment.data, function(item) {
            $scope.share_comment.pushComment.push(item);
          });
        }
      },
      function err(rsp) {}
    );
  };
  //   发送评论
  $scope.addComment = function() {
    $scope.share_comment.textarea_info = $scope.share_comment.textarea_info.trim();
    if ($scope.share_comment.textarea_info == "") {
      zeroModal.error("评论内容不能为空");
      return false;
    }
    var loading = zeroModal.loading(4);
    $http({
      method: "post",
      url: "/share/add-comment",
      data: {
        sid: $scope.share_comment.id,
        content: $scope.share_comment.textarea_info
      }
    }).then(
      function successCallback(data) {
        console.log(data);
        zeroModal.close(loading);
        if (data.data.status == "success") {
          zeroModal.success("评论成功");
          $scope.share_comment.pushComment = [];
          $scope.getComment();
        } else {
          zeroModal.error("评论成功");
        }
      },
      function errorCallback(data) {
        zeroModal.close(loading);
        zeroModal.error("评论成功");
      }
    );
  };
  // 加载更多
  $scope.add_more = function() {
    $scope.getComment();
  };
  $scope.init();
});
app.filter("reverse", function() {
  return function(items) {
    return items.slice().reverse();
  };
});
