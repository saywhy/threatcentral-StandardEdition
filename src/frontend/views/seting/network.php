<?php
/* @var $this yii\web\View */

$this->title = '网络配置';
?>

<link rel="stylesheet" href="/css/set/network.css">
<!-- Main content -->
<section class="net_container" ng-app="myApp" ng-cloak ng-controller="netCtrl">
    <div class="net_container_box">
        <div style="overflow: auto;">
            <div class="net_set_title">网络设置</div>
            <div class="net_set_right">
                <select class="select_box" ng-model="select_net_name"
                    ng-options="x.num as x.type for x in select_model" ng-change="select_net_name_change(select_net_name)"></select>
                <div class="net_set_item_box">
                    <span class="net_set_item_title">状态：</span>
                    <span ng-click="net_choose('open')" ng-disabled="network_type.disabled" class="choose_box">
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="set_true&&!network_type.disabled" alt="">
                        <img src="/images/set/disable_true.png" class="img_choose_icon" ng-if="set_true&&network_type.disabled" alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!set_true" alt="">
                        <span>启用</span>
                    </span>
                    <span ng-click="net_choose('closed')" class="choose_box"  ng-disabled="network_type.disabled" >
                        <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!set_true&&!network_type.disabled" alt="">
                        <img src="/images/set/disable_true.png" class="img_choose_icon" ng-if="!set_true&&network_type.disabled" alt="">
                        <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="set_true" alt="">
                        <span>停用</span>
                    </span>
                </div>
                <div class="net_set_item_box">
                    <span class="net_set_item_title">获取IP方式：</span>
                    <select class="select_box select_net_set" ng-class="network_type.disabled?'disabled_input':''" ng-disabled="network_type.disabled"  ng-model="select_gain_type"
                        ng-options="x.num as x.type for x in gain_type"></select>
                </div>
                <div class="net_set_item_box" ng-if="select_gain_type=='static'">
                    <span class="net_set_item_title">IP地址：</span>
                    <input class="input_box_net" type="text" ng-model="network.IPADDR"
                    ng-class="network_type.disabled?'disabled_input':''"  ng-disabled="network_type.disabled" >
                    <span class="net_set_item_title net_set_item_right">子网掩码：</span>
                    <input class="input_box_net" type="text" ng-model="network.NETMASK"
                    ng-class="network_type.disabled?'disabled_input':''" ng-disabled="network_type.disabled" >
                </div>
                <div class="net_set_item_box" ng-if="select_gain_type=='static'">
                    <span class="net_set_item_title">默认网关：</span>
                    <input type="text" class="input_box_net" ng-model="network.GATEWAY"
                    ng-class="network_type.disabled?'disabled_input':''" ng-disabled="network_type.disabled" >
                       <span class="net_set_item_title net_set_item_right">首选DNS：</span>
                    <input class="input_box_net" type="text" ng-model="network.DNS1"
                    ng-class="network_type.disabled?'disabled_input':''" ng-disabled="network_type.disabled" >
                </div>
                <div class="net_set_item_box" ng-if="select_gain_type=='static'">
                    <span class="net_set_item_title">备用DNS：</span>
                    <input type="text" class="input_box_net" ng-model="network.DNS2"
                    ng-class="network_type.disabled?'disabled_input':''" ng-disabled="network_type.disabled" >
                </div>
            </div>
        </div>
           <div>
            <button class="net_save" ng-if="network_type.disabled" ng-click="edit_network()">修改</button>
            <button class="net_save" ng-if="!network_type.disabled" ng-click="save_network()">保存</button>
            <button class="net_cancel" ng-if="!network_type.disabled" ng-click="cancel_network()">取消</button>
        </div>
        <div>
            <div class="net_http_title">代理服务器</div>
            <div class="net_set_item_box net_http_item">
                     <span class="net_set_item_title">HTTP:</span>
                    <input class="input_box_net" type="text" ng-model="proxy_list.HTTP_PROXY">
            </div>
            <div class="net_set_item_box net_http_item">
                     <span class="net_set_item_title">HTTPS:</span>
                    <input class="input_box_net" type="text" ng-model="proxy_list.HTTPS_PROXY">
            </div>
        </div>
        <div>
            <button class="net_save" ng-click="save_proxy()">保存</button>
        </div>
    </div>
</section>
<script src="/js/controllers/network.js"></script>
