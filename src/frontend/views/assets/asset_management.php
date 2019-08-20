<?php
/* @var $this yii\web\View */

$this->title = '资产管理';
?>
<link rel="stylesheet" href="/css/common.css">
<style>
    .management_box {
        background-color: #fff;
        padding: 10px;
        border-radius: 5px;
    }

    .input_radius {
        border-radius: 3px;
    }

    .management_box_content {
        margin-top: 30px;
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

    /* 资产管理 */
    .Management_container {
        padding: 36px 48px;
    }

    .Management_main {
        min-height: 200px;
        background: #FFFFFF;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .Management_main_top {
        height: 124px;
        padding: 0 36px;
        position: relative;
        z-index: 99;
    }

    .Management_main_top_box {
        position: absolute;
        height: 42px;
        transform: translateY(-50%);
        top: 50%;
        width: 100%;
        /* border: 1px solid red; */
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

    .input_box_item {
        position: relative;
        height: 42px;
        display: inline-block;
    }

    .search_icon {
        position: absolute;
        left: 10px;
        transform: translateY(-50%);
        top: 50%;
    }

    .btn_search {
        background: #0070FF;
        border-radius: 4px;
        color: #fff;
        height: 42px;
        width: 124px;
        font-size: 16px;
        margin-left: 64px;
    }

    .btn_add {
        border: 1px solid #0070FF;
        border-radius: 4px;
        color: #0070FF;
        height: 42px;
        width: 124px;
        font-size: 16px;
        margin-left: 16px;
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
    .col_md_input{
        padding: 0 10px;
    }
    .btn_background{
        background:#0070FF;
        color: #fff;
    }
</style>
<section class="Management_container" ng-app="myApp" ng-controller="myAssetsManagement" ng-cloak>
    <div class="Management_main">
        <div class="Management_main_top">
            <div class="Management_main_top_box">
                <!-- 网站资产 -->
                <span class="input_box_item" ng-if="domain_search_if">
                    <input class="input_box" placeholder="输入资产名称" ng-model="search.domain_asset_name"
                        ng-focus="get_domain_name_focus()" ng-blur="get_domain_name_blur()"
                        ng-keyup="myKeyup_domain_name(search.domain_asset_name)" type="text">
                    <ul class="container_ul" ng-show="domain_name_list_if">
                        <li ng-repeat="item in domain_name_list" class="li_hover"
                            ng-click="domain_name_list_click(item.asset_name)">
                            {{item.asset_name}}
                        </li>
                    </ul>
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                </span>
                <span class="input_box_item" ng-if="domain_search_if">
                    <input class="input_box" type="text" placeholder="输入资产分组名称" ng-model="search.domain_group_name"
                        ng-focus="get_group_name_focus()" ng-keyup="myKeyup_domain_group(search.domain_group_name)">
                    <ul class="container_ul" ng-show="domain_group_list_if">
                        <li ng-repeat="item in domain_group_list" class="li_hover"
                            ng-click="group_name_list_click(item.group_name)">
                            {{item.group_name}}
                        </li>
                    </ul>
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                </span>
                <!-- 主机资产 -->
                <span class="input_box_item" ng-if="host_search_if">
                    <input class="input_box" placeholder="输入资产名称"
                    ng-model="search.host_asset_name" ng-focus="get_host_name_focus()" ng-blur="get_host_name_blur()"
                    ng-keyup="myKeyup_host_name(search.host_asset_name)" type="text">

                 <ul class="container_ul" ng-show="host_name_list_if">
                    <li ng-repeat="item in host_name_list" class="li_hover"
                        ng-click="host_name_list_click(item.asset_name)">
                        {{item.asset_name}}
                    </li>
                </ul>
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                </span>
                <span class="input_box_item" ng-if="host_search_if">
                    <input class="input_box" type="text" placeholder="输入资产分组名称"
                    ng-model="search.host_group_name" ng-focus="get_host_group_focus()"
                    ng-keyup="myKeyup_host_group(search.host_group_name)">
                     <ul class="container_ul" ng-show="host_group_list_if">
                    <li ng-repeat="item in host_group_list" class="li_hover"
                        ng-click="host_group_list_click(item.group_name)">
                        {{item.group_name}}
                    </li>
                </ul>
                    <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                </span>
                <button class="btn_search" ng-click="search_res()">搜索</button>
                <span style="float: right; margin-right: 72px;">
                    <button class="btn_add" ng-click="add()">添加资产</button>
                    <button class="btn_add" ng-click="batch_import()">批量导出/导入</button>
                    <button class="btn_add" ng-click="sync_risk()">同步资产</button>
                </span>
            </div>
        </div>
        <div ng-click="table_click()">
            <ul class="nav nav-tabs detail_bom_nav">
                <li role="presentation" class="active" ng-click="tab_active(1)">
                    <a href="#info" data-toggle="tab">网站资产（ <span>{{domain_data.count}}</span>）</a>
                </li>
                <li role="presentation"><a href="#loophole" data-toggle="tab" ng-click="tab_active(2)">主机资产（ <span>{{host_data.count}}</span> ）</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="info" class="tab-pane active">
                    <table class="table ng-cloak domain_table">
                        <tr>
                            <th>资产名称</th>
                            <th style="min-width: 80px;">资产分组</th>
                            <th style="min-width: 80px;">资产状态</th>
                            <th style="min-width: 80px;">关联域名</th>
                            <th style="min-width: 80px;">导入方式</th>
                            <th style="width: 150px;">导入时间</th>
                            <th style="min-width: 80px;">地理位置</th>
                            <th style="min-width: 80px;">操作</th>
                        </tr>
                        <!-- <tr style="cursor: pointer;" ng-repeat="item in loophole.data"> -->
                        <tr style="cursor: pointer;" ng-repeat="item in domain_data.data">
                            <td title={{item.asset_name}}>{{item.asset_name}}</td>
                            <td title={{item.group_name}}>{{item.group_name}}</td>
                            <td title={{item.status_code}}>{{item.status_code}}</td>
                            <td title={{item.domain}}>{{item.domain}}</td>
                            <td>{{item.in_type == 'api'?'自动导入':'手动导入'}}</td>
                            <td title={{item.addtime}}>{{item.addtime}}</td>
                            <td title={{item.location}}>{{item.location}}</td>
                            <td class="cursor">&nbsp;&nbsp;
                                <button class="btn btn-xs btn-default" ng-disabled="item.in_type == 'api'"
                                    ng-click="del_domain(item.asset_name)" data-toggle="tooltip" title="删除资产">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                                <button class="btn btn-xs btn-default" ng-disabled="item.in_type == 'api'"
                                    ng-click="edit_domain(item)" data-toggle="tooltip" title="编辑资产">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                    <div style="border-top: 1px solid #f4f4f4;padding: 20px;">
                        <em style="font-size: 14px;color: #BBBBBB;">共有<span ng-bind="domain_data.count"></span>条资产</em>
                        <!-- angularjs分页 -->
                        <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.pageNow-1)"
                                    ng-if="domain_data.pageNow>1">上一页</a></li>
                            <li><a href="javascript:void(0);" ng-click="domain_get(1)"
                                    ng-if="domain_data.pageNow>1">1</a></li>
                            <li><a href="javascript:void(0);" ng-if="domain_data.pageNow>4">...</a></li>
                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.pageNow-2)"
                                    ng-bind="domain_data.pageNow-2" ng-if="domain_data.pageNow>3"></a></li>
                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.pageNow-1)"
                                    ng-bind="domain_data.pageNow-1" ng-if="domain_data.pageNow>2"></a></li>
                            <li class="active"><a href="javascript:void(0);" ng-bind="domain_data.pageNow"></a>
                            </li>
                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.pageNow+1)"
                                    ng-bind="domain_data.pageNow+1"
                                    ng-if="domain_data.pageNow<domain_data.maxPage-1"></a></li>
                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.pageNow+2)"
                                    ng-bind="domain_data.pageNow+2"
                                    ng-if="domain_data.pageNow<domain_data.maxPage-2"></a></li>
                            <li><a href="javascript:void(0);" ng-if="domain_data.pageNow<domain_data.maxPage-3">...</a>
                            </li>

                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.maxPage)"
                                    ng-bind="domain_data.maxPage" ng-if="domain_data.pageNow<domain_data.maxPage"></a>
                            </li>
                            <li><a href="javascript:void(0);" ng-click="domain_get(domain_data.pageNow+1)"
                                    ng-if="domain_data.pageNow<domain_data.maxPage">下一页</a></li>
                        </ul>
                    </div>
                </div>
                <div id="loophole" class="tab-pane">
                    <table class="table ng-cloak domain_table">
                        <tr>
                            <th style="min-width: 80px;">主机地址</th>
                            <th style="min-width: 80px;">资产分组</th>
                            <th style="min-width: 80px;">操作系统</th>
                            <th style="min-width: 80px;">主机状态</th>
                            <th style="min-width: 80px;">关联域名</th>
                            <th style="min-width: 80px;">导入方式</th>
                            <th style="width: 150px;">导入时间</th>
                            <th style="min-width: 80px;">地理位置</th>
                            <th style="min-width: 80px;">操作</th>
                        </tr>
                        <tr style="cursor: pointer;" ng-repeat="item in host_data.data">
                            <td title={{item.asset_name}}>{{item.asset_name}}</td>
                            <td title={{item.group_name}}>{{item.group_name}}</td>
                            <td title={{item.os}}>{{item.os}}</td>
                            <td title={{item.is_alive}}>{{item.is_alive}}</td>
                            <td title={{item.domain}}>{{item.domain}}</td>
                            <td>{{item.in_type == 'api'?'自动导入':'手动导入'}}</td>
                            <td title={{item.addtime}}>{{item.addtime}}</td>
                            <td title={{item.location}}>{{item.location}}</td>
                            <td class="cursor">&nbsp;&nbsp;
                                <button class="btn btn-xs btn-default" ng-disabled="item.in_type == 'api'"
                                    ng-click="del_host(item.asset_name)" data-toggle="tooltip" title="删除资产">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                                <button class="btn btn-xs btn-default" ng-disabled="item.in_type == 'api'"
                                    ng-click="edit_host(item)" data-toggle="tooltip" title="编辑资产">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                    <div style="border-top: 1px solid #f4f4f4;padding: 20px;">
                        <em style="font-size: 14px;color: #BBBBBB;">共有<span ng-bind="host_data.count"></span>条资产</em>
                        <!-- angularjs分页 -->
                        <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.pageNow-1)"
                                    ng-if="host_data.pageNow>1">上一页</a></li>
                            <li><a href="javascript:void(0);" ng-click="host_get(1)" ng-if="host_data.pageNow>1">1</a>
                            </li>
                            <li><a href="javascript:void(0);" ng-if="host_data.pageNow>4">...</a></li>
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.pageNow-2)"
                                    ng-bind="host_data.pageNow-2" ng-if="host_data.pageNow>3"></a></li>
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.pageNow-1)"
                                    ng-bind="host_data.pageNow-1" ng-if="host_data.pageNow>2"></a></li>
                            <li class="active"><a href="javascript:void(0);" ng-bind="host_data.pageNow"></a>
                            </li>
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.pageNow+1)"
                                    ng-bind="host_data.pageNow+1" ng-if="host_data.pageNow<host_data.maxPage-1"></a>
                            </li>
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.pageNow+2)"
                                    ng-bind="host_data.pageNow+2" ng-if="host_data.pageNow<host_data.maxPage-2"></a>
                            </li>
                            <li><a href="javascript:void(0);" ng-if="host_data.pageNow<host_data.maxPage-3">...</a></li>
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.maxPage)"
                                    ng-bind="host_data.maxPage" ng-if="host_data.pageNow<host_data.maxPage"></a>
                            </li>
                            <li><a href="javascript:void(0);" ng-click="host_get(host_data.pageNow+1)"
                                    ng-if="host_data.pageNow<host_data.maxPage">下一页</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 弹窗添加资产 -->
    <div style="display: none;" id="hideenBox">
        <div id="add_risk">
            <form>
                <div class="box-body ">
                    <div>
                        <ul class="nav nav-tabs detail_bom_nav" style="margin-bottom:-1px;">
                            <li class="active">
                                <a href="#domain_add" data-toggle="tab" ng-click="domain_add_acttiv()"
                                    aria-expanded="true">网站资产</a>
                            </li>
                            <li><a href="#host_add" data-toggle="tab" ng-click="host_add_acttiv()"
                                    aria-expanded="true">主机资产</a></li>
                        </ul>
                        <div class="tab-content" style="margin-top:10px;">
                            <div id="domain_add" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-4 col_md_input">
                                        <label>资产名称</label>
                                        <input class="form-control" ng-model="domain_add.asset_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-4 col_md_input">
                                        <label>资产分组</label>
                                        <input class="form-control" ng-model="domain_add.group_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-4 col_md_input">
                                        <label>关联域名</label>
                                        <input class="form-control" ng-model="domain_add.domain"
                                            style="border-radius: 3px">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col_md_input">
                                        <label>资产状态</label>
                                        <select class="form-control input_radius" style="background-color: #fff;"
                                            ng-model="domain_add.status_code"
                                            ng-options="x.num as x.type for x in domain_statusData"></select>
                                    </div>

                                    <div class="col-md-4 col_md_input">
                                        <label>地理位置</label>
                                        <input class="form-control" ng-model="domain_add.location"
                                            style="border-radius: 3px">
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <label>责任人</label>
                                        <input class="form-control" ng-model="domain_add.staff_name"
                                            style="border-radius: 3px">
                                    </div> -->
                                </div>
                            </div>
                            <div id="host_add" class="tab-pane ">
                                <div class="row">
                                    <!-- <div class="col-md-3">
                                        <label>主机名称</label>
                                        <input class="form-control" ng-model="host_add.host_name"
                                            style="border-radius: 3px">
                                    </div> -->
                                    <div class="col-md-4 col_md_input">
                                        <label>主机地址</label>
                                        <input class="form-control" ng-model="host_add.asset_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-4 col_md_input">
                                        <label>资产分组</label>
                                        <input class="form-control" ng-model="host_add.group_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-4 col_md_input">
                                        <label>操作系统</label>
                                        <input class="form-control" ng-model="host_add.os" style="border-radius: 3px">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col_md_input">
                                        <label>关联域名</label>
                                        <input class="form-control" ng-model="host_add.domain"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-4 col_md_input">
                                        <label>地理位置</label>
                                        <input class="form-control" ng-model="host_add.location"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-4 col_md_input">
                                        <label>主机状态</label>
                                        <select class="form-control input_radius" style="background-color: #fff;"
                                            ng-model="host_add.is_alive"
                                            ng-options="x.num as x.type for x in host_statusData"></select>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <label>责任人</label>
                                        <input class="form-control" ng-model="host_add.staff_name"
                                            style="border-radius: 3px">
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- 修改网站资产 -->
    <div style="display: none;" id="hideenBox_domain_edit">
        <div id="domain_edit">
            <form>
                <div class="box-body ">
                    <div>
                        <ul class="nav nav-tabs detail_bom_nav" style="margin-bottom:-1px;">
                            <li class="active">
                                <a href="#domain_edit_li" data-toggle="tab" aria-expanded="true">网站资产</a>
                            </li>
                        </ul>
                        <div class="tab-content" style="margin-top:10px;">
                            <div id="domain_edit_li" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-3 col_md_input">
                                        <label>资产名称</label>
                                        <input class="form-control" ng-model="domain_edit_data.asset_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>资产分组</label>
                                        <input class="form-control" ng-model="domain_edit_data.group_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>资产状态</label>
                                        <select class="form-control input_radius" style="background-color: #fff;"
                                            ng-model="domain_edit_data.status_code"
                                            ng-options="x.num as x.type for x in domain_statusData"></select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col_md_input">
                                        <label>关联域名</label>
                                        <input class="form-control" ng-model="domain_edit_data.domain"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>地理位置</label>
                                        <input class="form-control" ng-model="domain_edit_data.location"
                                            style="border-radius: 3px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- 修改主机资产 -->
    <div style="display: none;" id="hideenBox_host_edit">
        <div id="host_edit">
            <form>
                <div class="box-body ">
                    <div>
                        <ul class="nav nav-tabs detail_bom_nav" style="margin-bottom:-1px;">
                            <li class="active">
                                <a href="#host_edit_li" data-toggle="tab" aria-expanded="true">主机资产</a>
                            </li>
                        </ul>
                        <div class="tab-content" style="margin-top:10px;">
                            <div id="host_edit_li" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-3 col_md_input">
                                        <label>主机地址</label>
                                        <input class="form-control" ng-model="host_edit_data.asset_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>资产分组</label>
                                        <input class="form-control" ng-model="host_edit_data.group_name"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>操作系统</label>
                                        <input class="form-control" ng-model="host_edit_data.os"
                                            style="border-radius: 3px">
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>关联域名</label>
                                        <input class="form-control" ng-model="host_edit_data.domain"
                                            style="border-radius: 3px">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col_md_input">
                                        <label>主机状态</label>
                                        <select class="form-control input_radius" style="background-color: #fff;"
                                            ng-model="host_edit_data.is_alive"
                                            ng-options="x.num as x.type for x in host_statusData"></select>
                                    </div>
                                    <div class="col-md-3 col_md_input">
                                        <label>地理位置</label>
                                        <input class="form-control" ng-model="host_edit_data.location"
                                            style="border-radius: 3px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- 批量导入导出 -->
    <div style="display: none;" id="batch_import_hideenBox">
        <div id="batch_import">
            <form>
                <div class="box-body ">
                    <div class="row">
                        <div class="col-md-2">
                            <span>资产导入</span>
                        </div>
                        <div class="col-md-3">
                            <button class=" btn btn_style btn_background" style="max-width: 120px;"
                                ng-click="import_host_risk()">上传主机资产</button>
                        </div>
                        <div class="col-md-3">
                            <button class=" btn  btn_style btn_background" style="max-width: 120px;"
                                ng-click="import_website_risk()">上传网站资产</button>
                        </div>
                        <div class="col-md-3">
                            <button class=" btn  btn_style btn_background" style="max-width: 120px;"
                                ng-click="download_template()">模板下载</button>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-2">
                            <span>资产导出</span>
                        </div>
                        <div class="col-md-3">
                            <input type="radio" ng-value=true ng-model="export_risk_data" name="selectState"
                                ng-checked="true">
                            <span>主机资产</span>
                        </div>
                        <div class="col-md-3">
                            <input type="radio" ng-value=false ng-model="export_risk_data" name="selectState">
                            <span>网站资产</span>
                        </div>
                        <div class="col-md-2">
                            <button class=" btn btn_style btn_background" style="max-width: 120px;"
                                ng-click="export_risk()">导出</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="input-file" style="display: none">
        <form id="upload" method="post" enctype="multipart/form-data">
            <input type="text" id="avatval" placeholder="请选择文件···" readonly="readonly"
                style="vertical-align: middle;" />
            <input type="file" name="file" id="avatar" />
            <button ng-click="upload()" class="btn btn-primary upload_button" id="avatsel1">上传文件</button>
        </form>
    </div>

    <div class="input-file" style="display: none">
        <form id="upload_app" method="post" enctype="multipart/form-data">
            <input type="text" id="avatval_app" placeholder="请选择文件···" readonly="readonly"
                style="vertical-align: middle;" />
            <input type="file" name="file" id="avatar_app" />
            <button ng-click="upload_app()" class="btn btn-primary upload_button" id="avatsel1">上传文件</button>
        </form>
    </div>
    </div>
</section>
<script src="/js/controllers/asset_management.js"></script>
