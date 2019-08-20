<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>上汽集团威胁情报系统</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" media="screen" href="/css/common.css">
    <link rel="stylesheet" media="screen" href="/css/family/pingfang.css">
    <link rel="stylesheet" media="screen" href="/css/login.css">
    <script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="/plugins/angular/angular.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <div ng-app="myApp" ng-controller="loginCtrl" ng-cloak  >
        <div class="row login_box" id="login_enter" style="overflow:hidden;"  ng-click="click()">
            <div class="col-md-6" style="position: relative;height: 100%;">
                <div class="logo_box">
                    <img class="logo_img" src="/images/shangqi_logo1_sigin.png" alt="">
                </div>
                <div class="login_box_content">
                    <p class="login_box_content_tilte">威胁情报系统</p>
                    <div class="input_box">
                        <label for="username">用户名</label>
                        <img src="/images/login/user.png" class="user_img" alt="">
                        <input id="username" class="input_css" placeholder="请输入用户名" type="text"
                            ng-model="user.username" ng-focus="username_focus()">
                        <div class="error_msg">
                            <p class="errorMessage_color">
                                <span>{{errorMessage.username}}</span>
                            </p>
                        </div>
                    </div>
                    <div class="input_box">
                        <label for="pswd">密码</label>
                        <img src="/images/login/pswd.png" class="user_img" alt="">
                        <input id="pswd" class="input_css" ng-focus="password_focus()" placeholder="请输入密码" type="password" ng-model="user.password">
                        <div class="error_msg">
                            <p class="errorMessage_color">
                                <span>{{errorMessage.password}}</span>
                            </p>
                        </div>
                    </div>
                    <div class="input_box">
                        <label for="code">验证码</label>
                        <img src="/images/login/code.png" class="user_img" alt="">
                        <div>
                            <input id="code" class="input_css" ng-focus="code_focus()" style="width:210px;" placeholder="验证码" type="text"
                                ng-model="user.code">
                            <span style="float:right;">
                                <canvas width="134" height="42" id="verifyCanvas"></canvas>
                                <img id="code_img" />
                            </span>
                        </div>
                        <div class="error_msg">
                            <p class="errorMessage_color">
                                <span>{{errorMessage.code}}</span>
                            </p>
                        </div>
                    </div>
                    <div>
                        <img src="/images/login/select_o.png" class="img_rem" ng-click="select(1)" ng-if="select_if" alt="">
                        <img src="/images/login/select_i.png" class="img_rem"  ng-click="select(2)" ng-if="!select_if" alt="">
                        <span style="font-size: 14px;color: #666666;vertical-align: middle;">记住账号</span>
                    </div>
                    <button ng-click="login_in()" class="login_button">登录</button>
                </div>
            </div>
            <div class="col-md-6">
                <img class="bg_img" src="/images/login/login_bg.jpg" alt="">
            </div>
        </div>
    </div>
    <script src="/js/controllers/loginCtrl.js"></script>
</body>
