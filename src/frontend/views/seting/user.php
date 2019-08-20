<?php
/* @var $this yii\web\View */

$this->title = '账号管理';
?>
<link rel="stylesheet" href="/css/set/user.css">
<section class="user_container" ng-app="myApp" ng-controller="Set_userController" ng-cloak>
    <div class="user_box">
        <ul class="nav nav-tabs detail_bom_nav">
            <li role="presentation" class="active" ng-click="tab_click(1)">
                <a href="#user" data-toggle="tab">用户管理</a>
            </li>
            <li role="presentation"><a href="#role" data-toggle="tab" ng-click="tab_click(2)">角色管理</a></li>
            <li role="presentation"><a href="#policy" data-toggle="tab" ng-click="tab_click(3)">用户安全策略</a></li>
        </ul>
        <div class="tab-content tab_content">
            <!-- 用户管理 -->
            <div id="user" class="tab-pane active">
                <div class="user_top">
                    <div class="user_top_box">
                        <img src="/images/set/user_icon.png" class="img_icon" alt="">
                        <span class="top_title">用户</span>
                        <button class="user_btn" ng-click="add_user()">添加用户</button>
                    </div>
                </div>
                <div class="user_table">
                    <table class="table  domain_table ng-cloak">
                        <tr style="text-algin:center">
                            <th style="width:80px;">序号</th>
                            <th style="width:200px;">用户名</th>
                            <th style="width:200px;">角色</th>
                            <th style="width:200px;">管理IP</th>
                            <th style="width:200px;">创建人</th>
                            <th style="width:200px;">创建时间</th>
                            <th style="width:200px;">操作</th>
                        </tr>
                        <tr ng-repeat="item in user_pages.data">
                            <td ng-bind="$index + 1 + (user_pages.pageNow-1)*10">1</td>
                            <td ng-bind="item.username" title="{{item.username}}"></td>
                            <td ng-bind="item.role" title="{{item.role}}"></td>
                            <td ng-bind="item.allow" title="{{item.allow}}"> </td>
                            <td ng-bind="item.creatorname" title="{{item.creatorname}}"></td>
                            <td ng-bind="item.created_at*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                            <td class="cursor">
                                <img src="/images/set/edit_icon.png" class="img_icon" ng-click="edit_user(item)" alt="">
                                <img src="/images/set/cel_icon.png" class="img_icon" ng-click="cel_user(item)" alt="">
                            </td>
                        </tr>
                    </table>
                    <!-- angularjs分页 -->
                    <div style="padding: 25px;">
                        <span style="font-size: 14px;color: #BBBBBB;">共有
                            <span ng-bind="user_pages.count"></span>条</span>
                        <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.pageNow-1)"
                                    ng-if="user_pages.pageNow>1">上一页</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(1)" ng-if="user_pages.pageNow>1">1</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-if="user_pages.pageNow>4">...</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.pageNow-2)"
                                    ng-bind="user_pages.pageNow-2" ng-if="user_pages.pageNow>3"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.pageNow-1)"
                                    ng-bind="user_pages.pageNow-1" ng-if="user_pages.pageNow>2"></a>
                            </li>
                            <li class="active">
                                <a href="javascript:void(0);" ng-bind="user_pages.pageNow"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.pageNow+1)"
                                    ng-bind="user_pages.pageNow+1" ng-if="user_pages.pageNow<user_pages.maxPage-1"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.pageNow+2)"
                                    ng-bind="user_pages.pageNow+2" ng-if="user_pages.pageNow<user_pages.maxPage-2"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-if="user_pages.pageNow<user_pages.maxPage-3">...</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.maxPage)"
                                    ng-bind="user_pages.maxPage" ng-if="user_pages.pageNow<user_pages.maxPage"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" ng-click="getPage(user_pages.pageNow+1)"
                                    ng-if="user_pages.pageNow<user_pages.maxPage">下一页</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- 角色管理 -->
            <div id="role" class="tab-pane ">
                <div class="user_top">
                    <div class="user_top_box">
                        <img src="/images/set/role_icon.png" class="img_icon" alt="">
                        <span class="top_title">角色</span>
                        <button class="user_btn" ng-click="add_role()">添加角色</button>
                    </div>
                </div>
                <div class="user_table">
                    <table class="table  domain_table ng-cloak">
                        <tr style="text-algin:center">
                            <th>角色名称</th>
                            <th>角色描述</th>
                            <th>创建时间</th>
                            <th>创建人</th>
                            <th>操作</th>
                        </tr>
                        <tr ng-repeat="item in roleList">
                            <td ng-bind="item.name"></td>
                            <td ng-bind="item.description"></td>
                            <td ng-bind="item.created_at*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                            <td ng-bind="item.creatorname"></td>
                            <td class="cursor">
                                <div ng-if="item.name !='admin'&&item.name !='config'&&item.name !='audit'">
                                    <img src="/images/set/edit_icon.png" class="img_icon" ng-click="edit_role(item)"
                                        alt="">
                                    <img src="/images/set/cel_icon.png" class="img_icon" ng-click="delRole(item)"
                                        alt="">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div style="padding: 25px;">
                        <span style="font-size: 14px;color: #BBBBBB;">共有
                            <span ng-bind="roleList.length"></span>条</span>
                    </div>
                </div>
            </div>
            <!-- 用户安全策略 -->
            <div id="policy" class="tab-pane ">
                <div class="user_top">
                    <div class="user_top_box">
                        <img src="/images/set/policy_icon.png" class="img_icon" alt="">
                        <span class="top_title">角色安全策略</span>
                    </div>
                </div>
                <div class="policy_box">
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">启用：</span>
                        <span>
                            <span ng-click="type_choose('open')"class="choose_box">
                                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="type_true"
                                    alt="">
                                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!type_true"
                                    alt="">
                                <span>启用</span>
                            </span>
                            <span ng-click="type_choose('closed')"class="choose_box">
                                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!type_true"
                                    alt="">
                                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="type_true"
                                    alt="">
                                <span>停用</span>
                            </span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">密码长度最小值：</span>
                        <input class="policy_box_item_input" type="text"
                        ng-blur="input_blur('min_passwd_len')" ng-model="policy_list.min_passwd_len">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text">密码最小长度8-11之间包含大小写字母和数</span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">密码长度最大值：</span>
                        <input class="policy_box_item_input" type="text"
                        ng-blur="input_blur('max_passwd_len')"
                        ng-model="policy_list.max_passwd_len">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text">密码最大长度11-30之间包含大小写字母和数字</span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">密码定期修改时间：</span>
                        <input class="policy_box_item_input" type="text"
                         ng-blur="input_blur('passwd_regular_edit_time')"
                            ng-model="policy_list.passwd_regular_edit_time">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text">(1-90) 天</span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">最大登陆重试次数：</span>
                        <input class="policy_box_item_input" type="text"
                         ng-blur="input_blur('admin_faild_logon_time')"
                        ng-model="policy_list.admin_faild_logon_time">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text"> (1-5) 次</span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">登陆失败阻断时间：</span>
                        <input class="policy_box_item_input" type="text"
                         ng-blur="input_blur('admin_logon_delay_time')"
                        ng-model="policy_list.admin_logon_delay_time">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text"> (1-3600) 秒</span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">页面超时时间：</span>
                        <input class="policy_box_item_input" type="text"
                         ng-blur="input_blur('session_timeout')"
                         ng-model="policy_list.session_timeout">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text">(1-480) 分钟</span>
                        </span>
                    </div>
                    <div class="policy_box_item">
                        <span class="policy_box_item_name">同时在线数：</span>
                        <input class="policy_box_item_input" type="text"
                            ng-blur="input_blur('admin_online_count')"
                         ng-model="policy_list.admin_online_count">
                        <span class="placehode_box">
                            <span class="placehode_icon">* </span>
                            <span class="placehode_text"> (1-5) 个</span>
                        </span>
                    </div>
                    <div style="margin-top: 64px;">
                        <button class="save_btn" ng-click="save_policy()">保存</button>
                        <button class="cancel_btn" ng-click="cancel_policy()">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="/js/controllers/user.js"></script>
