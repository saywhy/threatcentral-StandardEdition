<?php
/* @var $this yii\web\View */

$this->title = '添加角色';
?>
<link rel="stylesheet" href="/css/set/addrole.css">
<section class="addrole_content" ng-app="myApp" ng-controller="AddRoleController" ng-cloak>
    <div class="role_box">
        <div class="role_box_item">
            <span class="role_box_item_title">角色名称</span>
            <input type="text" class="role_box_item_input" placeholder="请输入角色名称" ng-model="addRole.name">
        </div>
        <div class="role_box_item">
            <span class="role_box_item_title">角色描述</span>
            <textarea class="box_textarea" ng-model="addRole.description" autoHeight="true" rows="5" cols="20"
                placeholder="请输入角色描述信息"></textarea>
        </div>
        <div class="role_box_item_tree">
            <span class="role_box_item_title">角色权限</span>
            <div class="tree_box">
                <div id="tree" class="ztree"></div>
            </div>
        </div>
        <div>
            <button class="save_btn" ng-click="save()">保存</button>
            <button class="cancel_btn" ng-click="cancel()">取消</button>
        </div>
    </div>
</section>
<script type="text/javascript" src="/js/controllers/set/user/addrole.js"></script>
