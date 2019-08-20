<?php
/* @var $this yii\web\View */

$this->title = '修改用户';
?>
<link rel="stylesheet" href="/css/set/edituser.css">
<section class="edituser_content" ng-app="myApp" ng-controller="EditUserController" ng-cloak>
    <div class="user_box">
        <div class="user_box_item">
            <span class="user_box_item_title">用户名</span>
            <input type="text" class="user_box_item_input" disabled="disabled" ng-model="edit_user.username">
        </div>
        <div class="user_box_item">
            <span class="user_box_item_title">设置密码</span>
            <input type="password" class="user_box_item_input"
           placeholder="{{password_placeholder}}"  ng-model="edit_paswd.new_password">
        </div>
        <div class="user_box_item">
            <span class="user_box_item_title">确认密码</span>
            <input type="password" class="user_box_item_input"
             placeholder="{{password_placeholder}}"  ng-model="edit_paswd.repeat_password">
        </div>
        <div class="user_box_role">
            <span class="user_box_role_title">角色</span>
            <div class="choose_box">
                <span ng-click="set_choose(item.name,item.choose)" class="choose_item_box"
                    ng-repeat="item in choose_list">
                    <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="item.choose" alt="">
                    <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!item.choose" alt="">
                    <span style="font-size: 16px;color:#333">{{item.name}}</span>
                </span>
            </div>

        </div>
        <div class="user_box_item">
            <span class="user_box_item_title">管理员IP</span>
            <input type="text" class="user_box_item_input" placeholder="允许最多指定5个IP地址,每个地址用‘;’隔开" ng-model="edit_user.allow_ip" >
        </div>
        <div>
              <button class="save_btn" ng-click="save()">保存</button>
            <button class="cancel_btn" ng-click="cancel()">取消</button>
        </div>

    </div>
</section>
<script type="text/javascript" src="/js/controllers/set/user/edituser.js"></script>
