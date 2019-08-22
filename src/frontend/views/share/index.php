<?php
/* @var $this yii\web\View */

$this->title = '情报共享';
?>
<link rel="stylesheet" href="/css/share/share_index.css">
<section class="share_box" ng-app="myApp" ng-controller="shareCtrl" ng-cloak>
    <div class="search_box">
        <input type="text" placeholder="请输入IP、URL、域名、HASH" class="input_box" ng-model="searchWd">
        <button class="search_btn_box" ng-click="search()">
            <img src="/images/search/search.png" alt="">
        </button>
    </div>
    <div class="share_container_box">
        <div class="share_container_box_top">
            <span class="share_container_box_top_name">所有威胁情报</span>
            <button class="add_btn" ng-click="add()">
                <img src="/images/share/up_yun.png" class="img_icon" alt="">
                <span>共享情报提交</span>
            </button>
        </div>
        <div class="share_container_box_bom">
            <div class="share_container_box_bom_item" ng-repeat="item in list track by $index" ng-click="detail(item)">
                <p style="margin-bottom:12px;height:28px;">
                    <span class="share_container_box_bom_item_name">{{item.name}}</span>
                </p>
                <p class="share_container_box_bom_item_describe" title="{{item.describe}}">
                    {{item.describe}}
                </p>
                <p class="tag_box">
                    <span class="tag_item">
                        <span ng-bind="item.data==null?'0':item.data.length"></span>
                        <span>条指标</span>
                    </span>
                    <span class="tag_box_name">
                        <img src="/images/login/user.png" class="img_icon" alt="">
                        <span>{{item.username}}</span>
                    </span>
                    <span class="tag_box_time">
                        <img src="/images/share/time.png" class="img_icon" alt="">
                        <span>{{item.timeString}}</span>
                    </span>
                    <span class="tag_box_comment">
                        <img src="/images/share/del_icon.png"
                         ng-click="del(item,$index);$event.stopPropagation();" class="img_icon" alt="">
                    </span>
                    <span class="tag_box_comment">
                        <img src="/images/share/comment.png" class="img_icon" alt="">
                        <span>{{item.cq}}</span>
                    </span>
                    <span class="tag_box_look">
                        <img src="/images/share/look.png" class="img_icon" alt="">
                        <span>{{item.uv}}</span>
                    </span>

                </p>
            </div>
        </div>
    </div>
    <button class="btn_more" ng-click="add_more()" ng-if="btn_show" ng-bind="btn_text">加载更多</button>
</section>


<script src="/js/controllers/share.js"></script>
