<?php
/* @var $this yii\web\View */

$this->title = '漏洞关联';
?>
<style>
    .tab-content {
        background: #fff;
    }

    .input_text {
        border: 1px solid rgb(216, 216, 216);
        outline: none;
        padding: 0;
        -webkit-appearance: none;
        border-radius: 3px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        height: 35px;
        /* width: 670px; */
        text-indent: 20px;
        font-size: 14px;
        color: #333;
        overflow: visible;
    }

    .button_search {
        cursor: pointer;
        border: 0;
        border-radius: 3px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-color: #30a9f2;
        /* width: 110px; */
        color: #fff;
        height: 40px;
        color: #fff;
        vertical-align: bottom;
    }

    hr {
        margin: 5px 0;
    }

    .item_info {
        height: 30px;
        line-height: 30px;
    }

    .margin0 {
        margin: 0;
    }

    .margintop {
        margin-top: 10px;
    }

    table {
        table-layout: fixed;
    }

    td,
    th {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: center;
    }
</style>
<link rel="stylesheet" href="/css/set/loophole_connect.css">
<section class="loophole_container" ng-app="myApp" ng-controller="LoopConnectCtrl" ng-cloak>
    <div class="loop_connect_box">
        <div class="loop_connect_box_top">
            <div class="position_box">
                <input type="text" placeholder="请输入漏洞日志名称" class="input_box" ng-model="search_data.risk_name">
                <input type="text" placeholder="请输入漏洞情报名称" class="input_box" ng-model="search_data.title">
                <button class="search_btn" ng-click="get_loop_list()">搜索规则</button>
                <button class="add_btn" ng-click="add_loop()">新建规则</button>
            </div>
        </div>
        <div class="loop_connect_box_table">
            <table class="table  domain_table ng-cloak">
                <tr style="text-algin:center">
                    <th style="width:200px;">时间</th>
                    <th>漏洞日志名称</th>
                    <th>漏洞情报名称</th>
                    <th style="width:200px;">操作</th>
                </tr>
                <tr ng-repeat="item in loop_list.data">
                    <td>{{item.created_at*1000 | date : 'yyyy-MM-dd HH:mm'}}</td>
                    <td>{{item.risk_name}}</td>
                    <td>{{item.title}}</td>
                    <td>
                        <img src="/images/set/edit_icon.png" class="img_icon" ng-click="edit_loop(item)" alt="">
                        <img src="/images/set/look_icon.png" class="img_icon" ng-click="look_loop(item)" alt="">
                        <img src="/images/set/cel_icon.png" class="img_icon" ng-click="cel_loop(item)" alt="">
                    </td>
                </tr>
            </table>
            <div style="padding: 25px;">
                <span style="font-size: 14px;color: #BBBBBB;">共有
                    <span ng-bind="loop_list.count"></span>条</span>
                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.pageNow-1)"
                            ng-if="loop_list.pageNow>1">上一页</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(1)" ng-if="loop_list.pageNow>1">1</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="loop_list.pageNow>4">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.pageNow-2)"
                            ng-bind="loop_list.pageNow-2" ng-if="loop_list.pageNow>3"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.pageNow-1)"
                            ng-bind="loop_list.pageNow-1" ng-if="loop_list.pageNow>2"></a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" ng-bind="loop_list.pageNow"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.pageNow+1)"
                            ng-bind="loop_list.pageNow+1" ng-if="loop_list.pageNow<loop_list.maxPage-1"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.pageNow+2)"
                            ng-bind="loop_list.pageNow+2" ng-if="loop_list.pageNow<loop_list.maxPage-2"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="loop_list.pageNow<loop_list.maxPage-3">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.maxPage)"
                            ng-bind="loop_list.maxPage" ng-if="loop_list.pageNow<loop_list.maxPage"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_loop_list(loop_list.pageNow+1)"
                            ng-if="loop_list.pageNow<loop_list.maxPage">下一页</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 添加自定义规则 -->
    <div style="display: none;" id="hideenBox_custom_add">
        <div id="custom_add">
            <p class="edit_title">漏洞日志名称</p>
            <div>
                <input class="input_box_bottom" placeholder="请输入漏洞日志名称" ng-model="add_lopp.risk_name" type="text">
            </div>
            <p class="edit_title">漏洞情报名称</p>
            <div>
                <input class="input_box_bottom" placeholder="请输入漏洞情报名称" ng-model="add_lopp.title" type="text">
            </div>
            <div style="text-align: center;">
                <button class="save_btn" ng-click="add_save()">保存</button>
                <button class="cancel_btn" ng-click="add_cancel()">取消</button>
            </div>
        </div>
    </div>
    <!-- 编辑自定义规则 -->
    <div style="display: none;" id="hideenBox_custom_edit">
        <div id="custom_edit">
            <p class="edit_title">漏洞日志名称</p>
            <div>
                <input class="input_box_bottom" placeholder="请输入漏洞日志名称" ng-model="edit_lopp.risk_name" type="text">
            </div>
            <p class="edit_title">漏洞情报名称</p>
            <div>
                <input class="input_box_bottom" placeholder="请输入漏洞情报名称" ng-model="edit_lopp.title" type="text">
            </div>
            <div style="text-align: center;">
                <button class="save_btn" ng-click="edit_loop_save()">保存</button>
                <button class="cancel_btn" ng-click="edit_loop_cancel()">取消</button>
            </div>
        </div>
    </div>
    <!-- 关联漏洞预警列表-->
    <div style="display: none;" id="hideenBox_relation_alert">
        <div id="custom_relation_alert">
            <table class="table domain_table ng-cloak">
                <tr>
                    <th style="width:150px;">预警时间</th>
                    <th>资产</th>
                    <th>所属分组</th>
                    <th>漏洞</th>
                    <th>漏洞等级</th>
                    <th>POC</th>
                    <th>状态</th>
                </tr>
                <tr style="cursor: pointer;" ng-repeat="item in get_relation_data.data">
                    <td title="{{item.time*1000|date:'yyyy-MM-dd HH:mm'}}">
                        {{item.time*1000 | date:'yyyy-MM-dd HH:mm'}}</td>
                    <td title="{{item.device_ip}}">{{item.device_ip}}</td>
                    <td title="{{item.company}}">{{item.company}}</td>
                    <td title="{{item.loophole_name}}">{{item.loophole_name}}</td>
                    <td title="{{item.level}}">{{item.level}}</td>
                    <td title="{{item.poc}}">{{item.poc}}</td>
                    <td class="overflow_alarm width_100">
                        <div class="btn-group {{(ariaID == item.id)?'open':''}}">
                            <button type="button" class="btn btn-{{loop_status_str[item.risk_process].css}} btn-xs ">
                                <span>
                                    <span ng-bind="loop_status_str[item.risk_process].label"></span>
                                </span>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
            <div style="border-top: 1px solid #f4f4f4;padding: 10px;">
                <em>共有<span ng-bind="get_relation_data.count"></span>条漏洞</em>
                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.pageNow-1)"
                            ng-if="get_relation_data.pageNow>1">上一页</a></li>
                    <li><a href="javascript:void(0);" ng-click="get_relation(1)"
                            ng-if="get_relation_data.pageNow>1">1</a></li>
                    <li><a href="javascript:void(0);" ng-if="get_relation_data.pageNow>4">...</a></li>

                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.pageNow-2)"
                            ng-bind="get_relation_data.pageNow-2" ng-if="get_relation_data.pageNow>3"></a></li>
                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.pageNow-1)"
                            ng-bind="get_relation_data.pageNow-1" ng-if="get_relation_data.pageNow>2"></a></li>

                    <li class="active"><a href="javascript:void(0);" ng-bind="get_relation_data.pageNow"></a>
                    </li>

                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.pageNow+1)"
                            ng-bind="get_relation_data.pageNow+1"
                            ng-if="get_relation_data.pageNow<get_relation_data.maxPage-1"></a></li>
                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.pageNow+2)"
                            ng-bind="get_relation_data.pageNow+2"
                            ng-if="get_relation_data.pageNow<get_relation_data.maxPage-2"></a></li>
                    <li><a href="javascript:void(0);"
                            ng-if="get_relation_data.pageNow<get_relation_data.maxPage-3">...</a></li>

                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.maxPage)"
                            ng-bind="get_relation_data.maxPage"
                            ng-if="get_relation_data.pageNow<get_relation_data.maxPage"></a>
                    </li>
                    <li><a href="javascript:void(0);" ng-click="get_relation(get_relation_data.pageNow+1)"
                            ng-if="get_relation_data.pageNow<get_relation_data.maxPage">下一页</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="/js/controllers/loop_connect.js"></script>
