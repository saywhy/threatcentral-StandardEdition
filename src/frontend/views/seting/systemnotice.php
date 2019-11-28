<?php
/* @var $this yii\web\View */
$this->title = '威胁通知';
?>
<style type="text/css">
    .email_item {
        padding: 10px 30px;
        font-size: 16px;
    }

    .email_item_header {
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }

    .item_form {
        margin-top: 10px;
        font-size: 14px;
    }

    .item_form label {
        height: 34px;
        line-height: 34px;
    }

    .item_input {
        border-radius: 4px;
        max-width: 200px;
    }

    .role_textarea {
        border: 1px solid #999;
        width: 300px;
        height: 125px;
        position: absolute;
        left: 108px;
        top: 3px;
        resize: none;
        border-radius: 3px;
    }

    .info_input {
        width: 120px;
        border-radius: 3px;
        border: 1px solid #999;
        height: 30px;
    }

    .strategy_item {
        height: 35px;
    }

    .strategy_label {
        display: inline-block;
        width: 90px;
        margin-right: 10px;
    }
</style>
<link rel="stylesheet" href="/css/notice/notice.css">
<section class="notice_content" ng-app="myApp" ng-cloak ng-controller="systemnoticeCtrl">
    <div>
        <ul class="nav nav-tabs detail_bom_nav">
            <li role="presentation" class="active" ng-click="tab_click(1)">
                <a href="#mail" data-toggle="tab">邮件通知</a>
            </li>
            <li role="presentation" style="display:none"><a href="#message" data-toggle="tab" ng-click="tab_click(2)">短信通知</a></li>
        </ul>
        <div class="tab-content tab_content">
            <div id="mail" class="tab-pane active">
                <p class="content_title">邮件服务器设置</p>
                <p class="content_item">
                    <span class="content_item_name">SMTP服务器：</span>
                    <input type="text" placeholder="smtp.163.com" ng-model="email.host" class="content_input">
                </p>
                <p class="content_item">
                    <span class="content_item_name">SMTP端口：</span>
                    <input type="text" class="content_input" placeholder="25" ng-model="email.port">
                </p>
                <p class="content_item">
                    <span class="content_item_name_ssl">SMTP启用安全连接SSL启用：</span>
                    <span ng-click="set_choose('ssl','open')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="email_true.ssl" alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!email_true.ssl"
                            alt="">
                        <span>启用</span>
                    </span>
                    <span ng-click="set_choose('ssl','closed')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!email_true.ssl"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="email_true.ssl"
                            alt="">
                        <span>停用</span>
                    </span>
                </p>
                <p class="content_item">
                    <span class="content_item_name">邮箱地址：</span>
                    <input type="text" class="content_input" placeholder="smtp.163.com" ng-model="email.username">
                </p>
                <p class="content_item">
                    <span class="content_item_name">邮箱密码：</span>
                    <input type="password" class="content_input" ng-model="email.password">
                </p>
                <p class="content_title">收件邮箱账号</p>
                <div class="content_item_addr">
                    <span class="content_item_name addr_name">邮箱地址：</span>
                    <div class="box_item_addr">
                        <div ng-repeat="item in input_list" class="input_list_box">
                            <input class="input_bom_box" type="text" ng-model="item.name">
                            <img src="/images/report/del.png" class="addr_icon" ng-click="del_input($index)"
                                ng-if="!item.icon" alt="">
                            <img src="/images/report/add.png" class="addr_icon" ng-click="add_input($index)"
                                ng-if="item.icon" alt="">
                        </div>
                    </div>
                </div>
                <p class="content_item">
                    <span class="content_item_name_ssl">发生告警时给此邮件发送通知邮件：</span>
                    <span ng-click="set_choose('send','open')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="email_true.send"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!email_true.send"
                            alt="">
                        <span>启用</span>
                    </span>
                    <span ng-click="set_choose('send','closed')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!email_true.send"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="email_true.send"
                            alt="">
                        <span>停用</span>
                    </span>
                </p>
                <p class="content_item">
                    <span class="content_item_name_ssl">暗网信息：</span>
                    <span ng-click="set_choose('dark_net','open')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="email_true.dark_net"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!email_true.dark_net"
                            alt="">
                        <span>启用</span>
                    </span>
                    <span ng-click="set_choose('dark_net','closed')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!email_true.dark_net"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="email_true.dark_net"
                            alt="">
                        <span>停用</span>
                    </span>
                </p>
                <p class="content_item">
                    <span class="content_item_name_ssl">系统提醒：</span>
                    <span ng-click="set_choose('system','open')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="email_true.system"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!email_true.system"
                            alt="">
                        <span>启用</span>
                    </span>
                    <span ng-click="set_choose('system','closed')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!email_true.system"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="email_true.system"
                            alt="">
                        <span>停用</span>
                    </span>
                </p>
                <p class="content_item">
                    <span class="content_item_name_ssl">漏洞情报更新邮件提醒：</span>
                    <span ng-click="set_choose('intelligence','open')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="email_true.intelligence"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!email_true.intelligence"
                            alt="">
                        <span>启用</span>
                    </span>
                    <span ng-click="set_choose('intelligence','closed')" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!email_true.intelligence"
                            alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="email_true.intelligence"
                            alt="">
                        <span>停用</span>
                    </span>
                </p>
                <div class="btn_box">
                    <button class="btn_save" ng-click="send_email('save')">保存</button>
                    <button class="btn_test" ng-click="send_email('test')">发送测试邮件</button>
                </div>
            </div>
            <div id="message" class="tab-pane ">
                <p class="content_title">短信通知设置</p>
                <p class="content_item">
                    <span class="content_item_name">短信网关地址：</span>
                    <input type="text" placeholder="短信网关地址" class="content_input">
                </p>
                <p class="content_item">
                    <span class="content_item_name">用户名：</span>
                    <input type="text" placeholder="用户名" class="content_input">
                </p>
                <p class="content_item">
                    <span class="content_item_name">密码：</span>
                    <input type="text" placeholder="密码" class="content_input">
                </p>
                <p class="content_item">
                    <span class="content_item_name">通知内容：</span>
                    <textarea class="box_textarea" autoHeight="true" rows="5" cols="20" placeholder="通知内容"></textarea>
                </p>
                <div class="btn_box">
                    <button class="btn_save" ng-click="send_message('save')">保存</button>
                    <button class="btn_test" ng-click="send_message('test')">发送测试短信</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="/js/controllers/systemnotice.js"></script>
