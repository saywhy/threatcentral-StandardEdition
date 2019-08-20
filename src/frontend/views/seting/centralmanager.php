<?php
/* @var $this yii\web\View */

$this->title = '集中管理';
?>
<link rel="stylesheet" href="/css/set/centralmanager.css">
<section class="central_container" ng-app="myApp" ng-controller="centralmanagerCtrl" ng-cloak>
    <div class="central_box">
        <div class="central_box_top">
            <div class="central_box_top_role">
                <span class="central_box_top_name">设备角色</span>
                <span ng-click="role_choose('master')" class="choose_box">
                    <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="set_true" alt="">
                    <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!set_true" alt="">
                    <span>管理中心</span>
                </span>
                <span ng-click="role_choose('branch')" class="choose_box">
                    <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!set_true" alt="">
                    <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="set_true" alt="">
                    <span>分支机构</span>
                </span>
            </div>
            <div class="central_box_top_company" ng-if="!set_true">
                <span class="central_box_top_name">公司名称</span>
                <input type="text" class="input_box" ng-model="branch_data.company_name">
                <span class="central_box_top_name" style="margin-left:64px;">管理中心IP</span>
                <input type="text" class="input_box" ng-model="branch_data.device_ip">
            </div>
            <div class="btn_box" ng-if="!set_true">
                <button class="btn_ok" ng-click="change_role()">确认</button>
                <button class="btn_cancel" ng-click="reset()">重置</button>
            </div>
        </div>
        <div class="central_box_table" ng-if="set_true">
            <table class="table  domain_table ng-cloak">
                <tr style="text-algin:center">
                    <th>设备IP</th>
                    <th>分支机构名称</th>
                    <th>设备角色</th>
                    <th>链接状态</th>
                    <th style="width:320px;">管理操作</th>
                </tr>
                <tr ng-repeat="item in centralmanage_list.data">
                    <td>{{item.device_ip}}</td>
                    <td>{{item.company_name}}</td>
                    <td>{{item.role_type}}</td>
                    <td>{{item.connection_status_cn}}</td>
                    <td>
                        <button class="btn_list"
                        ng-if="item.connection_status =='unexamine'" ng-click="centralmanage_examine(item)">审核</button>
                        <button class="btn_list"
                         ng-if="item.connection_status =='break'"ng-click="centralmanage_connect(item)">连接</button>
                        <button class="btn_list"
                         ng-if="item.connection_status =='connect'"ng-click="centralmanage_break(item)">断开</button>
                        <button class="btn_list" ng-click="del_branch(item)">删除</button>
                        <button class="btn_list" ng-click="alert_box(item)">告警</button>
                    </td>
                </tr>
            </table>
            <div style="padding: 25px;">
                <span style="font-size: 14px;color: #BBBBBB;">共有
                    <span ng-bind="centralmanage_list.count"></span>条</span>
                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.pageNow-1)"
                            ng-if="centralmanage_list.pageNow>1">上一页</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(1)" ng-if="centralmanage_list.pageNow>1">1</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="centralmanage_list.pageNow>4">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.pageNow-2)"
                            ng-bind="centralmanage_list.pageNow-2" ng-if="centralmanage_list.pageNow>3"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.pageNow-1)"
                            ng-bind="centralmanage_list.pageNow-1" ng-if="centralmanage_list.pageNow>2"></a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" ng-bind="centralmanage_list.pageNow"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.pageNow+1)"
                            ng-bind="centralmanage_list.pageNow+1" ng-if="centralmanage_list.pageNow<centralmanage_list.maxPage-1"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.pageNow+2)"
                            ng-bind="centralmanage_list.pageNow+2" ng-if="centralmanage_list.pageNow<centralmanage_list.maxPage-2"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="centralmanage_list.pageNow<centralmanage_list.maxPage-3">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.maxPage)"
                            ng-bind="centralmanage_list.maxPage" ng-if="centralmanage_list.pageNow<centralmanage_list.maxPage"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_centralmanage_list(centralmanage_list.pageNow+1)"
                            ng-if="centralmanage_list.pageNow<centralmanage_list.maxPage">下一页</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 弹窗 -->
    <div style="display: none;" id="alert_time_box">
        <div id="alert_time">
            <div class="token_mid">
                <div class="time_start_box">
                    <p class="token_name">告警开始时间</p>
                    <img src="/images/report/time.png" class="time_icon" alt="">
                    <input type="text" placeholder="开始时间" id="start_time_picker" readonly
                        class="token_top_input token_top_input_time_left">
                </div>
                <div class="time_end_box">
                    <p class="token_name">告警结束时间</p>
                    <img src="/images/report/time.png" class="time_icon" alt="">
                    <input type="text" placeholder="结束时间" id="end_time_picker" readonly
                        class="token_top_input token_top_input_time_right">
                </div>
            </div>
            <div class="token_btn_box">
                <button class="token_btn_ok" ng-click="download_alert()">保存</button>
                <button class="token_btn_cancel" ng-click="alert_time_cancel()">取消</button>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="/js/controllers/centralmanager.js"></script>
