<?php
/* @var $this yii\web\View */
$this->title = 'APT武器库';
?>
<section ng-app="myApp" ng-controller="aptCtrl" ng-cloak>

    <div class="apt_header">
        <div class="apt_header_select_box">
            <img src="/images/alert/search_icon.png" class="serch_icon" alt="">
            <span class="search_tag" ng-repeat="item in attack_source_search track by $index">
                <span>{{item.name}}</span>
                <img style="margin-left: 5px;cursor:pointer;" ng-click="del_name(item,'source')"
                    src="/images/search/closed_i.png" alt="">
            </span>
            <span class="search_tag" ng-repeat="item in industry_search track by $index">
                <span>{{item.name}}</span>
                <img style="margin-left: 5px;cursor:pointer;" ng-click="del_name(item,'industry')"
                    src="/images/search/closed_i.png" alt="">
            </span>
            <span class="search_tag" ng-repeat="item in attack_target_search track by $index">
                <span>{{item.name}}</span>
                <img style="margin-left: 5px;cursor:pointer;" ng-click="del_name(item,'target')"
                    src="/images/search/closed_i.png" alt="">
            </span>
            <span class="search_tag" ng-repeat="item in motive_search track by $index">
                <span>{{item.name}}</span>
                <img style="margin-left: 5px;cursor:pointer;" ng-click="del_name(item,'motivation')"
                    src="/images/search/closed_i.png" alt="">
            </span>
            <span style="position: absolute;top: 50%; right: 10px;transform: translateY(-50%);">
                <span style="margin-right: 15px; font-size: 14px;color: #BBBBBB;">
                    <span>{{card_list.length}}</span>
                    条搜索结果</span>
                <img src="/images/search/closed_o.png" ng-if="closed_search_if" ng-click="closed_search()" alt="">
            </span>
        </div>
        <div class="apt_search_list">
             <div class="apt_search_list_item" ng-class="more_source_if?'height30':'min_height'">
                <div class="apt_search_list_item_title">
                    攻击来源
                </div>
                <div class="apt_search_list_item_mid">
                    <span class="apt_search_list_item_value" ng-class="item.class?'blue':'black'"
                        ng-repeat="(index,item) in attack_source track by $index"
                        ng-click="item_p_click(item,'source')">
                        <span>{{item.name}}</span>
                        <span>(</span>
                        <span>{{item.num}}</span>
                        <span>)</span>
                    </span>
                </div>
                <div class="btn_more" ng-click="more_source(more_source_if)">
                    <img src="/images/apt/open.png" ng-if="more_source_if" class="img_open" alt="">
                    <img src="/images/apt/closed.png" ng-if="!more_source_if" class="img_open" alt="">
                    <span ng-if="more_source_if">展开更多</span>
                    <span ng-if="!more_source_if">收起更多</span>
                </div>
            </div>
             <div class="apt_search_list_item" ng-class="more_industry_if?'height30':'min_height'">
                <div class="apt_search_list_item_title">
                    针对行业
                </div>
                <div class="apt_search_list_item_mid">
                    <span class="apt_search_list_item_value" ng-class="item.class?'blue':'black'"
                        ng-repeat="(index,item) in industry track by $index"
                        ng-click="item_p_click(item,'industry')">
                        <span>{{item.name}}</span>
                        <span>(</span>
                        <span>{{item.num}}</span>
                        <span>)</span>
                    </span>
                </div>
                <div class="btn_more" ng-click="more_industry(more_industry_if)">
                    <img src="/images/apt/open.png" ng-if="more_industry_if" class="img_open" alt="">
                    <img src="/images/apt/closed.png" ng-if="!more_industry_if" class="img_open" alt="">
                    <span ng-if="more_industry_if">展开更多</span>
                    <span ng-if="!more_industry_if">收起更多</span>
                </div>
            </div>
             <div class="apt_search_list_item" ng-class="more_target_if?'height30':'min_height'">
                <div class="apt_search_list_item_title">
                    攻击目标
                </div>
                <div class="apt_search_list_item_mid">
                    <span class="apt_search_list_item_value" ng-class="item.class?'blue':'black'"
                        ng-repeat="(index,item) in attack_target track by $index"
                        ng-click="item_p_click(item,'target')">
                        <span>{{item.name}}</span>
                        <span>(</span>
                        <span>{{item.num}}</span>
                        <span>)</span>
                    </span>
                </div>
                <div class="btn_more" ng-click="more_target(more_target_if)">
                    <img src="/images/apt/open.png" ng-if="more_target_if" class="img_open" alt="">
                    <img src="/images/apt/closed.png" ng-if="!more_target_if" class="img_open" alt="">
                    <span ng-if="more_target_if">展开更多</span>
                    <span ng-if="!more_target_if">收起更多</span>
                </div>
            </div>
             <div class="apt_search_list_item" ng-class="more_motive_if?'height30':'min_height'">
                <div class="apt_search_list_item_title">
                    动机
                </div>
                <div class="apt_search_list_item_mid">
                    <span class="apt_search_list_item_value" ng-class="item.class?'blue':'black'"
                        ng-repeat="(index,item) in motive track by $index"
                        ng-click="item_p_click(item,'source')">
                        <span>{{item.name}}</span>
                        <span>(</span>
                        <span>{{item.num}}</span>
                        <span>)</span>
                    </span>
                </div>
                <div class="btn_more" ng-click="more_motive(more_motive_if)">
                    <img src="/images/apt/open.png" ng-if="more_motive_if" class="img_open" alt="">
                    <img src="/images/apt/closed.png" ng-if="!more_motive_if" class="img_open" alt="">
                    <span ng-if="more_motive_if">展开更多</span>
                    <span ng-if="!more_motive_if">收起更多</span>
                </div>
            </div>
        </div>
    </div>
    <div class="apt_container_bom">
        <div class="row">
            <div class="col-md-3 apt_container_bom_item" ng-repeat="item in card_list" ng-click="card_detail(item)">
                <div class="apt_container_bom_item_box">
                    <div class="apt_container_bom_item_box_img">
                        <img class="apt_container_bom_item_box_img" ng-src="{{item.raw_picture_169}}" alt="">
                    </div>
                    <div class="card_info">
                        <p class="card_info_title">{{item.name}}</p>
                        <div>
                            <p class="card_info_p">
                                <span class="card_info_name">攻击来源：</span>
                                <span class="card_info_value"
                                    title="{{item.attack_source}}">{{item.attack_source}}</span>
                            </p>
                            <p class="card_info_p">
                                <span class="card_info_name">攻击目标：</span>
                                <span class="card_info_value"
                                    title="{{item.attack_target}}">{{item.attack_target}}</span>
                            </p>
                            <p class="card_info_p">
                                <span class="card_info_name">针对行业:</span>
                                <span class="card_info_value"
                                    title="{{item.target_industry}}">{{item.target_industry}}</span>
                            </p>
                            <p class="card_info_p">
                                <span class="card_info_name">动机:</span>
                                <span class="card_info_value" title="{{item.motive}}"> {{item.motive}}</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <!-- <div class="col-md-3 bom_item" ng-repeat="item in card_list" ng-click="card_detail(item)">
                    <div class="bom_item_content">
                        <p class="bom_item_content_title">
                            <span>{{item.name}}</span>
                        </p>
                        <p style="margin-top: 80px;" class="tittle_text">最近观察到的攻击时间</p>
                        <p class="item_p_long" title="{{item.lately_attack_time}}">{{item.lately_attack_time}}</p>
                        <p class="tittle_text">攻击目标</p>
                        <p class="item_p_long" title="{{item.attack_target}}">{{item.attack_target}}</p>
                        <p class="tittle_text">针对行业</p>
                        <p class="item_p_long" title="{{item.target_industry}}">{{item.target_industry}}</p>
                        <p class="tittle_text">动机</p>
                        <p class="item_p_long" title="{{item.target_industry}}">{{item.motive}}</p>
                        <hr style="margin:0;">
                        <p style="margin-top: 5px; margin-bottom: 10px;">详细信息</p>
                    </div>
                </div> -->
        </div>
    </div>
</section>
<link rel="stylesheet" href="/css/apt/apt.css">
<script src="/js/controllers/apt.js"></script>
