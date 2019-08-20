<?php
/* @var $this yii\web\View */

$this->title = '报表生成';
?>
<style>
    .head {
        background-color: #fff;
        padding: 20px;
        margin-bottom: 10px;
        border-top: 1px solid #d2d6de;
    }

    /* 选中状态图标定制 */
    .i-checks {
        padding-left: 20px;
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0 !important;
    }

    .i-checks input {
        position: absolute;
        margin-left: -20px;
        opacity: 0;
    }

    .i-checks input:checked+i {
        border-color: #3c8dbc;
    }

    .i-checks>i {
        position: relative;
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-top: -2px;
        margin-right: 4px;
        margin-left: -20px;
        line-height: 1;
        vertical-align: middle;
        background-color: #fff;
        border: 1px solid #cfdadd;
    }

    .i-checks input:checked+i:before {
        top: 4px;
        left: 4px;
        width: 10px;
        height: 10px;
        background-color: #3c8dbc;
    }

    .i-checks>i:before {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background-color: transparent;
        content: "";
        -webkit-transition: all 0.2s;
        transition: all 0.2s;
    }

    *:before,
    *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    #reservationtime {
        border-radius: 5px;
    }

    /* 表头 */
    .head-top,
    .head-mid,
    .head-name {
        margin-bottom: 20px;
        /* border: 1px solid #ddd; */
        height: 40px;
        line-height: 40px;
    }

    /* .main{
    /* height: 800px; */
    background-color: #fff;
    border-radius: 5px;
    padding: 20px;
    border-top: 3px solid #d2d6de;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    */ .cursor {
        cursor: pointer;
    }

    .head-mid-title {
        font-size: 14px;
        /* font-weight: 700; */
    }

    .item-span-content {
        font-size: 14px;
    }

    .alertInfo {
        color: red;
        margin-left: 10px;
    }

    .bordercolor {
        border: 1px solid red;
    }
    .time_input_report {
        min-width: 310px
    }

    label {
        font-weight: 400;
    }

    .strategy_item {
        height: 35px;
    }

    .strategy_label {
        display: inline-block;
        width: 120px;
        margin-right: 10px;
    }

    .report_content {
        padding: 10px;
    }
</style>
<link rel="stylesheet" href="/css/report/report.css">
<section ng-app="myApp" ng-controller="reportCtrl" ng-cloak>
    <div class="report_container">
        <div class="report_container_top">
            <div class="report_container_top_left"> 状态</div>
            <img src="/images/report/time.png" class="start_time_icon" alt="">
            <img src="/images/report/time.png" class="end_time_icon" alt="">
            <input class="input_box" id="start_time_picker" readonly type="text" placeholder="选择开始时间">
            <input class="input_box" id="end_time_picker" readonly type="text" placeholder="选择结束时间">
        </div>
        <div class="report_container_mid">
            <div class="report_container_mid_left"> 发送格式</div>
            <span ng-click="word_choose()" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="word_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!word_true" alt="">
                <span>Word</span>
            </span>
            <span ng-click="excel_choose()" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!word_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="word_true" alt="">
                <span>Excel</span>
            </span>
        </div>
        <div class="report_container_bom">
            <div class="report_container_bom_left"> 报表名称</div>
            <input class="input_bom_box" type="text" ng-model="report_data.report_name" placeholder="请输入报表名称">
        </div>
        <div class="btn_box">
            <button class="save_btn" ng-click="add_report()">
                保存
            </button>
            <button class="cancel_btn">
                取消
            </button>
        </div>
        <table class="table  domain_table ng-cloak">
            <tr style="text-algin:center">
                <th class="width_65">序号</th>
                <th>日期</th>
                <th>名称</th>
                <th>时间范围</th>
                <th>格式</th>
                <th>操作</th>
            </tr>
            <tr ng-repeat="item in report_list.data">
                <td ng-bind="$index + 1 + (report_list.pageNow - 1)*10">1</td>
                <td ng-bind="item.create_time">2018-05-09</td>
                <td ng-bind="item.report_name">月报</td>
                <td>
                    <span ng-bind="item.stime"></span> -
                    <span ng-bind="item.etime"></span>
                </td>
                <td ng-bind="item.report_type"></td>
                <td class="cursor">
                    <img src="/images/report/down.png" class="img_icon" alt="" ng-click="report_download(item)">
                    <img src="/images/report/del.png" class="img_icon" alt="" ng-click="report_del(item)">
                </td>
            </tr>
        </table>
        <div style="padding: 25px;">
            <span style="font-size: 14px;color: #BBBBBB;">共有
                <span ng-bind="report_list.count"></span>条</span>
            <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.pageNow-1)"
                        ng-if="report_list.pageNow>1">上一页</a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(1)" ng-if="report_list.pageNow>1">1</a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-if="report_list.pageNow>4">...</a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.pageNow-2)"
                        ng-bind="report_list.pageNow-2" ng-if="report_list.pageNow>3"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.pageNow-1)"
                        ng-bind="report_list.pageNow-1" ng-if="report_list.pageNow>2"></a>
                </li>
                <li class="active">
                    <a href="javascript:void(0);" ng-bind="report_list.pageNow"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.pageNow+1)"
                        ng-bind="report_list.pageNow+1" ng-if="report_list.pageNow<report_list.maxPage-1"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.pageNow+2)"
                        ng-bind="report_list.pageNow+2" ng-if="report_list.pageNow<report_list.maxPage-2"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-if="report_list.pageNow<report_list.maxPage-3">...</a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.maxPage)"
                        ng-bind="report_list.maxPage" ng-if="report_list.pageNow<report_list.maxPage"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" ng-click="get_report_list(report_list.pageNow+1)"
                        ng-if="report_list.pageNow<report_list.maxPage">下一页</a>
                </li>
            </ul>
        </div>
    </div>
        <div class="echarts">
            <!-- 生成预警趋势图片 -->
            <div id="waring_trend"></div>
            <!-- 威胁程度统计; -->
            <div id="alert_caterory"></div>
            <div id="loophole_caterory"></div>
            <div id="darknet_caterory"></div>
            <div id="waring_category"></div>
            <div id="monitor_assets_host"></div>
            <div id="monitor_assets_website"></div>
            <!-- 漏洞攻击预警 -->
            <div id="loophole_level"></div>
            <!-- 情报更新 -->
            <div id="intelligence_update"></div>
        </div>
</section>
<script src="/js/controllers/report.js"></script>
