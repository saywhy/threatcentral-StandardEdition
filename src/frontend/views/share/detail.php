<?php
/* @var $this yii\web\View */

$this->title = '共享详情';
?>
<link rel="stylesheet" href="/css/share/share_detail.css">
<section class="share_detail_box" ng-app="myApp" ng-controller="shareDeatilCtrl" ng-cloak>
    <div class="share_detail_container">
        <div class="share_detail_container_top">
            <span class="share_detail_container_top_title">{{share_detail.name}}</span>
        </div>
        <div class="share_detail_container_mid">
            <div class="share_detail_container_mid_title">
                <img src="/images/share/miaoshu.png" alt="">
                <span>行为描述</span>
            </div>
            <p class="share_detail_container_mid_des">{{share_detail.describe}}</p>
        </div>
        <div class="share_detail_container_bom">
            <div class="share_detail_container_bom_title">
                <img src="/images/share/zhibiao.png" alt="">
                <span>相关指标</span>
                <img  src="/images/share/down_load.png" class="download_box_icon" ng-click="down_load()" alt="">
                <img src="/images/share/up.png" ng-click="describe_if('up')" ng-if="describe" class="describe_icon"
                    alt="">
                <img src="/images/share/down.png" ng-click="describe_if('down')" ng-if="!describe" class="describe_icon"
                    alt="">

            </div>
            <div class="table_box" >
                <table class="table  table-striped ng-cloak" ng-if="describe">
                    <tr style="text-algin:center" class="alert_table_tr">
                        <th style="width:80px;">序号</th>
                        <th>指标值</th>
                        <th>指标类型</th>
                        <th style="width:100px;">威胁度</th>
                        <th style="width:100px;">置信度</th>
                    </tr>
                    <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in share_detail.data">
                        <td>{{$index+1}}</td>
                        <td title="{{item.indicators}}">
                            {{item.indicators}}
                        </td>
                        <td title="{{item.type}}">{{item.type}}</td>
                        <td>
                            {{item.threat}}
                        </td>
                        <td>
                            {{item.confidence}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    var share_old = <?=json_encode($share)?> ;
    var share = <?=json_encode($share)?> ;
</script>
<script src="/js/controllers/share-detail.js"></script>