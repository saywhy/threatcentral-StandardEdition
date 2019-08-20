<?php
$this->title = '暗网详情';
?>
<style>
    .row,
    .col-md-4 {
        margin: 0;
        padding: 0;
    }

    .darknet_content {
        padding: 36px 48px;
    }

    .darknet_box_top {
        height: 116px;
        padding: 0 24px;
        background: #FFFFFF;
        border-radius: 8px;
    }

    .darknet_box_top_p {
        height: 58px;
        line-height: 58px;
        margin: 0;
    }

    .darknet_box_top_p_title {
        font-size: 16px;
        color: #649EE9;
        vertical-align: middle;
    }

    .darknet_box_top_p_content {
        margin-left: 10px;
        font-size: 16px;
        color: #333333;
        vertical-align: middle;
    }

    .darknet_box_bom {
        margin-top: 36px;
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.08);
        border-radius: 6px;
        min-height: 200px;
    }

    .darknet_box_bom_top {
        height: 68px;
        padding: 0 36px;
        line-height: 68px;
        border-bottom: 1px solid #ececec;
    }

    .darknet_box_bom_title {
        margin-left: 10px;
        font-size: 20px;
        vertical-align: middle;
        color: #333333;
        font-weight: 500;
    }

    .darknet_box_bom_bom {
        padding: 23px 36px;
    }

    .darknet_box_bom_bom_p {
        margin: 0;
        padding: 0;
        text-align: left;
        color: #666;
        font-size: 14px;
        text-indent: 2em;
        letter-spacing: 2px;
        line-height: 20px;
    }
</style>
<section ng-app="myApp" class="darknet_content" ng-controller="AlertDarknetDetailCtrl" ng-cloak>
    <div class="darknet_box_top row">
        <div class="col-md-4">
            <div class="darknet_box_top_p">
                <img src="/images/alert/dark_01.png" alt="">
                <span class="darknet_box_top_p_title">预警主题:</span>
                <span class="darknet_box_top_p_content">{{detail.theme}}</span>
            </div>
            <div class="darknet_box_top_p">
                <img src="/images/alert/top_3.png" alt="">
                <span class="darknet_box_top_p_title">情报来源:</span>
                <span class="darknet_box_top_p_content">{{detail.source}}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="darknet_box_top_p">
                <img src="/images/alert/top_4.png" alt="">
                <span class="darknet_box_top_p_title">发现时间:</span>
                <span class="darknet_box_top_p_content">{{detail.time}}</span>
            </div>
            <div class="darknet_box_top_p">
                <img src="/images/alert/dark_04.png" alt="">
                <span class="darknet_box_top_p_title">验证状态:</span>
                <span class="darknet_box_top_p_content">{{detail.verify_status=='0'?'未验证':'已验证'}}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="darknet_box_top_p">
                <img src="/images/alert/dark_05.png" alt="">
                <span class="darknet_box_top_p_title">泄漏途径:</span>
                <span class="darknet_box_top_p_content">{{detail.label}}</span>
            </div>
        </div>
    </div>
    <div class="darknet_box_bom">
        <div class="darknet_box_bom_top">
            <img src="/images/alert/dark_06.png" alt="">
            <span class="darknet_box_bom_title"> 详细描述 </span>
        </div>
        <div class="darknet_box_bom_bom">
            <p class="darknet_box_bom_bom_p">{{detail.detail}}</p>
        </div>

    </div>
</section>
<script src="/js/controllers/alert_darknet_detail.js"></script>
