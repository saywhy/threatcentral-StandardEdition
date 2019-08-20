<?php
/* @var $this yii\web\View */

$this->title = '威胁预警';
// $this->params['chartVersion'] = '1.1.1';
?>
<style>
    .loophole_item {
        border-radius: 3px;
        border: 1px solid #eee;
        height: 120px;
        margin: 20px 0;
        padding: 20px 10px;
    }

    td,
    th {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: left;
    }

    .icon {
        width: 1em;
        height: 1em;
        vertical-align: -0.05em;
        fill: currentColor;
        overflow: hidden;
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
        height: 380px;
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

    .echarts_box {
        height: 383px;
        margin: 36px 48px;
        background-color: #fff;
        border-radius: 6px;
    }

    .alert_box {
        margin: 0 48px;
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
        width: 210px;
        padding-left: 34px;
        margin-right: 16px;
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
    border:none;
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
    .alert_search_input::-webkit-input-placeholder{
font-size: 14px;
color: #BBBBBB;
}
</style>
<section class="content" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <div class="echarts_box">
        <div id="alarm_echart"></div>
    </div>
    <div class="alert_box" ng-click="alert_box()">
        <div class="alert_box_top">
            <span class="search_icon_box">
                <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                <input type="text" class="alert_search_input" ng-focus="get_client_ip_focus()"
                    ng-blur="get_client_ip_blur()" ng-keyup="myKeyup_client_ip(searchData.client_ip)"
                    placeholder="请输入受影响资产" ng-model="searchData.client_ip">
                <ul class="container_ul" ng-show="select_client_ip_if">
                    <li ng-repeat="item in select_client_ip" class="li_hover"
                        ng-click="select_client_ip_item(item.client_ip)">
                        {{item.client_ip}}
                    </li>
                </ul>
            </span>
            <select class="alert_search_input" style="background-color: #fff;" ng-model="searchData.category"
                ng-options="x.num as x.type for x in category_select"></select>
            <span class="search_icon_box">
                <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                <input type="text" class="alert_search_input" ng-focus="get_indicator_focus()"
                    ng-blur="get_indicator_blur()" ng-keyup="myKeyup_indicator(searchData.indicator)"
                    placeholder="请输入威胁指标" ng-model="searchData.indicator">
                <ul class="container_ul" ng-show="select_indicator_if">
                    <li ng-repeat="item in select_indicator" class="li_hover"
                        ng-click="select_indicator_item(item.indicator)">
                        {{item.indicator}}
                    </li>
                </ul>
            </span>
            <select class="alert_search_input" style="background-color: #fff;" ng-model="searchData.company"
                ng-options="x.num as x.type for x in company_select"></select>
            <button class="button_search" ng-click="search()">搜索</button>
            <button class="button_down" ng-click="download()">导出报表</button>
        </div>
        <div class="row" style="margin:0;padding:24px 0" ng-click="blur_input()">
            <table class="table  table-striped ng-cloak">
                <tr style="text-algin:center" class="alert_table_tr">
                    <th style="text-algin:center;width:70px;padding-left:36px;">
                        <img src="/images/alert/select_false.png" class="cursor" ng-if="choose_all"
                            ng-click="choose_click_all('false');$event.stopPropagation();" alt="">
                        <img src="/images/alert/select_true.png" class="cursor" ng-if="!choose_all"
                            ng-click="choose_click_all('true');$event.stopPropagation();" alt="">
                    </th>
                    <th style="width: 200px;">受影响资产</th>
                    <th>资产分组</th>
                    <th>预警类型</th>
                    <th>威胁指标</th>
                    <th>预警时间</th>
                    <th>处理状态</th>
                    <th style="padding-right:36px;width: 120px;">操作</th>
                </tr>
                <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in pages.data" ng-click="detail(item)">
                    <td style="text-algin:center;width:35px;padding-left:36px;" ng-click="choose_click_td();$event.stopPropagation();">
                        <img src="/images/alert/select_false.png" class="cursor"
                        ng-if="item.choose_status&&item.status!='2' && item.status!='3'&& item.status!='4'"
                            ng-click="choose_click($index);$event.stopPropagation();" alt="">
                        <img src="/images/alert/select_true.png" class="cursor"
                        ng-if="!item.choose_status&&item.status!='2' && item.status!='3'&& item.status!='4'"
                            ng-click="choose_click($index);$event.stopPropagation();" alt="">
                    </td>
                    <td ng-click="click_clientip();">
                        <img src="/images/alert/h.png" ng-if="item.degree == '高'" alt="">
                        <img src="/images/alert/m.png" ng-if="item.degree == '中'" alt="">
                        <img src="/images/alert/l.png" ng-if="item.degree == '低'" alt="">
                        <span ng-bind="item.client_ip"> </span>
                    </td>
                    <td>{{item.company}}</td>
                    <td ng-bind="item.category"></td>
                    <td ng-bind="item.indicator"></td>
                    <td ng-bind="item.time*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                    </td>
                    <td>
                        <span ng-bind="status_str[item.status].label"></span>
                    </td>
                    <td style="padding-right:36px;" class="td_operation"ng-click="$event . stopPropagation();">
                        <button class="btn_look" ng-click="operation_click($index);$event.stopPropagation();" ng-if="item.status!='2'&& item.status!='3'">
                            <span ng-bind="status_str[item.status].label"></span>
                            <img src="/images/alert/down.png" alt="">
                            <ul class="td_ul" ng-if="item_operation == $index ">
                                <li class="td_li" ng-click="update_alert(item,'1');$event.stopPropagation();"
                                    ng-if="item.status!='1'&& item.status!='4'">
                                    处置中
                                </li>
                                <li class="td_li" ng-if="item.status!='4'" ng-click="update_alert(item,'2');$event.stopPropagation();">
                                    已解决
                                </li>
                                <li class="td_li" ng-if="item.status!='4'" ng-click="update_alert(item,'3');$event.stopPropagation();">
                                    已忽略
                                </li>
                                <li class="td_li" ng-click="update_alert(item,'4');$event.stopPropagation();" ng-if="item.category=='钓鱼仿冒'&& item.status!='4'">
                                    白名单
                                </li>
                                <li class="td_li" ng-if="item.status=='4'"
                                ng-click="update_alert(item,'5'); $event.stopPropagation();">
                                    取消白
                                </li>
                            </ul>
                        </button>
                        <button class="btn_look_closed" ng-if="item.status=='2'" ng-click="$event.stopPropagation();">
                            已解决
                        </button>
                        <button class="btn_look_closed" ng-if="item.status=='3'" ng-click="$event.stopPropagation();">
                            已忽略
                        </button>
                    </td>
                </tr>
            </table>
            <p>
                <em style="font-size: 14px;padding-left:36px;color: #BBBBBB;">共有<span
                        ng-bind="pages.count"></span>条预警</em>
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
                    <button ng-disabled='choose_count_array.length==0' ng-click="cel()" ng-class="{true : 'cel_btn_true',false : 'cel_btn' }[choose_count_array.length==0]">取消</button>
                    <button  ng-disabled='choose_count_array.length==0' ng-class="{true : 'ok_btn_true',false : 'ok_btn' }[choose_count_array.length==0]" ng-click="batch_updata()">确定</button>
                </div>
                <div class="choose_box_mid">
                    　<span style="margin-right:12px;">更改处理状态为</span>
                    <select  ng-disabled='choose_count_array.length==0' ng-class="{true : 'select_choose_type_true',false : 'select_choose_type' }[choose_count_array.length==0]" ng-model="select_choose"
                        ng-options="x.num as x.type for x in select_status"></select>
                </div>
            </div>
            <div style="padding: 0px; position: relative;height:60px;">
                <!-- angularjs分页 -->
                <ul class="pagination pagination-sm  pull-right ng-cloak" style="margin-right:36px;">
                    <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)"
                            ng-if="pages.pageNow>1">上一页</a></li>
                    <li><a href="javascript:void(0);" ng-click="getPage(1)" ng-if="pages.pageNow>1">1</a>
                    </li>
                    <li><a href="javascript:void(0);" ng-if="pages.pageNow>4">...</a></li>
                    <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow-2)" ng-bind="pages.pageNow-2"
                            ng-if="pages.pageNow>3"></a></li>
                    <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-bind="pages.pageNow-1"
                            ng-if="pages.pageNow>2"></a></li>
                    <li class="active"><a href="javascript:void(0);" ng-bind="pages.pageNow"></a></li>
                    <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-bind="pages.pageNow+1"
                            ng-if="pages.pageNow<pages.maxPage-1"></a></li>
                    <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow+2)" ng-bind="pages.pageNow+2"
                            ng-if="pages.pageNow<pages.maxPage-2"></a></li>
                    <li><a href="javascript:void(0);" ng-if="pages.pageNow<pages.maxPage-3">...</a></li>

                    <li><a href="javascript:void(0);" ng-click="getPage(pages.maxPage)" ng-bind="pages.maxPage"
                            ng-if="pages.pageNow<pages.maxPage"></a></li>
                    <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)"
                            ng-if="pages.pageNow<pages.maxPage">下一页</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<script src="/js/controllers/alert.js"></script>
