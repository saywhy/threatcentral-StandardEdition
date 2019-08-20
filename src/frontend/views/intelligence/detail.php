<?php
/* @var $this yii\web\View */

$this->title = '漏洞情报详情';
?>
<link rel="stylesheet" href="/css/intelligence/detail.css">
<section class="intelligence_content" ng-app="myApp" ng-controller="IntelligenceDetailCtrl" ng-cloak>
    <div class="detail_box">
        <div class="detail_box_top">
             <img src="/images/alert/h.png" ng-if="detail.degree == '高'" class="img_icon" alt="">
                <img src="/images/alert/m.png" ng-if="detail.degree == '中'" class="img_icon"  alt="">
                <img src="/images/alert/l.png" ng-if="detail.degree == '低'" class="img_icon"  alt="">
            <span style="vertical-align: middle;">{{detail.title}}</span>
        </div>
        <div class="detail_box_bom">
              <div ng-bind-html='html_content'> </div>
        </div>
    </div>
</section>
<script src="/js/controllers/intelligence_detail.js"></script>
