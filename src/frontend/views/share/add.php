<?php
/* @var $this yii\web\View */

$this->title = '共享情报提交';
?>
<link rel="stylesheet" href="/css/share/share_add.css">

<section class="content" ng-app="myApp" ng-controller="ShareAddCtrl" ng-cloak>
    <div class="share_box_container">
        <div class="share_box_container_top">
            <p class="share_box_container_top_title">共享情报提交</p>
            <p class="share_box_container_top_subtitle">
                标题
                <span style="color:#ff5f5c">*</span>
            </p>
            <input type="text" placeholder="请输入标题" class="share_box_container_top_input" ng-model="share_parmas.name">
        </div>

        <div class="share_box_container_editor">
            <textarea ng-model="share_parmas.describe" class="ioc_box_textarea"
                placeholder="请输入描述内容" name="" id="" cols="30" rows="10"></textarea>
        </div>
        <div class="ioc_box">
            <p class="ioc_box_p">
                <img src="/images/share/down.png" ng-click="textarea_if('down')" ng-if="!share.textarea_if"
                    class="img_box" alt="">
                <img src="/images/share/up.png" ng-click="textarea_if('up')" ng-if="share.textarea_if" class="img_box"
                    alt="">
                <span class="ioc_box_title">添加IOC</span>
                <span class="ioc_out_text">对本框输入的IOC以及对PDF、DOCX、XLS、XLSX、TXT格式的附件系统会做IOC提取</span>
                <label class="ioc_box_btn" style="cursor: pointer;" for="InputFile" >上传附件</label>
                <input type="file" id="InputFile" style="display: none;width: 1px;height: 1px">
            </p>
            <textarea ng-if="share.textarea_if" ng-change="textarea_change()"  ng-model="share_parmas.textarea_ioc_info" class="ioc_box_textarea" placeholder="*添加多个ioc指标，用换行或者分号隔开,输入完毕后请点击手动IOC提取按钮进行指标提取" name="" id="" cols="30" rows="10"></textarea>
        </div>
        <div>
            <button ng-class="textarea_input?'manual_box_btn':'manual_box_btn_disabled'"
            ng-click="manual_test()" ng-disabled="!textarea_input">手动IOC提取</button>
        </div>
        <div class="tag_box">
            <p class="tag_box_p">
                <img src="/images/share/down.png" ng-click="tag_if('down')" ng-if="!share.tag_if" class="img_box"
                    alt="">
                <img src="/images/share/up.png" ng-click="tag_if('up')" ng-if="share.tag_if" class="img_box" alt="">
                <span class="tag_box_title">添加标签</span>
            </p>
            <ul ng-if="share.tag_if" class="tag_box_ul">
                <li class="tag_box_span tag_box_span_active" ng-repeat="item in share_parmas.tagNames track by $index">
                    {{item}}
                </li>
            </ul>
            <p ng-if="share.tag_if" class="tag_box_placeholed">*请至少添加一个标签</p>
            <button ng-if="share.tag_if" class="add_tag_btn" ng-click="add_tagName()">添加</button>
        </div>

        <button ng-click="send()" class="send_btn">发布</button>
    </div>
    <!-- 弹窗-->
    <div style="display: none;" id="token_box">
        <div id="token">
            <div class="table_overflow">
                <table class="table  table-striped ng-cloak">
                    <tr style="text-algin:center" class="alert_table_tr">
                        <th style="width:80px;">
                            <img src="/images/alert/select_false.png" class="cursor" ng-if="!choose_all"
                                ng-click="choose_click_all('false');" alt="">
                            <img src="/images/alert/select_true.png" class="cursor" ng-if="choose_all"
                                ng-click="choose_click_all('true');" alt="">
                        </th>
                        <th style="width:80px;">序号</th>
                        <th>指标值</th>
                        <th style="width:100px;">指标类型</th>
                        <th style="width:100px;">威胁度</th>
                        <th style="width:100px;">置信度</th>
                    </tr>
                    <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in indicatorsList">
                        <td>
                            <img src="/images/alert/select_false.png" class="cursor" ng-if="!item.choose"
                                ng-click="choose_click($index,'false');" alt="">
                            <img src="/images/alert/select_true.png" class="cursor" ng-if="item.choose"
                            ng-click="choose_click($index,'true');" alt="" >
                        </td>
                        <td>{{$index+1}}</td>
                        <td title="{{item.indicators}}">
                            {{item.indicators}}
                        </td>
                        <td title="{{item.type}}">{{item.type}}</td>
                        <td>
                            <input type="text" class="input_box_add" ng-model="item.threat">
                        </td>
                        <td>
                            <input type="text" class="input_box_add" ng-model="item.confidence">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="token_btn_box">
                <button class="token_btn_ok" ng-click="token_save()">确定</button>
                <button class="token_btn_cancel" ng-click="token_cancel()">取消</button>
            </div>
        </div>
    </div>
    <!-- 手动输入弹窗-->
    <div style="display: none;" id="manual_box">
        <div id="manual">
            <div class="table_overflow">
                <table class="table  table-striped ng-cloak">
                    <tr style="text-algin:center" class="alert_table_tr">
                        <th style="width:80px;">
                            <img src="/images/alert/select_false.png" class="cursor" ng-if="!manual_choose_all"
                                ng-click="manual_choose_click_all('false');" alt="">
                            <img src="/images/alert/select_true.png" class="cursor" ng-if="manual_choose_all"
                                ng-click="manual_choose_click_all('true');" alt="">
                        </th>
                        <th style="width:80px;">序号</th>
                        <th>指标值</th>
                        <th style="width:100px;">指标类型</th>
                        <th style="width:100px;">威胁度</th>
                        <th style="width:100px;">置信度</th>
                    </tr>
                    <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in getIndicators_data.list">
                        <td>
                            <img src="/images/alert/select_false.png" class="cursor" ng-if="!item.choose"
                                ng-click="manual_choose_click($index,'false');" alt="">
                            <img src="/images/alert/select_true.png" class="cursor" ng-if="item.choose"
                            ng-click="manual_choose_click($index,'true');" alt="" >
                        </td>
                        <td>{{$index+1}}</td>
                        <td title="{{item.indicators}}">
                            {{item.indicators}}
                        </td>
                        <td title="{{item.type}}">{{item.type}}</td>
                        <td>
                            <input type="text" class="input_box_add" ng-model="item.threat">
                        </td>
                        <td>
                            <input type="text" class="input_box_add" ng-model="item.confidence">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="token_btn_box">
                <button class="token_btn_ok" ng-click="manual_save()">确定</button>
                <button class="token_btn_cancel" ng-click="manual_cancel()">取消</button>
            </div>
        </div>
    </div>
    <!-- 添加标签-->
    <div style="display: none;" id="tagName_box">
        <div id="tagName">
            <div  style="height: 75px;">
                <p style="font-size: 12px;color: #666666;">标签名称</p>
                <input class="token_top_input" type="text" placeholder="请输入标签名称" ng-model="tagName_span">
            </div>
            <div class="token_btn_box">
                <button class="token_btn_ok" ng-click="tagName_save()">保存</button>
                <button class="token_btn_cancel" ng-click="tagName_cancel()">取消</button>
            </div>
        </div>
    </div>
</section>
<script src="/plugins/xlsx/xlsx.full.min.js"></script>
<script src="/js/controllers/share_add.js"></script>
