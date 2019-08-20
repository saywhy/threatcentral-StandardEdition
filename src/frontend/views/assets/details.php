<?php
/* @var $this yii\web\View */
$this->title = '资产信息';
?>
<link rel="stylesheet" href="/css/common.css">
<style>
    .details_content {
        padding: 36px 48px;
    }

    .details_content_box {
        background: #FFFFFF;
        border-radius: 6px;
        min-height: 200px;
    }

    .detail_bom_nav {
        height: 56px;
        background: #EEF6FF;
        border: none;
        border-radius: 6px 6px 0 0;
    }

    .detail_bom_nav li {
        height: 56px;
    }

    .detail_bom_nav>li>a {
        height: 56px;
        line-height: 56px !important;
        font-size: 16px !important;
        color: #333333;
        padding: 0 30px !important;
        border: 0;
    }

    .detail_bom_nav a:hover {
        color: #409eff !important;
    }

    .detail_bom_nav>li>a:hover {
        border: none;
        background: transparent;
    }

    .detail_bom_nav>li.active>a {
        color: #409eff !important;
        border: none !important;
    }

    .detail_bom_nav>li.active {
        border-top: 3px solid #0070FF;
        border-radius: 4px;
    }

    .domain_table tr:nth-child(odd) {
        background: #fff;
    }

    .domain_table tr:nth-child(even) {
        background: #eef6ff;

    }

    .domain_table {
        width: 100%;
        table-layout: fixed;
        font-size: 14px;
        color: #333333;
    }

    .domain_table tr {
        height: 48px;
        line-height: 48px;
        padding-left: 26px;
    }

    .domain_table td,
    .domain_table th {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: center;
    }
</style>
<section class="details_content" ng-app="myApp" ng-cloak ng-controller="RiskyDetails">
    <div class="details_content_box">
        <ul class="nav nav-tabs detail_bom_nav">
            <li role="presentation" class="active" ng-click="tab_active(1)">
                <a href="#info" data-toggle="tab">威胁预警</a>
            </li>
            <li role="presentation" ng-click="tab_active(2)">
                <a href="#loophole" data-toggle="tab">漏洞预警</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="info" class="tab-pane active">
                <table class="table  domain_table ng-cloak">
                    <tr>
                        <th>受影响资产</th>
                        <th>所属分组</th>
                        <th>预警类型</th>
                        <th>威胁指标</th>
                        <th>预警时间</th>
                        <th>处理状态</th>
                    </tr>
                    <tr ng-repeat="item in alerts_list_data.data" style="cursor:pointer;" ng-click="alert_detail(item)">
                        <td title="{{item.client_ip}}">
                            <img src="/images/alert/h.png" ng-if="item.degree == '高'" alt="">
                            <img src="/images/alert/m.png" ng-if="item.degree == '中'" alt="">
                            <img src="/images/alert/l.png" ng-if="item.degree == '低'" alt="">
                            <span ng-bind="item.client_ip"> </span>
                        </td>
                        <td title="{{item.company}}" ng-bind="item.company"></td>
                        <td title="{{item.category}}" ng-bind="item.category"></td>
                        <td title="{{item.indicator}}" ng-bind="item.indicator"></td>
                        <td ng-bind="item.time*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                        <td>
                            <span ng-bind="status_str[item.status].label"></span>
                        </td>
                    </tr>
                </table>
                <!-- angularjs分页 -->
                <div style="padding: 20px;min-height: 20px;">
                    <em style="font-size: 14px;color: #BBBBBB;">共有
                        <span>{{alerts_list_data.count}}</span>条</em>
                    <ul class="pagination pagination-sm no-margin pull-right ng-cloak">

                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.pageNow-1)"
                                ng-if="alerts_list_data.pageNow>1">上一页</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(1)"
                                ng-if="alerts_list_data.pageNow>1">1</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-if="alerts_list_data.pageNow>4">...</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.pageNow-2)"
                                ng-bind="alerts_list_data.pageNow-2" ng-if="alerts_list_data.pageNow>3"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.pageNow-1)"
                                ng-bind="alerts_list_data.pageNow-1" ng-if="alerts_list_data.pageNow>2"></a>
                        </li>
                        <li class="active">
                            <a href="javascript:void(0);" ng-bind="alerts_list_data.pageNow"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.pageNow+1)"
                                ng-bind="alerts_list_data.pageNow+1"
                                ng-if="alerts_list_data.pageNow<alerts_list_data.maxPage-1"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.pageNow+2)"
                                ng-bind="alerts_list_data.pageNow+2"
                                ng-if="alerts_list_data.pageNow<alerts_list_data.maxPage-2"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"
                                ng-if="alerts_list_data.pageNow<alerts_list_data.maxPage-3">...</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.maxPage)"
                                ng-bind="alerts_list_data.maxPage"
                                ng-if="alerts_list_data.pageNow<alerts_list_data.maxPage"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="alerts_list(alerts_list_data.pageNow+1)"
                                ng-if="alerts_list_data.pageNow<alerts_list_data.maxPage">下一页</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="loophole" class="tab-pane ">
                <table class="table domain_table  ng-cloak">
                    <tr>
                        <th>漏洞名称</th>
                        <th>受影响资产</th>
                        <th>所属分组</th>
                        <th>POC</th>
                        <th>预警时间</th>
                        <th>处理状态</th>
                    </tr>
                    <tr ng-repeat="item in loopholes_list_data.data" style="cursor:pointer;" ng-click="loop_detail(item)">
                        <td title="{{item.loophole_name}}">
                            <img src="/images/alert/h.png" ng-if="item.level == '高'" alt="">
                            <img src="/images/alert/m.png" ng-if="item.level == '中'" alt="">
                            <img src="/images/alert/l.png" ng-if="item.level == '低'" alt="">
                            <span ng-bind="item.loophole_name"> </span>
                        </td>
                        <td title="{{item.device_ip}}">{{item.device_ip}}</td>
                        <td title="{{item.company}}">{{item.company}}</td>
                        <td>{{item.poc}}</td>
                        <td ng-bind="item.time*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                        <td>
                          <span>{{item.risk_status_cn}}</span>
                        </td>
                    </tr>
                </table>
                <!-- angularjs分页 -->
                <div style="padding: 20px;min-height: 20px;">
                    <em style="font-size: 14px;color: #BBBBBB;">共有
                        <span>{{loopholes_list_data.count}}</span>条</em>
                    <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.pageNow-1)"
                                ng-if="loopholes_list_data.pageNow>1">上一页</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(1)"
                                ng-if="loopholes_list_data.pageNow>1">1</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-if="loopholes_list_data.pageNow>4">...</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.pageNow-2)"
                                ng-bind="loopholes_list_data.pageNow-2" ng-if="loopholes_list_data.pageNow>3"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.pageNow-1)"
                                ng-bind="loopholes_list_data.pageNow-1" ng-if="loopholes_list_data.pageNow>2"></a>
                        </li>
                        <li class="active">
                            <a href="javascript:void(0);" ng-bind="loopholes_list_data.pageNow"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.pageNow+1)"
                                ng-bind="loopholes_list_data.pageNow+1"
                                ng-if="loopholes_list_data.pageNow<loopholes_list_data.maxPage-1"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.pageNow+2)"
                                ng-bind="loopholes_list_data.pageNow+2"
                                ng-if="loopholes_list_data.pageNow<loopholes_list_data.maxPage-2"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"
                                ng-if="loopholes_list_data.pageNow<loopholes_list_data.maxPage-3">...</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.maxPage)"
                                ng-bind="loopholes_list_data.maxPage"
                                ng-if="loopholes_list_data.pageNow<loopholes_list_data.maxPage"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" ng-click="loopholes_list_data(loopholes_list_data.pageNow+1)"
                                ng-if="loopholes_list_data.pageNow<loopholes_list_data.maxPage">下一页</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="/js/controllers/assets_details.js"></script>
