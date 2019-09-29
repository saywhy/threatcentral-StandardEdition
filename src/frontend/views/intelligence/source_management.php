<?php
/* @var $this yii\web\View */

$this->title = '情报源管理';
?>
<link rel="stylesheet" href="/css/common.css">
<style>
    .manage_container {
        padding: 0 38px;
    }

    .item_box {
        height: 208px;
        padding: 0 10px;
        margin-bottom: 20px;
    }

    .item {
        background: #FFFFFF;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.16);
        border-radius: 8px;
        height: 208px;
        position: relative;
        padding: 15px 10px 10px 24px;
    }

    .img_bg {
        position: absolute;
        bottom: 5px;
        right: 5px;
        height: 136px;
        width: 136px;
        z-index: 9;
    }

    .item_info {
        position: relative;
        z-index: 99;

    }

    .item_info_title {
        height: 56px;
    }

    .item_info_title_num {
        font-size: 48px;

        line-height: 56px;
    }

    .green {
        color: #7ACE4C;
    }

    .blue_light {
        color: #12DCFF;

    }

    .orange {
        color: #FEAA00;
    }

    .blue {
        color: #0070FF;
    }

    .item_info_title_name {
        font-size: 14px;
        color: #333333;
        line-height: 18px;
        margin: 6px 0;
    }

    .item_info_title_value {
        font-size: 14px;
        color: #666666;
        line-height: 18px;
        margin-bottom: 25px;
    }

    .item_info_title_time,
    .item_info_title_res {
        margin-bottom: 5px;
        font-size: 14px;
        color: #999999;
        line-height: 18px;
    }

    .select_container {
        padding: 10px 48px;
    }

    .alert_search_input {
        border: 1px solid #ECECEC;
        border-radius: 4px;
        height: 42px;
        width: 210px;
        padding-left: 34px;
        margin-right: 16px;
    }

    .item_info_top {
        height: 52px;
    }

    .item_info_top_p {
        float: left;
    }

    .switch_box {
        float: right;

    }

    .tgl-ios:checked+.tgl-btn {
        background: #0070FF;
    }

    .tgl-ios+.tgl-btn {
        width: 30px;
        height: 18px;
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

    .model_btn_box {
        text-align: center;
        margin-top: 12px;
    }

    .input_style {
        width: 100px;
        border: 1px solid #999;
        display: inline-block;
        border-radius: 3px;
    }

    .input_style:focus {
        border-color: #0070ff;
    }
</style>
<!-- Main content -->
<section ng-app="myApp" ng-controller="PrototypeCtrl" ng-cloak>
    <div class="select_container">
        <select class="alert_search_input" style="background-color: #fff;" ng-model="select.model"
            ng-options="x.num as x.type for x in select_model"></select>
    </div>
    <div class="manage_container">
        <div ng-if="select.model=='1'">
            <div class="row">
                <div class="col-md-3 item_box" ng-repeat="item in source_data.red track by $index"
                    ng-click="detail(item)">
                    <div class="item">
                        <img class="img_bg" src="/images/agent/hoohoolab.png" alt="">
                        <div class="item_info">
                            <div class="item_info_top">
                                <p class="item_info_title item_info_top_p">
                                    <span class="item_info_title_num"
                                        ng-class="{'green':($index+1)%2==0,'blue_light':($index+1)%2==1,'orange':($index+1)%4==0,'blue':($index+1)%4==1 }">{{item.confidence}}</span>
                                </p>
                                <div class="switch_box" style="height: 52px;line-height: 52px;">
                                    <input class="tgl tgl-ios" type="checkbox" id="{{item.name}}"
                                        ng-checked="item.choose" ng-click="choose_open(item)">
                                    <label class="tgl-btn" for="{{item.name}}"
                                        style="margin-top: 16px;margin-right: 10px; float: left;"></label>
                                    <span style="float:left;" ng-if="item.choose">启用</span>
                                    <span style="float:left;" ng-if="!item.choose">禁用</span>
                                </div>
                            </div>
                            <p class="item_info_title_name">{{item.key}}</p>
                            <p class="item_info_title_value">{{item.name}}</p>
                            <p class="item_info_title_time">
                                <span> 上次更新时间：</span>
                                <span> {{item.last_run |date:'yyyy/MM/dd hh:mm:ss'}}</span>
                            </p>
                            <p class="item_info_title_res">
                                <span> 结果：</span>
                                <span> {{item.sub_state_cn}}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div ng-if="select.model=='2'">
            <div class="row">
                <div class="col-md-3 item_box" ng-repeat="item in source_data.green" ng-click="detail(item)">
                    <div class="item">
                        <img class="img_bg" src="/images/agent/hoohoolab.png" alt="">
                        <div class="item_info">
                            <div class="item_info_top">
                                <p class="item_info_title item_info_top_p">
                                    <span class="item_info_title_num"
                                        ng-class="{'green':($index+1)%2==0,'blue_light':($index+1)%2==1,'orange':($index+1)%4==0,'blue':($index+1)%4==1 }">{{item.confidence}}</span>
                                </p>
                                <div class="switch_box" style="height: 52px;line-height: 52px;"
                                ng-click="aaa();$event.stopPropagation();">
                                    <input class="tgl tgl-ios" type="checkbox" value="item.choose"
                                        ng-checked="item.choose" ng-click="choose_open(item);$event.stopPropagation();"
                                        id="{{item.name}}">
                                    <label class="tgl-btn" for="{{item.name}}"
                                        style="margin-top: 16px;margin-right: 10px; float: left;"></label>
                                    <span style="float:left;" ng-if="item.choose">启用</span>
                                    <span style="float:left;" ng-if="!item.choose">禁用</span>
                                </div>
                            </div>
                            <p class="item_info_title_name">{{item.key}}</p>
                            <p class="item_info_title_value">{{item.name}}</p>
                            <p class="item_info_title_time">
                                <span> 上次更新时间：</span>
                                <span> {{item.last_run |date:'yyyy/MM/dd hh:mm:ss'}}</span>
                            </p>
                            <p class="item_info_title_res">
                                <span> 结果：</span>
                                <span> {{item.sub_state_cn}}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <input type="file" style="display: none;" id='inputFile_cert'>
    <input type="file" style="display: none;" id='inputFile_pkey'>
    <!-- 详情弹窗 -->
    <div style="display: none;" id="detail_hideenBox">
        <div id="detail_info">
            <div class="box-body" style="padding:0;">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <ul class="nav nav-stacked sensor-detail">
                            <li>
                                <span class="sensor-detail-title">情报名称</span>
                                <span ng-bind="detail_info.name"></span>
                            </li>
                            <li>
                                <span class="sensor-detail-title">信心指数</span>
                                <span class="value">
                                    <input class="form-control input-sm" type="number" max="100" min="0"
                                        ng-model="detail_info.confidence">
                                </span>
                            </li>
                            <li>
                                <span class="sensor-detail-title">更新周期</span>
                                <span ng-if="detail_info.config.interval != null">
                                    <input class="form-control input-sm input_style" type="number" min="0"
                                        ng-model="detail_info.config.interval ">
                                    (秒)
                                </span>
                            </li>
                            <li>
                                <span class="sensor-detail-title">私钥</span>
                                <span>
                                    <label class="btn btn-sm btn-default" for="inputFile_pkey">
                                        <i class="fa fa-upload"></i>
                                        <span>替换私钥</span>
                                    </label>
                                </span>
                            </li>
                            <li>
                                <span class="sensor-detail-title" style="width: 140px;">最后一次更新成功时间:</span>
                                <span>{{detail_info.last_run |date:'yyyy/MM/dd hh:mm:ss'}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 ">
                        <ul class="nav nav-stacked sensor-detail">
                            <li>
                                <span class="sensor-detail-title">情报类型</span>
                                <span ng-bind="detail_info.config.attributes.type"></span>
                            </li>
                            <li>
                                <span class="sensor-detail-title">威胁程度</span>
                                <span class="value" ng-if="detail_info.config.attributes.threat != null">
                                    <input class="form-control input-sm" type="number" max="5" min="0"
                                        ng-model="detail_info.config.attributes.threat">
                                </span>
                            </li>
                            <li>
                                <span class="sensor-detail-title">情报状态</span>
                                <span>
                                    <input class="tgl tgl-ios" id="detail_status" type="checkbox"
                                        ng-checked="detail_info.choose"
                                        ng-click="$event.stopPropagation();changeStatus(nowPrototype);">
                                    <label class="tgl-btn" for="detail_status"></label>
                                </span>
                            </li>
                            <li>
                            </li>
                            <li>
                                <span class="sensor-detail-title">证书</span>
                                <span>
                                    <label class="btn btn-sm btn-default" for="inputFile_cert">
                                        <i class="fa fa-upload"></i>
                                        <span>替换证书</span>
                                    </label>
                                </span>
                            </li>
                            <li>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="model_btn_box">
                    <button class="model_btn_save" ng-click="detail_save()">保存</button>
                    <button class="model_btn_cel" ng-click="detail_cancel()">取消</button>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript" src="/js/controllers/prototype.js"></script>
