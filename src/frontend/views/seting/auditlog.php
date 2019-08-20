<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = '审计日志';
?>
<style type="text/css">
</style>
<section class="content" ng-app="myApp">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <?php include 'nav.php';?>
                <div class="tab-content">
                    <div class="tab-pane active" id="auditlog" ng-controller="auditlogCtrl">
                    <div class="row" style="padding-bottom: 20px">
                    <div class="col-md-12">
                        <div class="tab-content" style="padding-top:0px;border-bottom:0px;">
                            <div class="tab-pane active" id="protect">
                                <div class="row margin download_relative">
                                    <div class="form-group col-md-2">
                                        <label>用户标识</label>
                                        <input type="text" class="form-control input_radius" ng-model="log.username"
                                            ng-keyup="myKeyup($event)">
                                    </div>
                                    <div class="form-group col-md-4" style="max-width: 300px;">
                                        <label>时间</label>
                                        <input type="text" class="form-control timerange input_radius" readonly style="background-color: #fff;">
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label style="width: 100%;">&nbsp;</label>
                                        <button class="form-control btn btn-primary btn_style" style="max-width: 80px;"
                                            ng-click="search()">搜&nbsp;索</button>
                                    </div>
                                    <div class="download_position ">
                                        <span>
                                            <img src="../src/images/icos/export.png" title="导出" ng-click="download(item.id)"
                                                width="18" height="18" alt="">
                                        </span>
                                    </div>
                                </div>
                                <div class="row margin">
                                    <table class="table table-hover ng-cloak" style="table-layout: fixed">
                                        <tr>
                                            <th style="width:80px">序号</th>
                                            <th style="width:150px">时间</th>
                                            <th style="width:120px">用户标识</th>
                                            <th>描述</th>
                                            <th style="width:120px">主机地址</th>
                                        </tr>
                                        <tr style="cursor: pointer;" ng-repeat="item in pages.data">
                                            <td ng-bind="$index + 1 + index_num">1</td>
                                            <td class="td_content" ng-bind="item.created_at"></td>
                                            <td class="td_content"  title="{{item.username}}" ng-bind="item.username"></td>
                                            <td class="td_content td_class" title="{{item.info}}" ng-bind="item.info"></td>
                                            <td class="td_content" title="{{item.userip}}"  ng-bind="item.userip"></td>
                                        </tr>
                                    </table>
                                    <!-- angularjs分页 -->
                                    <div style="border-top: 1px solid #f4f4f4;padding: 10px;">
                                        <em>共有
                                            <span ng-bind="pages.count"></span>条</em>
                                        <!-- angularjs分页 -->
                                        <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-if="pages.pageNow>1">上一页</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(1)" ng-if="pages.pageNow>1">1</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-if="pages.pageNow>4">...</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-2)"
                                                    ng-bind="pages.pageNow-2" ng-if="pages.pageNow>3"></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)"
                                                    ng-bind="pages.pageNow-1" ng-if="pages.pageNow>2"></a>
                                            </li>
                                            <li class="active">
                                                <a href="javascript:void(0);" ng-bind="pages.pageNow"></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)"
                                                    ng-bind="pages.pageNow+1" ng-if="pages.pageNow<pages.maxPage-1"></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+2)"
                                                    ng-bind="pages.pageNow+2" ng-if="pages.pageNow<pages.maxPage-2"></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-if="pages.pageNow<pages.maxPage-3">...</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.maxPage)" ng-bind="pages.maxPage"
                                                    ng-if="pages.pageNow<pages.maxPage"></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-if="pages.pageNow<pages.maxPage">下一页</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- /.content -->


<script type="text/javascript" src="/js/controllers/auditlog.js"></script>