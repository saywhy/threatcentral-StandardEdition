<?php
/* @var $this yii\web\View */

$this->title = '受影响资产';
?>
<link rel="stylesheet" href="/css/common.css">
<style>
    .table tr td .progress {
        margin-top: 0;
    }
    .high_line {
        float: left;
        width: 33.3%;
        background-color:#FF5F5C;
        height: 100%;
        border-radius: 2px;
    }

    .high_num {
        width: 33.3%;
        float: left;
        color: #FF5F5C;
        display: inline-block;
        text-align: center;
    }
    .mid_num {
        width: 33.3%;
        float: left;
        color: #FEAA00;
        display: inline-block;
        text-align: center;

    }
       .mid_line {
        float: left;
        width: 33.3%;
        background-color: #FEAA00;
        height: 100%;
        border-radius: 2px;
    }
    .low_num {
        width: 33.3%;
        float: left;
        color: #7ACE4C;
        display: inline-block;
        text-align: center;
    }
    .low_line {
        float: left;
        width: 33.3%;
        background-color:#7ACE4C;
        height: 100%;
        border-radius: 2px;
    }

    .risky_box {
        background-color: #fff;
        padding: 10px;
        border-radius: 5px;
    }

    /* 受影响资产 */
    .myAsset_content {
        padding: 36px 48px;
    }

    .myAsset_box {
        background: #FFFFFF;
        border-radius: 6px;
        min-height: 200px;
    }

    .myAsset_box_top {
        height: 124px;
        padding: 0 36px;
        position: relative;
    }

    .myAsset_box_top_box {
        height: 42px;
        position: absolute;
        top: 50%;
        left: 36px;
        transform: translateY(-50%);
    }

    .input_box {
        border: 1px solid #ECECEC;
        border-radius: 4px;
        width: 210px;
        height: 42px;
        margin-right: 16px;
        padding-left: 30px;
        padding-right: 5px;
        font-size: 14px;
        color: #333;
    }

    .input_item {
        float: left;
        margin-right: 20px;
        position: relative;
    }

    .search_icon {
        position: absolute;
        left: 10px;
        transform: translateY(-50%);
        top: 50%;
    }

    .input_item_btn {
        background: #0070FF;
        font-size: 16px;
        border-radius: 4px;
        width: 124px;
        height: 42px;
        color: #fff;
    }

    .container_ul {
        width: 210px;
        height: 100px;
        overflow-y: auto;
        position: absolute;
        top: 43px;
        border: 1px solid #999;
        z-index: 999;
        background: #fff;
        padding: 5px;
        border-radius: 3px;
    }

    .li_hover {
        padding: 3px;
        border-radius: 3px;
    }

    .li_hover:hover {
        cursor: pointer;
        background-color: #0070FF;
        color: #fff;
        border-radius: 3px;
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

    .table tr td .progress {
        margin-top: 0;
        height: 12px;
        background-color: transparent;
    }

    .td_box {
        position: relative;
            padding-top: 18px !important;
    }


    .velnerabilites {
        float: left;
         padding: 0 10px;
        height: 12px;
        width: 100%;
        position: absolute;
        top: 7px;
        left: 0;
    }
    .input_demo{
  border: 1px solid #ECECEC;
        border-radius: 4px;
        width: 210px;
        min-height: 42px;
        margin-right: 16px;
        padding-left: 30px;
        padding-right: 5px;
        font-size: 14px;
        color: #333;
       overflow: hidden;
        white-space:normal;
        word-wrap:break-word;
        word-break:break-all;
    }
    .cursor{
        cursor:pointer;
    }
</style>
<section class="myAsset_content" ng-app="myApp" ng-cloak ng-controller="myAssetsRisky">
    <div class="myAsset_box">
        <div class="myAsset_box_top">
            <div class="myAsset_box_top_box">
                <div class="input_item">
                    <input type="text" class="input_box" placeholder="输入资产名称" ng-model="search.asset_ip"
                        ng-focus="get_assets_name_focus()" ng-blur="get_domain_name_blur()"
                        ng-keyup="myKeyup_assets_name(search.asset_ip)">
                    <ul class="container_ul" ng-show="assets_name_list_if">
                        <li ng-repeat="item in assets_name" class="li_hover"
                            ng-click="assets_name_list_click(item.asset_ip)">
                            {{item.asset_ip}}
                        </li>
                    </ul>
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                </div>
                <div class="input_item">
                    <input type="text" class="input_box" placeholder="输入资产分组名称" ng-model="search.company"
                        ng-focus="get_company_list_focus()" ng-blur="get_domain_name_blur()"
                        ng-keyup="myKeyup_company_list(search.company)">
                    <ul class="container_ul" ng-show="company_list_if">
                        <li ng-repeat="item in company_list" class="li_hover"
                            ng-click="company_list_click(item.company)">
                            {{item.company}}
                        </li>
                    </ul>
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                </div>
                <div class="input_item">
                    <button class="input_item_btn" ng-click="risk()">搜索</button>
                </div>

            </div>
        </div>
        <div class="myAsset_box_bom" ng-click="if_false()">
            <table class="table domain_table ng-cloak">
                <tr>
                    <th>受影响资产</th>
                    <th>资产分组</th>
                    <th style="cursor: pointer;" ng-click="sort('count')">预警总数</th>
                    <th>威胁预警</th>
                    <th>漏洞预警</th>
                </tr>
                <tr ng-repeat="item in risk_data.data" >
                    <td>
                        <span class="cursor" ng-click="list_detail(item)">
                        {{item.asset_ip}}
                        </span>
                    </td>
                    <td>
                            <span class="cursor" ng-click="list_detail(item)">
                        {{item.company}}
                        </span>
                    </td>
                    <td>
                               <span class="cursor" ng-click="list_detail(item)">
                        {{item.total_count}}
                        </span>
                    <td class="td_box" ng-click="list_detail(item)">
                        <!-- <p style="padding:0;margin:0;"> -->
                        <span class="high_num">{{item.high_alert_count}}</span>
                        <span class="mid_num">{{item.medium_alert_count}}</span>
                        <span class="low_num">{{item.low_alert_count}}</span>
                        <!-- </p> -->
                        <div class="velnerabilites">
                            <span class="high_line"></span>
                            <span class="mid_line"></span>
                            <span class="low_line"></span>
                        </div>
                    </td>
                    <td class="td_box" ng-click="list_detail(item)">
                        <p style="padding:0;margin:0;">
                            <span class="high_num">{{item.high_loophole_count}}</span>
                            <span class="mid_num">{{item.medium_loophole_count}}</span>
                            <span class="low_num">{{item.low_loophole_count}}</span>
                        </p>
                        <div class="velnerabilites">
                            <span class="high_line"></span>
                            <span class="mid_line"></span>
                            <span class="low_line"></span>
                        </div>
                    </td>
                </tr>
            </table>
            <!-- angularjs分页 -->
            <div style="padding: 20px;min-height: 20px;">
                <em style="font-size: 14px;color: #BBBBBB;">共有
                    <span>{{risk_data.count}}</span>条</em>
                <!-- <span ng-bind="pages.count">3</span>条</em> -->
                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">

                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.pageNow-1)"
                            ng-if="risk_data.pageNow>1">上一页</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(1)" ng-if="risk_data.pageNow>1">1</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="risk_data.pageNow>4">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.pageNow-2)" ng-bind="risk_data.pageNow-2"
                            ng-if="risk_data.pageNow>3"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.pageNow-1)" ng-bind="risk_data.pageNow-1"
                            ng-if="risk_data.pageNow>2"></a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" ng-bind="risk_data.pageNow"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.pageNow+1)" ng-bind="risk_data.pageNow+1"
                            ng-if="risk_data.pageNow<risk_data.maxPage-1"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.pageNow+2)" ng-bind="risk_data.pageNow+2"
                            ng-if="risk_data.pageNow<risk_data.maxPage-2"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="risk_data.pageNow<risk_data.maxPage-3">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.maxPage)" ng-bind="risk_data.maxPage"
                            ng-if="risk_data.pageNow<risk_data.maxPage"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="risk(risk_data.pageNow+1)"
                            ng-if="risk_data.pageNow<risk_data.maxPage">下一页</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>
<script src="/js/controllers/assets_risky.js"></script>
