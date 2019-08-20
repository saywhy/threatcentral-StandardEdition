<li class="dropdown" id="selfApp" ng-controller="selfCtrl">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-user"></i>
        <span><?=Yii::$app->user->identity->username?></span>
    </a>
    <ul class="dropdown-menu" style="min-width: 100%;">
        <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" ng-click="resetPassword();">
                <i class="fa fa-edit"></i>
                <span>修改密码</span>
            </a>
            <div id="resetSelfPasswordBox" style="display: none;">
                <div id="resetSelfPassword">
                    <form>
                        <div class="box-body">
                            <div class="form-group {{resetUser_old_passworderror ? 'has-error':''}}">
                                <label for="InputVersion">旧的密码：</label>
                                <input type="password" class="form-control" ng-model="resetUser.old_password">
                                <p class="help-block" ng-bind="resetUser_old_passworderror ? '请输入旧的密码' : '　'"></p>
                            </div>

                            <div class="form-group {{resetUser_passworderror ? 'has-error':''}}">
                                <label for="InputVersion">设置密码：</label>
                                <input type="password" class="form-control" ng-model="resetUser.password">
                                <p class="help-block">请填写8-30位密码,包含大写字母、小写字母、数字、特称字符</p>
                            </div>

                            <div class="form-group {{resetUser_repassworderror ? 'has-error':''}}">
                                <label for="InputVersion">确认密码：</label>
                                <input type="password" class="form-control" ng-model="resetUser.repassword">
                                <p class="help-block" ng-bind="resetUser_repassworderror ? '密码不一致' : '　'"></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</li>