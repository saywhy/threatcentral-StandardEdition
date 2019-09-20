<?php
/* @var $this yii\web\View */

$this->title = '情报API';
?>
<link rel="stylesheet" href="/css/api/api.css">
<section class="api_content" ng-app="myApp" ng-controller="myApi" ng-cloak>
    <div class="api_box">
        <div class="api_box_top">
            <span class="api_box_top_name">证书验证</span>
            <span ng-click="net_choose('open')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="set_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="!set_true" alt="">
                <span>开启</span>
            </span>
            <span ng-click="net_choose('closed')" class="choose_box">
                <img src="/images/report/choose_true.png" class="img_choose_icon" ng-if="!set_true" alt="">
                <img src="/images/report/choose_false.png" class="img_choose_icon" ng-if="set_true" alt="">
                <span>关闭</span>
            </span>
        </div>
        <div class="api_box_mid">
            <p class="api_box_mid_name">授权管理</p>
            <div class="api_box_mid_search_box">
                <input class="api_box_mid_input" placeholder="请输入用户名" type="text" ng-model="token_institution">
                <img src="/images/alert/search_icon.png" class="search_icon" alt="">
                <button class="api_box_mid_button_left" ng-click="token_search()">搜索</button>
                <button class="api_box_mid_button_right" ng-click="token_add()">生成TOKEN</button>
            </div>
        </div>
        <div>
            <table class="table  table-striped ng-cloak">
                <tr style="text-algin:center" class="alert_table_tr">
                    <th style="width:150px;padding-left:48px;">分支机构</th>
                    <th  style="min-width:350px">Token</th>
                    <th style="width:100px">证书</th>
                    <th style="width:130px">创建时间</th>
                    <th style="width:130px">更新时间</th>
                    <th style="width:270px">使用期限</th>
                    <th style="width:100px">查询次数</th>
                    <th style="width:140px">操作</th>
                </tr>
                <tr class="alert_table_tr" style="cursor: pointer;" ng-repeat="item in api_list.data">
                    <td style="padding-left:48px;" title="{{item.institution}}">{{item.institution}}</td>
                    <td title="{{item.token}}" ng-click="copy_token(tem.token)">
                    <span>{{item.token}}</span>
                     <textarea id="{{item.token}}" style="display:none" ></textarea>
                    </td>
                    <td>
                        <button class="down_btn" ng-click="download()">下载证书</button>
                    </td>
                    <td title="{{item.create_time*1000| date:'yyyy-MM-dd HH:mm'}}">
                        {{item.create_time*1000| date:'yyyy-MM-dd HH:mm'}}</td>
                    <td title="{{item.update_time*1000| date:'yyyy-MM-dd HH:mm'}}">
                        {{item.update_time*1000| date:'yyyy-MM-dd HH:mm'}}</td>
                    <td>
                        <span>
                            {{item.start_time*1000| date:'yyyy-MM-dd HH:mm'}}
                        </span>
                        <span>-</span>
                        <span>
                            {{item.end_time*1000| date:'yyyy-MM-dd HH:mm'}}
                        </span>
                    </td>
                    <!-- <td title="{{item.rest_count+'/'+item.search_count}}">{{item.rest_count+'/'+item.search_count}}</td> -->
                    <td style="text-align:center">
                    <span ng-if="item.rest_count!='0'">{{item.search_count}}</span>
                    <span ng-if="item.rest_count!='0'">/</span>
                    <span ng-if="item.rest_count=='0'">∞</span>
                    <span ng-if="item.rest_count!='0'">{{item.rest_count}}</span>
                </td>
                    <td>
                        <input class="tgl tgl-ios" type="checkbox" value="item.choose" ng-checked="item.choose"
                            ng-click="choose_open(item)" id="{{item.id}}">
                        <label class="tgl-btn" for="{{item.id}}"
                            style="margin-top: 3px;margin-right: 5px; float: left;"></label>
                        <img src="/images/set/update.png" class="img_margin" ng-click="update_token(item)" alt="">
                        <img src="/images/set/edit_icon.png" class="img_margin" ng-click="edit_token(item)" alt="">
                        <img src="/images/set/cel_icon.png" class="img_margin" ng-click="cel_tolen(item)" alt="">
                    </td>
                </tr>
            </table>
            <div style="padding: 25px;">
                <span style="font-size: 14px;color: #BBBBBB;">共有
                    <span ng-bind="api_list.count"></span>条</span>
                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.pageNow-1)"
                            ng-if="api_list.pageNow>1">上一页</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(1)" ng-if="api_list.pageNow>1">1</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="api_list.pageNow>4">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.pageNow-2)"
                            ng-bind="api_list.pageNow-2" ng-if="api_list.pageNow>3"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.pageNow-1)"
                            ng-bind="api_list.pageNow-1" ng-if="api_list.pageNow>2"></a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" ng-bind="api_list.pageNow"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.pageNow+1)"
                            ng-bind="api_list.pageNow+1" ng-if="api_list.pageNow<api_list.maxPage-1"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.pageNow+2)"
                            ng-bind="api_list.pageNow+2" ng-if="api_list.pageNow<api_list.maxPage-2"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="api_list.pageNow<api_list.maxPage-3">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.maxPage)"
                            ng-bind="api_list.maxPage" ng-if="api_list.pageNow<api_list.maxPage"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="get_api_list(api_list.pageNow+1)"
                            ng-if="api_list.pageNow<api_list.maxPage">下一页</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 生成token -->
    <div style="display: none;" id="token_box">
        <div id="token">
            <div class="token_top">
                <p class="token_name">分支结构名称</p>
                <input type="text" placeholder="请填写分支机构名称" class="token_top_input"
                    ng-model="add_token_data.institution">
            </div>
            <div class="token_mid">
                <p class="token_name">生效时间</p>
                <img src="/images/report/time.png" class="start_time_icon" alt="">
                <img src="/images/report/time.png" class="end_time_icon" alt="">
                <input type="text" placeholder="开始时间" id="start_time_picker" readonly
                    class="token_top_input token_top_input_time_left">
                <input type="text" placeholder="结束时间" id="end_time_picker" readonly
                    class="token_top_input token_top_input_time_right">
            </div>
            <div class="token_bom">
                <p class="token_name">查询次数</p>
                <input type="number"  ng-blur="add_token_blur()"  placeholder="请输入查询次数" class="token_top_input" ng-model="add_token_data.search_count">
            </div>
            <div class="token_btn_box">
                <button class="token_btn_ok" ng-click="token_save()">确定</button>
                <button class="token_btn_cancel" ng-click="token_cancel()">取消</button>
            </div>
        </div>
    </div>
    <!-- 编辑token -->
    <div style="display: none;" id="edit_token_box">
        <div id="edit_token">
            <div class="token_top">
                <p class="token_name">分支结构名称</p>
                <input type="text" placeholder="请填写分支机构名称"  disabled="disabled" class="token_top_input"
                    ng-model="edit_token_item.institution">
            </div>
            <div class="token_mid">
                <p class="token_name">生效时间</p>
                <img src="/images/report/time.png" class="start_time_icon" alt="">
                <img src="/images/report/time.png" class="end_time_icon" alt="">
                <input type="text" placeholder="开始时间" id="edit_start_time_picker" readonly
                    class="token_top_input token_top_input_time_left">
                <input type="text" placeholder="结束时间" id="edit_end_time_picker" readonly
                    class="token_top_input token_top_input_time_right">
            </div>
            <div class="token_bom">
                <p class="token_name">查询次数</p>
                <input type="number" ng-blur="edit_token_blur()"  placeholder="请输入查询次数" class="token_top_input" ng-model="edit_token_item.rest_count">
            </div>
            <div class="token_btn_box">
                <button class="token_btn_ok" ng-click="edit_token_save()">确定</button>
                <button class="token_btn_cancel" ng-click="edit_token_cancel()">取消</button>
            </div>
        </div>
    </div>
</section>
<script src="/js/controllers/api.js"></script>
