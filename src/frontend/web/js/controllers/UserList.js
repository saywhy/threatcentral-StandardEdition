var Groups = {
    '*':{
        text:'所有',
        type:'*'
    }
};
var myApp = angular.module('myApp', []);
myApp.controller('UserCtrl', function($scope, $http,$filter,$httpParamSerializerJQLike) {
    $scope.UserIDList = [];
    $scope.userList = {};

    $scope.pages = {
        data : [],
        count : 0,
        maxPage : "...",
        pageNow : 1,
    };
    $scope.userRole = {
        'admin':'管理员',
        'user':'普通用户',
        'share':'共享用户'
    }
    $scope.getPage = function(pageNow)
    {
        pageNow = pageNow ? pageNow : 1;
        $http.post('/user/page',{page:pageNow}).then(function success(rsp){
            if(rsp.data.status == 'success')
            {
                $scope.pages = rsp.data;
            }
        },function err(rsp){
        });
    }
    $scope.getPage();

    $scope.sendUser = function(id,$event){
        rqs_data = {
            username : $scope.newUser.username,
            password : $scope.newUser.password,
            role : $scope.newUser.role,
            page:$scope.pages.pageNow
        };
        var loading = zeroModal.loading(4);
        $http.post("/user/add",rqs_data).then(function success(rsp){
            zeroModal.close(loading);
            if(rsp.data.status == 'success')
            {
                $scope.pages = rsp.data;
            }else if(rsp.data.errorCode == 1){
                zeroModal.error({
                    content: '用户添加失败',
                    contentDetail: '此用户名已经存在！'
                });
            }
        },function err(rsp){
            zeroModal.close(loading);
        });
    }

    $scope.del = function(item){
        zeroModal.confirm({
            content: '确定删除'+ $scope.userRole[item.role] +'"'+ item.username +'"吗？',
            okFn: function() {
                rqs_data = {
                    id : item.id,
                    page:$scope.pages.pageNow
                };
                var loading = zeroModal.loading(4);
                $http.post("/user/del",rqs_data).then(function success(rsp){
                    zeroModal.close(loading);
                    if(rsp.data.status == 'success')
                    {
                        $scope.pages = rsp.data;
                    }
                },function err(rsp){
                    zeroModal.close(loading);
                });
            },
            cancelFn: function() {
            }
        });
    }

    $scope.selectAll = function(){
        if($scope.UserIDList.length == $scope.pages.data.length){
            $scope.UserIDList = [];
        }else{
            $scope.UserIDList = [];
            for (var i in $scope.pages.data) {
                var item = $scope.pages.data[i];
                $scope.UserIDList.push(item.id);
            }
        }
    }
    $scope.selectOne = function(id,$event){
        $event.stopPropagation();
        var index = $scope.UserIDList.indexOf(id);
        if(index == -1){
            $scope.UserIDList.push(id);
        }else{
            $scope.UserIDList.splice(index,1);
        }
    }

    $scope.resetPassword = function(user){
        var loading = zeroModal.loading(4);
        $http.get("/user/get-password-reset-token?id="+user.id).then(function success(rsp){
            zeroModal.close(loading);
            if(rsp.data.status == 'success')
            {
                var W = 540;
                var H = W*3/4;
                zeroModal.show({
                    title: '重置['+user.username+']的密码',
                    content: resetPassword,
                    width: W+"px",
                    height: H+"px",
                    ok: true,
                    cancel: true,
                    okFn: function() {
                        var flag = true;
                        var password = $scope.resetUser.password;
                        var pattern = /(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z0-9]).{8,30}/;
                        if(!pattern.test(password))
                        {
                            flag = false;
                            $scope.resetUser_passworderror = true;
                        }else{
                            $scope.resetUser_passworderror = false;
                        }
                        if(password != $scope.resetUser.repassword)
                        {
                            flag = false;
                            $scope.resetUser_repassworderror = true;
                        }else{
                            $scope.resetUser_repassworderror = false;
                        }
                        $scope.$apply();
                        if(!flag)
                        {
                            return false;
                        }
                        var post_data = {
                            'ResetPasswordForm':{
                                'password':password
                            }
                        };
                        var formData = {
                            method: 'POST',
                            url: '/user/reset-password?token='+rsp.data.token,post_data,
                            data:post_data
                        }
                        loading = zeroModal.loading(4);
                        $http({
                            method : formData.method,
                            url :formData.url,
                            data : $httpParamSerializerJQLike(formData.data),
                            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                        }).then(function success(rsp){
                            if(rsp.data.status == 'success'){
                                zeroModal.success('密码重置成功！');
                            }else{
                                zeroModal.error('密码重置失败！');
                            }
                            zeroModal.close(loading);
                        },function err(rsp){
                            zeroModal.close(loading);
                        });
                    },
                    onCleanup: function() {
                        hideenBox.appendChild(resetPassword);
                    }
                });
            }
        },function err(rsp){
            zeroModal.close(loading);
        });
    }

    $scope.add = function(){
        var W = 540;
        var H = 480;
        var box = null;
        $scope.newUser={
            username:'',
            password:'',
            role:'share'
        }


        box = zeroModal.show({
            title: '添加用户',
            content: newUser,
            width: W+"px",
            height: H+"px",
            ok: true,
            cancel: true,
            okFn: function() {
                var username = $scope.newUser.username;
                var flag = true;

                if(username == null || username.length==0 ||!/^[a-z0-9_-]{2,16}$/.test(username))
                {
                    flag = false;
                    $scope.nameerror = true;
                }else{
                    $scope.nameerror = false;
                }
                var password = $scope.newUser.password;
                var pattern = /(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z0-9]).{8,30}/;
                if(!pattern.test(password))
                {
                    flag = false;
                    $scope.passworderror = true;
                }else{
                    $scope.passworderror = false;
                }
                if(password != $scope.newUser.repassword)
                {
                    flag = false;
                    $scope.repassworderror = true;
                }else{
                    $scope.repassworderror = false;
                }

                $scope.$apply();
                if(!flag)
                {
                    return false;
                }
                $scope.sendUser();
            },
            onCleanup: function() {
                hideenBox.appendChild(newUser);
            }
        });
    }

    $scope.addGroups = function(){
        var W = 480;
        var H = 360;
        zeroModal.show({
            title: '请选择用户分组！',
            content: groupTree,
            width: W+"px",
            height: H+"px",
            ok: true,
            cancel: true,
            okFn: function() {
                if(!nowGroup){
                    zeroModal.error('请选择用户分组！');
                    return false;
                }
                if(nowGroup.type == 1){
                    zeroModal.error('不能加入自动分组！');
                    return false;
                }
                var uidList = [];
                for (var i = $scope.UserIDList.length - 1; i >= 0; i--) {
                    var uid = $scope.UserIDList[i];
                    uidList.push(uid);
                }
                rqs_data = {
                    gid:nowGroup.id,
                    uidList:uidList
                };
                var loading = zeroModal.loading(4);
                $http.post("addgroups",rqs_data).then(function success(rsp){
                    if(rsp.data.status == 'success'){
                        zeroModal.success({
                            content:'完成',
                            contentDetail:'成功加入'+rsp.data.success+'个用户！<br/>'+rsp.data.fail+'个用户已经在此分组中。',
                        });
                    }else{
                        zeroModal.error('加入用户分组失败！');
                    }
                    zeroModal.close(loading);
                },function err(rsp){
                    zeroModal.error('加入用户分组失败！');
                    zeroModal.close(loading);
                });
            },
            onCleanup: function() {
                hide_box.appendChild(groupTree);
            }
        });
    }
    var GroupTree = [];


    for (var i = 0; i < GroupList.length; i++) {
      var group = GroupList[i];
      Groups[group.id] = group;
      group.type = ''+group.type;
      if(group.pid == 0)
      {
        GroupTree.push(group);
      }else{
        if(!Groups[group.pid].nodes){
          Groups[group.pid].nodes = [];
        }
        Groups[group.pid].nodes.push(group);
      }
    }
    var treeDom;

    function updateTree(){
      treeDom = $('#groupTree').treeview({
        color: "#428bca",
        data: GroupTree,
        onNodeSelected: function(event, node) {
          nowGroup = node;
        },
        onNodeUnselected: function (event, node) {
          nowGroup = null;
        }
      });
    }
    updateTree();
});




