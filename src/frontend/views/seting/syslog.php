<?php
/* @var $this yii\web\View */

$this->title = 'SYSLOG配置';
?>

<link rel="stylesheet" href="/css/set/syslog.css">
<section class="syslog_container" ng-app="myApp" ng-cloak ng-controller="SyslogCtrl">
    <div class="log_box">
        <div class="log_box_top">
            <div class="log_input_box">
                <button class="top_btn" ng-click="syslog_add()">添加SYSLOG服务器</button>
            </div>
        </div>
        <div class="log_box_table">

            <table class="table  domain_table ng-cloak">
                <tr style="text-algin:center">
                    <th>SYSLOG服务器IP</th>
                    <th>端口</th>
                    <th>传输协议</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <tr ng-repeat="item in pages.data">
                    <td>{{item.server_ip}}</td>
                    <td>{{item.server_port}}</td>
                    <td>{{item.protocol}}</td>
                    <td>{{item.status =='1'?'启用':'未启用'}}</td>
                    <td class="cursor">
                        <img src="/images/set/edit_icon.png" class="img_icon"
                            ng-click="modify(item);$event.stopPropagation();" alt="">
                        <img src="/images/set/cel_icon.png" class="img_icon"
                            ng-click="del(item);$event.stopPropagation();" alt="">
                    </td>
                </tr>
            </table>
            <!-- angularjs分页 -->
            <div style="padding: 25px;">
                <span style="font-size: 14px;color: #BBBBBB;">共有
                    <span ng-bind="pages.count"></span>条</span>
                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)"
                            ng-if="pages.pageNow>1">上一页</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(1)" ng-if="pages.pageNow>1">1</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="pages.pageNow>4">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-2)" ng-bind="pages.pageNow-2"
                            ng-if="pages.pageNow>3"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-bind="pages.pageNow-1"
                            ng-if="pages.pageNow>2"></a>
                    </li>
                    <li class="active">
                        <a href="javascript:void(0);" ng-bind="pages.pageNow"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-bind="pages.pageNow+1"
                            ng-if="pages.pageNow<pages.maxPage-1"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+2)" ng-bind="pages.pageNow+2"
                            ng-if="pages.pageNow<pages.maxPage-2"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-if="pages.pageNow<pages.maxPage-3">...</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.maxPage)" ng-bind="pages.maxPage"
                            ng-if="pages.pageNow<pages.maxPage"></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)"
                            ng-if="pages.pageNow<pages.maxPage">下一页</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- 添加syslog -->
    <div style="display: none;" id="syslog_box">
        <div id="syslog">
            <div class="item_box">
                <label for="InputVersion" class="label_w" >状态：</label>
                <div style="display: inline-block;vertical-align: middle;" id="InputVersion">
                    <input class="tgl tgl-ios" id="encryption" type="checkbox" ng-checked="syslog.ONBOOT == '1'"
                        ng-click="syslog.ONBOOT  = (syslog.ONBOOT =='0'?'1':'0')">
                    <label class="tgl-btn" for="encryption"></label>
                </div>
            </div>
            <div class="item_box">
                <label for="InputVersion" class="label_w" >传输协议：</label>
                <label class="i-checks m-b-none">
                    <input type='radio' id="udp" ng-value="3" ng-model="syslog.trans" name="trans">
                    <i></i>
                    <span class="item-span-content">UDP</span>
                </label>
                <label class="i-checks m-b-none" style="margin-left: 20px;">
                    <input type='radio' id="tcp" ng-value="4" ng-model="syslog.trans" name="trans">
                    <i></i>
                    <span class="item-span-content">TCP</span>
                </label>
            </div>
            <div class="item_box">
                <label for="Inputsion"class="label_w">SYSLOG服务器IP：</label>
                <input class="form-control" style="width: 150px;border-radius: 3px;display: inline-block;" type='text'
                    ng-model="syslog.ip" style="margin: 4px 4px 4px 15px" />
                <label for="Input" style="width: 50px;margin-left:10px;">端口：</label>
                <input class="form-control" style="width: 100px;border-radius: 3px;display: inline-block;" type='text'
                    ng-model="syslog.port" style="margin: 4px 4px 4px 15px" />
            </div>
            <div class="item_box btn_box">
                <button class="btn_ok" ng-if="zeroModal_type" ng-click="confirm_syslog('add')">确定</button>
                <button class="btn_ok" ng-if="!zeroModal_type" ng-click="confirm_syslog('edit')">确定</button>
                <button class="btn_cancel" ng-click="cancel_syslog()">取消</button>
            </div>
        </div>
    </div>
</section>
<script src="/js/controllers/set/syslog.js"></script>
