<?php
/* @var $this yii\web\View */

$this->title = '评论详情';
?>
<link rel="stylesheet" href="/css/share/share_comment.css">
<section class="share_comment_box" ng-app="myApp" ng-controller="shareCommentCtrl" ng-cloak>
    <div class="comment_box_top">
        <div class="comment_box_top_title">
            <span>{{share_comment.name}}</span>
        </div>
        <div class="comment_box_top_content">
            <p class="comment_describe">{{share_comment.describe}}</p>
            <p class="comment_username">
                <span class="tag_item">
                    <span ng-bind="share_comment.data==null?'0':share_comment.data.length"></span>
                    <span>条指标</span>
                </span>
                <span class="tag_box_name">
                    <img src="/images/login/user.png" class="img_icon" alt="">
                    <span>{{share_comment.username}}</span>
                </span>
                <span class="tag_box_time">
                    <img src="/images/share/time.png" class="img_icon" alt="">
                    <span>{{share_comment.timeString}}</span>
                </span>
            </p>
            <p class="tagName_box">
                <span class="tagName" ng-repeat="item in share_comment.tagNames  track by $index">{{item}} </span>
            </p>
        </div>

    </div>
    <div class="comment_box_bom">
        <div class="comment_box_bom_top">
            <span>评论(</span>
            <span>{{share_comment.comment.count}}</span>
            <span>)</span>
        </div>
        <div class="comment_box_bom_mid">
            <textarea ng-model="share_comment.textarea_info"
                class="ioc_box_textarea" placeholder="请输入您的评论" name="" id="" cols="30"
                rows="10"></textarea>
            <button class="send_btn" ng-click="addComment()">评论</button>
        </div>
        <div class="comment_box_bom_bom">
            <div class="item_box" ng-repeat="item in share_comment.pushComment">
                <p class="item_comment">
                    <span class="item_name">{{item.username}}</span>
                    <span>：</span>
                    <span>{{item.content}}</span>
                </p>
                <p class="item_time">{{item.created_at*1000 | date:'yyyy-MM-dd HH:mm'}}</p>
            </div>
        </div>
    </div>
        <button class="btn_more" ng-if="add_more_show" ng-click="add_more()" >加载更多</button>
</section>
<script src="/js/controllers/share_comment.js"></script>
