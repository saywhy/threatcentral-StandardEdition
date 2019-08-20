<?php
$this->title = '漏洞预警详情';
?>
<link rel="stylesheet" href="/css/loop_detail.css">
<section class="loop_detail_content" ng-app="myApp" ng-controller="AlertLoopholeDetailCtrl" ng-cloak>
    <div class="loop_detail_top">
        <div class="loop_detail_top_title">
            <p class="loop_detail_top_title_p">
                <img src="/images/alert/h.png" ng-if="detail_info_detail.degree == '高'" alt="">
                <img src="/images/alert/m.png" ng-if="detail_info_detail.degree == '中'" alt="">
                <img src="/images/alert/l.png" ng-if="detail_info_detail.degree == '低'" alt="">
                <span title="{{detail_info_detail.title}}">{{detail_info_detail.title}}</span>
            </p>
        </div>
        <div class="loop_detail_top_info">
            <div class="row">
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_1.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">风险类型：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info_detail.category}}">{{detail_info_detail.category}}</span>
                </div>
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_2.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">资产地址：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info.location}}">{{detail_info.location}}</span>
                </div>
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_3.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">资产分组：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info.title}}">{{detail_info.company}}</span>
                </div>
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_4.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">漏洞状态：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info.risk_status_cn}}">{{detail_info.risk_status_cn}}</span>
                </div>
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_5.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">关联域名：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info.risk_addr}}">{{detail_info.risk_addr}}</span>
                </div>
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_6.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">首次发现时间：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info_detail.first_seen | date : 'yyyy-MM-dd HH:mm'}}">
                        {{detail_info_detail.first_seen | date : 'yyyy-MM-dd HH:mm'}}</span>
                </div>
                <div class="col-md-3 loop_detail_top_info_item">
                    <img src="/images/loophole/loop_7.png" class="img_box" alt="">
                    <span class="loop_detail_top_info_item_title">最后更新时间：</span>
                    <span class="loop_detail_top_info_item_value"
                        title="{{detail_info_detail.last_seen | date : 'yyyy-MM-dd HH:mm'}}">
                        {{detail_info_detail.last_seen | date : 'yyyy-MM-dd HH:mm'}}</span>
                </div>
            </div>


        </div>
    </div>

    <div class="loop_detail_bom">
        <div class="loop_detail_top_title">
            <p class="loop_detail_top_title_p">
                <span>漏洞情报详情</span>
            </p>
        </div>
        <div class="loop_detail_bom_info">
            <div ng-bind-html='detail_info_detail.content'></div>
        </div>
    </div>

</section>
<script src="/js/controllers/alert_loophole_detail.js"></script>
