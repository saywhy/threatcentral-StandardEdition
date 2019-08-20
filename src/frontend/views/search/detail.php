<?php
/* @var $this yii\web\View */
$this->title = 'APT详情';
?>
<link rel="stylesheet" href="/css/common.css">
<section class="apt_detail_content" ng-app="myApp" ng-controller="aptDetailCtrl" ng-cloak>

    <div class="apt_detail_box_top">
        <div class="apt_detail_box_top_left">
            <img ng-src="{{card_detail_data.raw_picture_11}}" class="img_bg" alt="">
        </div>
        <div class="apt_detail_box_top_right">
            <p class="apt_detail_box_top_right_p p_title">
                <span>名称：</span>
                <span>{{card_detail_data.name}}</span>
            </p>
            <p class="apt_detail_box_top_right_p">
                <span>攻击来源：</span>
                <span>{{card_detail_data.attack_source}}</span>
            </p>
            <p class="apt_detail_box_top_right_p">
                <span>最近观察到的攻击时间：</span>
                <span>{{card_detail_data.lately_attack_time}}</span>
            </p>
            <p class="apt_detail_box_top_right_p">
                <span>攻击目标：</span>
                <span>{{card_detail_data.attack_target}}</span>
            </p>
            <p class="apt_detail_box_top_right_p">
                <span>针对行业：</span>
                <span>{{card_detail_data.target_industry}}</span>
            </p>
        </div>
    </div>

    <div class="apt_detail_box_bom">
        <div class="bom_item">
            <p class="bom_item_title">
                <img src="/images/apt/title.png" class="img_icon" alt="">
                <span class="img_icon_span">概述</p>
            </p>
            <p class="bom_item_info">
                {{card_detail_data.summary}}
            </p>
        </div>

    </div>
    <div class="apt_detail_box_mid">
        <p class="bom_item_title">
            <img src="/images/apt/ttp.png" class="img_icon" alt="">
            <span class="img_icon_span">攻击战术、技术和流程 (TTPs)</p>
        </p>
        <div class="row">
            <div class="col-md-2" ng-repeat="(index,item) in all_array" ng-if="index<6">
                <p class="apt_detail_mid_title">{{item.title}}</p>
                <div class="apt_detail_mid_item" ng-repeat="k in item.card">
                    <p class="apt_detail_mid_item_p">
                        <span class="apt_detail_mid_item_subtitle">{{k.name}}</span>
                        <br>
                        <span class="apt_detail_mid_item_subvalue">{{k.value}}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col-md-2" ng-repeat="(index,item) in all_array" ng-if="5<index">
                <p class="apt_detail_mid_title">{{item.title}}</p>
                <div class="apt_detail_mid_item" ng-repeat="k in item.card">
                    <p class="apt_detail_mid_item_p">
                        <span class="apt_detail_mid_item_subtitle">{{k.name}}</span>
                        <br>
                        <span class="apt_detail_mid_item_subvalue">{{k.value}}</span>
                    </p>
                </div>
            </div>
        </div>

    </div>
    <div class="apt_detail_box_mid">
        <p class="bom_item_title">
            <img src="/images/apt/ioc.png" class="img_icon" alt="">
            <span class="img_icon_span">失陷标识 (IOCs)</p>
        </p>
        <div class="row">
            <div class="col-md-4 iocs_item">
                <p class="apt_detail_bom_title">Hashes</p>
                <div class="iocs_item_box">
                    <p ng-repeat="item in card_detail_data.hash track by $index">{{item}}</p>
                </div>
            </div>
            <div class="col-md-4 iocs_item">
                <p class="apt_detail_bom_title">C&C server Domain Names</p>
                <div class="iocs_item_box">
                    <p ng-repeat="item in card_detail_data.domain track by $index">{{item}}</p>

                </div>
            </div>
            <div class="col-md-4 iocs_item">
                <p class="apt_detail_bom_title">C&C server IP Address</p>
                <div class="iocs_item_box">
                    <p ng-repeat="item in card_detail_data.ip track by $index">{{item}}</p>
                </div>
            </div>
        </div>
    </div>
</section>
<link rel="stylesheet" href="/css/apt/apt_detail.css">
<script src="/js/controllers/aptDetail.js"></script>
