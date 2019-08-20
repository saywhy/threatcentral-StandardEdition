<?php
$this->title = '漏洞预警';
?>
<style>
    .loophole_item {
        border-radius: 3px;
        border: 1px solid #eee;
        height: 120px;
        margin: 20px 0;
        padding: 20px 10px;
    }

    .loophole_item_left {
        border-right: 1px solid #eee;
        height: 100%;
        text-align: center;
    }

    .loophole_item_left_num {
        color: red;
        font-size: 28px;
        font-weight: 700;
    }

    .loophole_item_right {
        text-align: center;
        height: 100%;
    }

    .icon {
        width: 1em;
        height: 1em;
        vertical-align: -0.15em;
        fill: currentColor;
        overflow: hidden;
    }

    #alarm_echart {
        height: 200px;
        width: 100%;
    }

    .dark_net_left {
        border: 1px solid #eee;
        padding: 0;
        overflow-y: scroll;
        height: 700px;
    }

    ul,
    li {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .dark_net_left_item {
        border-top: 2px solid #d2d6de;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        border-radius: 3px;
        padding: 10px;
        height: 60px;
        background: #fff;
        margin: 10px 0;

    }

    .active_dark_net {
        background-color: #3c8dbc;
        color: #fff !important;
    }

    .velnerabilites {
        background-color: #ecf0f5;
        height: 3px;
        width: 100%;
    }

    .high_line {
        float: left;
        width: 20%;
        background-color: rgba(150, 33, 22, .8);
        height: 100%;
    }

    .high_num {
        width: 20%;
        color: rgba(150, 33, 22, .8);
        display: inline-block;
    }

    .mid_num {
        width: 20%;
        color: rgba(245, 191, 65, .8);
        display: inline-block;
    }

    .low_num {
        width: 30%;
        color: rgba(74, 164, 110, .8);
        display: inline-block;
    }

    .mid_line {
        float: left;
        width: 20%;
        background-color: rgba(245, 191, 65, .8);
        height: 100%;
    }

    .low_line {
        float: left;
        width: 30%;
        background-color: rgba(74, 164, 110, .8);
        height: 100%;
    }

    .anwang {
        background: #ecf0f5;
    }

    .item_p {
        margin: 0;
    }

    .border_top {
        border-top: 1px solid #d2d6de;
    }

    .border_bom {
        border-bottom: 1px solid #d2d6de;
    }

    .table tr td .progress {
        margin-top: 0;
    }

    .container_ul {
        width: 210px;
        height: 100px;
        overflow-y: auto;
        position: absolute;
        top: 45px;
        border: 1px solid #ECECEC;
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

    /* 漏洞 */
    .loop_content {
        padding: 48px;
    }

    .row,
    .col-md-4,
    .col-md-5,
    .col-md-7,
    p {
        margin: 0;
        padding: 0;
    }

    .loop_row {
        height: 192px;
    }

    .loop_item_left {
        padding-right: 20px;
    }

    .loop_item_mid {
        padding: 0 10px;
    }

    .loop_item_right {
        padding-left: 20px;
    }

    .loop_item_content {
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.12);
        border-radius: 6px;
        height: 192px;
        padding: 24px 0;
    }

    .loop_item_content_left {
        border-right: 1px solid #ECECEC;
        height: 144px;
        position: relative;

    }

    .loop_item_content_left_box {
        width: 120px;
        height: 97px;
        transform: translate(-50%, -50%);
        position: absolute;
        top: 46%;
        left: 50%;
    }

    .loop_item_content_left_num {
        font-size: 56px;
        color: #333333;
        text-align: center;
    }

    .loop_item_content_left_name {
        font-size: 12px;
        color: #999999;
        text-align: center;
    }

    #loop_total,
    #loop_num_7,
    #risk_num {
        width: 100%;
        height: 144px;
    }

    .loop_table {
        min-height: 200px;
        background: #FFFFFF;
        border-radius: 6px;
        margin: 48px 0;
    }

    /* 引用  */
    .loop_table_box {
        /* margin: 0 48px; */
        background-color: #fff;
        border-radius: 6px;
        padding-top: 46px;
    }

    .alert_box_top {
        padding-left: 36px;
    }

    .alert_search_input {
        border: 1px solid #ECECEC;
        border-radius: 4px;
        height: 42px;
        width: 180px;
        padding-left: 34px;
        margin-right: 16px;
    }

    .alert_search_input::-webkit-input-placeholder {
        font-size: 14px;
        color: #BBBBBB;
    }

    select:focus,
    input:focus {
        outline: none;
        outline-offset: 0px;
        border: 1px solid #0070FF;
    }

    .button_search {
        background: #0070FF;
        border-radius: 4px;
        height: 42px;
        width: 124px;
        color: #fff;
        margin: 0 24px;
    }

    .button_down {
        border: 1px solid #0070FF;
        border-radius: 4px;
        height: 42px;
        width: 124px;
        color: #0070FF;
    }

    button:focus {
        outline: none;
    }

    button:active {
        outline: none;
    }

    table tr:nth-child(even) {
        background-color: #fff
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #EEF6FF
    }

    .alert_table_tr {
        height: 48px;
        line-height: 48px;
    }

    .btn_look {
        border-radius: 4px;
        width: 68px;
        height: 28px;
        line-height: 28px;
        font-size: 12px;
        color: #fff;
        background: #64A8FF;
        padding: 0;
        position: relative;
    }

    .btn_look_closed {
        background: #F2F2F2;
        border-radius: 4px;
        font-size: 12px;
        width: 68px;
        height: 28px;
        color: #BBBBBB;
    }

    .td_operation {
        overflow: inherit;
    }

    .td_ul {
        position: absolute;
        z-index: 99;
        width: 68px;
        background: #FFFFFF;
        box-shadow: 0 0 6px 0 rgba(0, 0, 0, 0.16);
        border-radius: 4px;
        font-size: 12px;
        color: #333;
        left: -1px;
    }

    .td_li {
        width: 100%;
        height: 28px;
    }

    .td_li:hover {
        background: #0070FF;
        color: #fff;
        cursor: pointer;
    }

    .choose_box {
        width: 100%;
        height: 68px;
        background: #F8F8F8;
    }

    .choose_box_left {
        float: left;
        line-height: 68px;
        width: 300px;
        padding-left: 36px;
    }

    .choose_box_mid {
        float: right;
        line-height: 68px;
    }

    .choose_box_right {
        float: right;
        height: 68px;
        position: relative;
        width: 316px;
    }

    .choose_text {
        font-size: 14px;
        color: #999999;
    }

    .select_choose_type {
        width: 114px;
        height: 36px;
        border-radius: 4px;
        background-color: #64A8FF !important;
        color: #fff;
        border: none;
    }

    .select_choose_type_true {
        width: 114px;
        height: 36px;
        border-radius: 4px;
        background-color: #F2F2F2 !important;
        color: #bbb;
        border: none;
    }

    .cursor {
        cursor: pointer;

    }

    .cel_btn {
        border-radius: 4px;
        width: 104px;
        height: 36px;
        font-size: 16px;
        position: absolute;
        left: 48px;
        top: 50%;
        transform: translateY(-50%);
        color: #0070FF;
        border: 1px solid #0070FF;
    }

    .cel_btn_true {
        border-radius: 4px;
        width: 104px;
        height: 36px;
        font-size: 16px;
        position: absolute;
        left: 48px;
        top: 50%;
        transform: translateY(-50%);
        color: #BBBBBB;
        border: 1px solid #BBBBBB;
    }

    .ok_btn {
        width: 104px;
        height: 36px;
        background: #0070FF;
        border-radius: 4px;
        color: #fff;
        position: absolute;
        right: 36px;
        top: 50%;
        transform: translateY(-50%);
    }

    .ok_btn_true {
        width: 104px;
        height: 36px;
        border: none;
        background: #F2F2F2;
        border-radius: 4px;
        color: #BBBBBB;
        position: absolute;
        right: 36px;
        top: 50%;
        transform: translateY(-50%);
    }

    .pagination>.active>a,
    .pagination>.active>a:focus,
    .pagination>.active>a:hover,
    .pagination>.active>span,
    .pagination>.active>span:focus,
    .pagination>.active>span:hover {
        z-index: 3;
        color: #fff;
        cursor: default;
        background: #0070FF;
        border-radius: 4px;
        border-color: #0070FF;
    }

    .search_icon_box {
        position: relative;
        height: 42px;
        display: inline-block;
    }

    .search_icon {
        position: absolute;
        top: 14px;
        left: 10px;
    }

    .container_ul {
        width: 180px;
        height: 100px;
        overflow-y: auto;
        position: absolute;
        top: 45px;
        border: 1px solid #ECECEC;
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
     td,
    th {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: left;
    }
</style>
<section class="loop_content" ng-app="myApp" ng-controller="AlertLoopholeCtrl" ng-cloak>
    <div class="row loop_row">
        <div class="col-md-4 loop_item_left">
            <div class="loop_item_content">
                <div class="row">
                    <div class="loop_item_content_left col-md-5">
                        <div class="loop_item_content_left_box">
                            <p class="loop_item_content_left_num">{{loop_top_data.all_loophole.total_count}}</p>
                            <p class="loop_item_content_left_name">总漏洞数</p>
                        </div>
                    </div>
                    <div class="loop_item_content_right col-md-7">
                        <div id="loop_total"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 loop_item_mid">
            <div class="loop_item_content">
                <div class="row">
                    <div class="loop_item_content_left col-md-5">
                        <div class="loop_item_content_left_box">
                            <p class="loop_item_content_left_num">{{loop_top_data.last_7day_loophole.total_count}}</p>
                            <p class="loop_item_content_left_name">7天内新增漏洞数</p>
                        </div>
                    </div>
                    <div class="loop_item_content_right col-md-7">
                        <div id="loop_num_7"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 loop_item_right">
            <div class="loop_item_content">
                <div class="row">
                    <div class="loop_item_content_left col-md-5">
                        <div class="loop_item_content_left_box">
                            <p class="loop_item_content_left_num">{{loop_top_data.effect_asset.total_count}}</p>
                            <p class="loop_item_content_left_name">受影响资产数</p>
                        </div>
                    </div>
                    <div class="loop_item_content_right col-md-7">
                        <div id="risk_num"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row loop_table">
        <div class="loop_table_box" ng-click="alert_box()">
            <div class="alert_box_top">
                <span class="search_icon_box">
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                    <input type="text" class="alert_search_input" ng-focus="get_device_ip_focus()"
                        ng-blur="get_device_ip_blur();" ng-keyup="myKeyup_device_ip(loop_serch_data.device_ip)"
                        placeholder="请输入受影响资产" ng-model="loop_serch_data.device_ip">
                    <ul class="container_ul" ng-show="select_device_ip_if" style="top:58px;">
                        <li ng-repeat="item in select_device_ip" class="li_hover"
                            ng-click="select_device_ip_item(item.device_ip)">
                            {{item.device_ip}}
                        </li>
                    </ul>
                </span>
                <select class="alert_search_input" style="background-color: #fff;" ng-model="loop_serch_data.company"
                    ng-options="x.num as x.type for x in company_select_loophole"></select>
                <span class="search_icon_box">
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                    <input type="text" class="alert_search_input" ng-focus="get_loophole_name_focus()"
                        ng-blur="get_loophole_name_blur()"
                        ng-keyup="myKeyup_loophole_name(loop_serch_data.loophole_name)" placeholder="请输入受影响资产"
                        ng-model="loop_serch_data.loophole_name">
                    <ul class="container_ul" ng-show="select_loophole_name_if" style="top:58px;">
                        <li ng-repeat="item in select_loophole_name" class="li_hover"
                            ng-click="select_loophole_name_item(item.loophole_name)">
                            {{item.loophole_name}}
                        </li>
                    </ul>
                </span>
                <select class="alert_search_input" style="background-color: #fff;" ng-model="loop_serch_data.poc"
                    ng-options="x.num as x.type for x in poc_selsect"></select>
                <button class="button_search" ng-click="loop_serch()">搜索</button>
                <button class="button_down" ng-click="loop_download()">导出报表</button>
            </div>
            <div class="row" style="margin:0;padding:24px 0" ng-click="blur_input()">
                <table class="table  table-striped ng-cloak">
                    <tr style="text-algin:center" class="alert_table_tr" >
                        <th style="text-algin:center;width:70px;padding-left:36px;">
                            <img src="/images/alert/select_false.png" class="cursor" ng-if="choose_all"
                                ng-click="choose_click_all('false');$event.stopPropagation();" alt="">
                            <img src="/images/alert/select_true.png" class="cursor" ng-if="!choose_all"
                                ng-click="choose_click_all('true');$event.stopPropagation();" alt="">
                        </th>
                        <th >漏洞名称</th>
                        <th >受影响资产</th>
                        <th style="width:100px">所属分组</th>
                        <th style="width:100px">POC</th>
                        <th style="width:150px">预警时间</th>
                        <th style="width:100px">风险状态</th>
                        <th style="padding-right:36px;width:150px">处理状态</th>
                    </tr>
                    <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in loophole.data"
                        ng-click="loophole_detail(item)">
                        <td style="text-algin:center;width:35px;padding-left:36px;"
                            ng-clikc="choose_click_td();$event.stopPropagation();">
                            <img src="/images/alert/select_false.png" class="cursor"
                                ng-if="item.choose_status&&item.risk_process!='IGNORED'&&item.risk_process!='RESOLVED'"
                                ng-click="choose_click($index);$event.stopPropagation();" alt="">
                            <img src="/images/alert/select_true.png" class="cursor"
                                ng-if="!item.choose_status&&item.risk_process!='IGNORED'&&item.risk_process!='RESOLVED'"
                                ng-click="choose_click($index);$event.stopPropagation();" alt="">
                        </td>
                        <td ng-click="click_clientip();">
                            <img src="/images/alert/h.png" ng-if="item.level == '高'" alt="">
                            <img src="/images/alert/m.png" ng-if="item.level == '中'" alt="">
                            <img src="/images/alert/l.png" ng-if="item.level == '低'" alt="">
                            <span ng-bind="item.loophole_name" title="{{item.loophole_name}}" > </span>
                        </td>
                        <td>{{item.device_ip}}</td>
                        <td>{{item.company}}</td>
                        <td>{{item.poc}}</td>
                        <td>{{item.time}}</td>
                        <td>{{item.risk_status_cn}}</td>
                        <td style="padding-right:36px;" class="td_operation">
                            <button class="btn_look" ng-click="operation_click($index);$event.stopPropagation();"
                                ng-if="item.risk_process!='IGNORED'&& item.risk_process!='RESOLVED'">
                                <span ng-bind="status_str[item.risk_process].label"></span>
                                <img src="/images/alert/down.png" alt="">
                                <ul class="td_ul" ng-if="item_operation == $index ">
                                    <li class="td_li" ng-click="update_alert(item,'CONFIRMED');$event.stopPropagation();"
                                        ng-if="item.risk_process!='CONFIRMED'">
                                        处置中
                                    </li>
                                    <li class="td_li" ng-click="update_alert(item,'IGNORED');$event.stopPropagation();">
                                        已忽略
                                    </li>
                                    <li class="td_li" ng-click="update_alert(item,'RESOLVED');$event.stopPropagation();">
                                        已解决
                                    </li>
                                </ul>
                            </button>
                            <button class="btn_look_closed" ng-if="item.risk_process=='IGNORED'">
                                已忽略
                            </button>
                            <button class="btn_look_closed" ng-if="item.risk_process=='RESOLVED'">
                                已解决
                            </button>
                        </td>
                    </tr>
                </table>
                <p>
                    <em style="font-size: 14px;padding-left:36px;color: #BBBBBB;">共有<span
                            ng-bind="loophole.count"></span>条预警</em>
                </p>
                <div class="choose_box">
                    <div class="choose_box_left">
                        <p style="margin:0">
                            <img src="/images/alert/select_false.png" class="cursor" ng-if="choose_all"
                                ng-click="choose_click_all('false')" alt="">
                            <img src="/images/alert/select_true.png" class="cursor" ng-if="!choose_all"
                                ng-click="choose_click_all('true')" alt="">
                            <span>全选</span>
                            <span class="choose_text">(已选择</span>
                            <span class="choose_text">{{choose_count_array.length}}</span>
                            <span class="choose_text">条预警)</span>
                        </p>
                    </div>
                    <div class="choose_box_right">
                        <button ng-disabled='choose_count_array.length==0' ng-click="cel()"
                            ng-class="{true : 'cel_btn_true',false : 'cel_btn' }[choose_count_array.length==0]">取消</button>
                        <button ng-disabled='choose_count_array.length==0'
                            ng-class="{true : 'ok_btn_true',false : 'ok_btn' }[choose_count_array.length==0]"
                            ng-click="batch_updata()">确定</button>
                    </div>
                    <div class="choose_box_mid">
                        　<span style="margin-right:12px;">更改处理状态为</span>
                        <select ng-disabled='choose_count_array.length==0'
                            ng-class="{true : 'select_choose_type_true',false : 'select_choose_type' }[choose_count_array.length==0]"
                            ng-model="select_choose" ng-options="x.num as x.type for x in select_status"></select>
                    </div>
                </div>
                <div style="padding: 0px; position: relative;height:60px;">
                    <!-- angularjs分页 -->
                    <ul class="pagination pagination-sm  pull-right ng-cloak" style="margin-right:36px;">
                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.pageNow-1)"
                                ng-if="loophole.pageNow>1">上一页</a></li>
                        <li><a href="javascript:void(0);" ng-click="loop_serch(1)" ng-if="loophole.pageNow>1">1</a>
                        </li>
                        <li><a href="javascript:void(0);" ng-if="loophole.pageNow>4">...</a></li>
                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.pageNow-2)" ng-bind="loophole.pageNow-2"
                                ng-if="loophole.pageNow>3"></a></li>
                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.pageNow-1)" ng-bind="loophole.pageNow-1"
                                ng-if="loophole.pageNow>2"></a></li>
                        <li class="active"><a href="javascript:void(0);" ng-bind="loophole.pageNow"></a></li>
                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.pageNow+1)" ng-bind="loophole.pageNow+1"
                                ng-if="loophole.pageNow<loophole.maxPage-1"></a></li>
                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.pageNow+2)" ng-bind="loophole.pageNow+2"
                                ng-if="loophole.pageNow<loophole.maxPage-2"></a></li>
                        <li><a href="javascript:void(0);" ng-if="loophole.pageNow<loophole.maxPage-3">...</a></li>

                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.maxPage)" ng-bind="loophole.maxPage"
                                ng-if="loophole.pageNow<loophole.maxPage"></a></li>
                        <li><a href="javascript:void(0);" ng-click="loop_serch(loophole.pageNow+1)"
                                ng-if="loophole.pageNow<loophole.maxPage">下一页</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- 漏洞详情 -->
    <div style="display: none;" id="loop_hideenBox">
        <div id="loop_detail">
            <div>
                <h4>漏洞详情</h4>
                <div ng-bind-html='html_content'></div>
            </div>
            <div>
                <h4>漏洞日志</h4>
                <ul>
                    <li>
                        <span>risk_id:</span>
                        <span>{{loop_log.risk_id}}</span>
                    </li>
                    <li>
                        <span>risk_sn:</span>
                        <span>{{loop_log.risk_sn}}</span>
                    </li>
                    <li>
                        <span>risk_name:</span>
                        <span>{{loop_log.risk_name}}</span>
                    </li>
                    <li>
                        <span>vuln_type:</span>
                        <span>{{loop_log.vuln_type}}</span>
                    </li>
                    <li>
                        <span>general_type:</span>
                        <span>{{loop_log.general_type}}</span>
                    </li>
                    <li>
                        <span>vuln_id:</span>
                        <span>{{loop_log.vuln_id}}</span>
                    </li>
                    <li>
                        <span>risk_addr:</span>
                        <span>{{loop_log.risk_addr}}</span>
                    </li>
                    <li>
                        <span>risk_level:</span>
                        <span>{{loop_log.risk_level}}</span>
                    </li>
                    <li>
                        <span>risk_status:</span>
                        <span>{{loop_log.risk_status}}</span>
                    </li>
                    <li>
                        <span>risk_process:</span>
                        <span>{{loop_log.risk_process}}</span>
                    </li>
                    <li>
                        <span>last_detect_tm:</span>
                        <span>{{loop_log.last_detect_tm}}</span>
                    </li>
                    <li>
                        <span>insert_tm:</span>
                        <span>{{loop_log.insert_tm}}</span>
                    </li>
                    <li>
                        <span>insert_tm:</span>
                        <span>{{loop_log.insert_tm}}</span>
                    </li>
                    <li>
                        <span>update_tm:</span>
                        <span>{{loop_log.update_tm}}</span>
                    </li>
                    <li>
                        <span>relation_domain:</span>
                        <span>{{loop_log.relation_domain}}</span>
                    </li>
                    <li>
                        <span>location:</span>
                        <span>{{loop_log.location}}</span>
                    </li>
                    <li>
                        <span>detect_status:</span>
                        <span>{{loop_log.detect_status}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<script src="/js/controllers/alert_loophole.js"></script>
