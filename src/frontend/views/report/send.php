<?php
/* @var $this yii\web\View */

$this->title = '报表发送';
?>
</style>
<link rel="stylesheet" href="/css/report/send.css">
<section ng-app="myApp" ng-controller="sendCtrl" ng-cloak>
    <div class="send_container">
        <div class="send_box_top">
            <div class="send_box_left">状态</div>
            <span ng-click="type_choose('open')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="type_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!type_true" alt="">
                <span>启用</span>
            </span>
            <span ng-click="type_choose('closed')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!type_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="type_true" alt="">
                <span>停用</span>
            </span>
        </div>
        <div class="send_box_mid">
            <div class="send_box_left">发送周期</div>
            <span ng-click="cycle('day')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="day_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!day_true" alt="">
                <span>每日</span>
            </span>
            <span ng-click="cycle('week')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="week_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!week_true" alt="">
                <span>每周</span>
            </span>
            <span ng-click="cycle('month')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="month_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!month_true" alt="">
                <span>每月</span>
            </span>
        </div>
        <div class="send_box_bom">
            <div class="send_box_left">发送格式</div>
            <span ng-click="format_choose('word')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="word_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!word_true" alt="">
                <span>Word</span>
            </span>
            <span ng-click="format_choose('excel')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!word_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="word_true" alt="">
                <span>Excel</span>
            </span>
        </div>
        <div class="send_box_addr">
            <div class="send_box_addr_left">收件人邮箱</div>
            <div ng-repeat="item in input_list" class="input_list_box">
            <input  class="input_bom_box"  type="text" ng-model="item.name" >
                <img src="/images/report/del.png" class="addr_icon" ng-click="del_input($index)" ng-if="!item.icon" alt="">
                <img src="/images/report/add.png" class="addr_icon"  ng-click="add_input($index)" ng-if="item.icon"  alt="">
            </div>
            <div>
        </div>
          <div class="btn_box">
            <button class="save_btn" ng-click="set_config()">
                保存
            </button>
            <button class="cancel_btn" ng-click="can_cel()">
                取消
            </button>
        </div>

    </div>
</section>


<script src="/js/controllers/send.js"></script>
