<?php
/* @var $this yii\web\View */

$this->title = '暗网预警';
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

    /* 暗网 */
    .darknet_content {
        padding: 36px 48px;
    }

    .row {
        padding: 0;
        margin: 0;
    }

    .darknet_row {
        background: #FFFFFF;
        border-radius: 6px;
        min-height: 200px;
    }

    .darknet_top {
        height: 112px;
        padding: 46px 36px 0 36px;
        position: relative;
    }

    .search_input_box {
        float: left;
        position: relative;
    }

    .search_input {
        border: 1px solid #ECECEC;
        border-radius: 4px;
        height: 42px;
        width: 210px;
        padding-left: 34px;
        margin-right: 16px;
    }

    .search_input::-webkit-input-placeholder {
        font-size: 14px;
        color: #BBBBBB;
    }

    button:focus,
    select:focus,
    input:focus {
        outline: none;
        outline-offset: 0px;
        border: 1px solid #0070FF;
    }

    .search_icon {
        position: absolute;
        top: 15px;
        left: 13px;
    }

    .darknet_table {
        min-height: 200px;
    }

    .search_btn {
        height: 42px;
        width: 124px;
        margin-left: 36px;
        background: #0070FF;
        border-radius: 4px;
        font-size: 16px;
        color: #FFFFFF
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

    .img_margin {
        margin: 0 3px;
        cursor: pointer;
    }

    td,
    th {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: left;
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

    .container_ul {
        width: 210px;
        height: 100px;
        overflow-y: auto;
        position: absolute;
        top: 43px;
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

    .zeromodal-container {
        border-radius: 8px;
        padding: 20px 36px;
    }

    .zeromodal-header span {
        font-size: 18px;
        color: #333333;
        border-left: 3px solid #0070FF;
        padding-left: 5px;
    }

    .model_item {
        height: 32px;
        margin: 20px 0;
        line-height: 32px;
    }

    .model_tilte {
        font-size: 16px;
        color: #333333;
        width: 105px;
        height: 32px;
        line-height: 32px;
        float: left;
    }

    .model_tilte_span {
        font-size: 16px;
        color: #333333;
    }

    .mode_top_input {
        height: 32px;
        border: 1px solid #ECECEC;
        border-radius: 4px;
        font-size: 14px;
        color: #333333;
        width: 363px;
        padding-left: 5px;
    }

    .mode_mid_input {
        height: 32px;
        border: 1px solid #ECECEC;
        border-radius: 4px;
        font-size: 14px;
        color: #333333;
        width: 160px;
        padding-left: 5px;
    }

    .model_btn_box {
        margin-top: 36px;
        text-align: center;
    }

    .model_btn_save {
        background: #0070FF;
        border-radius: 4px;
        font-size: 16px;
        color: #FFFFFF;
        width: 124px;
        height: 40px;
        line-height: 40px;
    }

    .model_btn_cel {
        margin-left: 36px;
        border: 1px solid #0070FF;
        border-radius: 4px;
        font-size: 16px;
        color: #0070FF;
        width: 124px;
        height: 40px;
        line-height: 40px;
    }
</style>
<!-- Main content -->
<section ng-app="myApp" class="darknet_content" ng-controller="AlertDarknetCtrl" ng-cloak>
    <div class="row darknet_row">
        <div class="darknet_top">
            <div class="search_input_box">
                <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                <input type="text" class="search_input" placeholder="请输入预警描述" ng-model="searchData.theme"
                    ng-focus="get_loophole_name_focus()" ng-blur="get_loophole_name_blur();$event.stopPropagation();"
                    ng-keyup="myKeyup_loophole_name()">
                <ul class="container_ul" ng-show="select_loophole_name_if">
                    <li ng-repeat="item in theme_list" class="li_hover"
                        ng-click="select_loophole_name_item(item.theme);$event.stopPropagation();">
                        {{item.theme}}
                    </li>
                </ul>
            </div>
            <button class="search_btn" ng-click="get_dark_list(1)">搜索</button>
        </div>
        <div class="darknet_table">
            <table class="table  table-striped ng-cloak">
                <tr style="text-algin:center" class="alert_table_tr">
                    <th style="width:200px;padding-left:36px;">预警时间</th>
                    <th >预警描述</th>
                    <th style="width:120px">情报来源</th>
                    <th style="width:120px">验证状态</th>
                    <th style="width:180px">操作</th>
                </tr>
                <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in darknet_list_data.data"
                    ng-click="dark_detail(item)">
                    <td style="min-width:150px;padding-left:36px;">{{item.time}}</td>
                    <td title="{{item.theme}}">{{item.theme}}</td>
                    <td>{{item.source}} </td>
                    <td>{{item.verify_status=='0'?'未验证':'已验证'}}</td>
                    <td>
                        <img src="/images/alert/look_detail.png" class="img_margin" ng-click="dark_detail(item)" alt="">
                        <img src="/images/alert/down_load.png" class="img_margin"
                            ng-click="darknet_download(item.rar_name)" alt="">
                        <img src="/images/alert/set.png" class="img_margin" ng-click="set_model(item)" alt="">
                    </td>
                </tr>
            </table>
            <p>
                <em style="font-size: 14px;padding-left:36px;color: #BBBBBB;">共有<span
                        ng-bind="darknet_list_data.count"></span>条预警</em>
            </p>
            <div style="padding: 0px; position: relative;height:60px;">
                <!-- angularjs分页 -->
                <ul class="pagination pagination-sm  pull-right ng-cloak" style="margin-right:36px;">
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.pageNow-1)"
                            ng-if="darknet_list_data.pageNow>1">上一页</a></li>
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(1)"
                            ng-if="darknet_list_data.pageNow>1">1</a>
                    </li>
                    <li><a href="javascript:void(0);" ng-if="darknet_list_data.pageNow>4">...</a></li>
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.pageNow-2)"
                            ng-bind="darknet_list_data.pageNow-2" ng-if="darknet_list_data.pageNow>3"></a></li>
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.pageNow-1)"
                            ng-bind="darknet_list_data.pageNow-1" ng-if="darknet_list_data.pageNow>2"></a></li>
                    <li class="active"><a href="javascript:void(0);" ng-bind="darknet_list_data.pageNow"></a></li>
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.pageNow+1)"
                            ng-bind="darknet_list_data.pageNow+1"
                            ng-if="darknet_list_data.pageNow<darknet_list_data.maxPage-1"></a></li>
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.pageNow+2)"
                            ng-bind="darknet_list_data.pageNow+2"
                            ng-if="darknet_list_data.pageNow<darknet_list_data.maxPage-2"></a></li>
                    <li><a href="javascript:void(0);"
                            ng-if="darknet_list_data.pageNow<darknet_list_data.maxPage-3">...</a></li>

                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.maxPage)"
                            ng-bind="darknet_list_data.maxPage"
                            ng-if="darknet_list_data.pageNow<darknet_list_data.maxPage"></a></li>
                    <li><a href="javascript:void(0);" ng-click="get_dark_list(darknet_list_data.pageNow+1)"
                            ng-if="darknet_list_data.pageNow<darknet_list_data.maxPage">下一页</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 设置验证 -->
    <div style="display: none;" id="darknet_hideenBox">
        <div id="darknet_model">
            <div class="model_item">
                <div class="model_tilte">SMTP服务器</div>
                <!-- <input type="text" class="mode_top_input" ng-model="model_data.theme"> -->
                <input type="text" class="mode_top_input" >
            </div>
            <div class="model_item">
                <div class="model_tilte">端口</div>
                <input type="text" class="mode_mid_input">
            </div>
            <div class="model_item">
                <span class="model_tilte_span">SMTP启用安全连接SSL</span>
                <input style="margin-left:40px;margin-right:6px;" type="checkbox" ng-model="ssl_data"  name='isHappy'
                 ng-change="ssl_change(ssl_data)">
                 <span class="model_tilte_span">
                 启用
                 </span>
            </div>
            <div class="model_btn_box">
                <button class="model_btn_save" ng-click="model_save()">保存</button>
                <button class="model_btn_cel" ng-click="model_cel()">取消</button>
            </div>
        </div>
    </div>
</section>
<script src="/js/controllers/alert_darknet.js"></script>
