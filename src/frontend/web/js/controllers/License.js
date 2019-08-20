var myApp = angular.module('myApp', []);
myApp.controller('LicenseCtrl', function($scope, $http,$filter) {
    $scope.License = {
        listt:{},
        sensorNowCount:0
    };

    $http.get('/license/get').then(function success(rsp){
        if(rsp.data.status == 'success')
        {
            $scope.License = rsp.data.license;
            $scope.key = rsp.data.key;
        }else{
            zeroModal.error('查询失败!');
        }
    },function err(rsp){
        eroModal.error('查询失败!');
    });

    $scope.getJSON = function(){
        return JSON.stringify($scope.License,null,2);
    }

    $scope.import = function(){
        $('#LicenseFile').click();
    }

    $scope.online = function(){
        var W = 480;
        var H = (W/2);
        zeroModal.show({
            title: '在线激活',
            content: inputSN,
            width: W+"px",
            height: H+"px",
            overlayClose: true,
            cancel: true,
            ok: true,
            okTitle:'激活',
            okFn: function(){
                var post_data = {
                    SN:$scope.SN,
                    key:$scope.key
                }
                $http.post('/license/online',post_data).then(function success(rsp){
                    if(rsp.data.status == 'success'){
                        importBin(rsp.data.bin);
                    }else if(rsp.data.errorMessage == 'License does not exist'){
                        zeroModal.error({
                            content:'验证失败！',
                            contentDetail:'序列号校验失败，请确认您输入的序列号！',
                        });
                    }else if(rsp.data.errorMessage == 'Key error'){
                        zeroModal.error({
                            content:'验证失败！',
                            contentDetail:'此序列号已被替他设备使用，请购买新的许可证！',
                        });
                    }
                },function err(rsp){
                    zeroModal.error({
                        content:'验证失败！',
                        contentDetail:'请检查网络，确保服务器可以流畅访问互联网！',
                    });
                });
            },
            onCleanup: function() {
                hide_box.appendChild(inputSN);
            }
        });
    }

    function importBin(bin){
        if(/^[0-1]*$/.test(bin)){
            $http.post('/license/import',{bin:bin}).then(function success(rsp){
                if(rsp.data.status == 'success')
                {
                    if($scope.License.list[rsp.data.SN]){
                        zeroModal.success('许可证已存在，无需重复导入!');
                    }else{
                        $scope.License = rsp.data.license;
                        zeroModal.success('许可证导入成功!');
                    }
                }else{
                    zeroModal.error('许可证无效!');
                }
            },function err(rsp){
                eroModal.error('许可证导入失败!');
            });
        }else{
            zeroModal.error('许可证无效!');
        }
    }


    $('#LicenseFile').change(function(){
        var file = this.files[0];
        if(file && file.size < 1024*1024){
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload=function(f){
                var bin=reader.result.trim();
                importBin(bin);
            }
        }else{
            zeroModal.error('许可证无效!');
        }
    });
});

