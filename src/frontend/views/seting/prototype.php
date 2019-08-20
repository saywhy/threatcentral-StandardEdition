<?php
/* @var $this yii\web\View */

$this->title = '情报管理';
?>
<!-- Main content -->
<section class="content" ng-app="myApp" ng-controller="PrototypeCtrl" ng-cloak >
  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">

        <?php include 'nav.php';?>

        <div class="tab-content">
          <div class="tab-pane active" id="Prototype">
            <input type="file" style="display: none;" id='inputFile_cert'>
            <input type="file" style="display: none;" id='inputFile_pkey'>

            <section ng-if="!nowPrototype" ng-cloak >
              <h4 class="seting-header">商业情报：</h4>
              <div class="row">
                <div class="col-lg-2 col-sm-4 col-xs-6 ng-cloak" ng-repeat="(name,item) in prototypes.red track by $index" style="height:300px;">
                  <div class="small-box" style="cursor: pointer;" ng-class="item.node ? bgList[$index%7] : 'bg-gray'" ng-click="detail(item);">
                    <div class="inner">
                      <h3 ng-bind="item.config.attributes.confidence"></h3>
                      <p >
                          <span ng-bind="item.orgName" style="font-size: 20px;"></span>
                        </p>
                        <p ng-bind="name"></p>
                        <p >
                        <span> 上次更新时间：</span>
                          <span  ng-bind="item.last_run |date:'yyyy/MM/dd hh:mm:ss'"> 2019.01.13</span>
                        </p>
                        <p >
                        <span>结果：</span>
                        <span ng-bind="item.sub_state=='ERROR'?'失败':'' "></span>
                        <span ng-bind="item.sub_state=='SUCCESS'?' 成功':'' "></span>
                        </p>
                        <p >
                        <span>状态：</span>
                        <span ng-bind="item.state"></span>
                        </p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-institution"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer" ng-click="$event.stopPropagation();changeStatus(item);">
                      <span ng-bind="item.node ? '停用' : '启用'"></span>
                      <i class="fa" ng-class="item.node ? 'fa-toggle-on' : 'fa-toggle-off'"></i>
                    </a>
                  </div>
                </div>
              </div>
            </section>
            <section ng-if="!nowPrototype">
              <h4 class="seting-header">开源情报：</h4>
              <div class="row">
                <div class="col-lg-2 col-sm-4 col-xs-6 ng-cloak" ng-repeat="(name,item) in prototypes.green" style="height:300px;">
                  <div class="small-box" style="cursor: pointer;" ng-class="item.node ? bgList[$index%7] : 'bg-gray'" ng-click="detail(item);">
                    <div class="inner">
                      <h3 ng-bind="item.config.attributes.confidence"></h3>
                      <p ng-bind="item.orgName" style="font-size: 20px;"></p>
                      <p ng-bind="name"></p>
                    </div>
                     <span> 上次更新时间：</span>
                          <span  ng-bind="item.last_run |date:'yyyy/MM/dd hh:mm:ss'"> 2019.01.13</span>
                        </p>
                        <p >
                        <span>结果：</span>
                        <span ng-bind="item.sub_state=='ERROR'?'失败':'' "></span>
                        <span ng-bind="item.sub_state=='SUCCESS'?' 成功':'' "></span>
                        </p>
                        <p >
                        <span>状态：</span>
                        <span ng-bind="item.state"></span>
                        </p>
                    <div class="icon">
                      <i class="fa fa-code-fork"></i>
                    </div>
                    <a href="javascript:void(0);" class="small-box-footer" ng-click="$event.stopPropagation();changeStatus(item);">
                      <span ng-bind="item.node ? '停用' : '启用'"></span>
                      <i class="fa" ng-class="item.node ? 'fa-toggle-on' : 'fa-toggle-off'"></i>
                    </a>
                  </div>
              </div>
            </section>
            <section class="ng-cloak" ng-if="nowPrototype">
              <h4 class="seting-header">
                情报详情：
                <button class="btn btn-sm btn-primary pull-right" style="margin-top: -10px;" ng-click="backList();">
                  <i class="fa fa-arrow-left"></i>
                  返回
                </button>
                <button class="btn btn-sm btn-success pull-right" style="margin-right: 10px;margin-top: -10px;" ng-if="changed" ng-click="save();">
                  <i class="fa fa-save"></i>
                  保存
                </button>
              </h4>
              <div class="box box-solid" style="box-shadow:none;margin:0;">
                <div class="box-body" style="padding:0;">
                  <div class="row">
                    <div class="col-md-6 border-right">
                      <ul class="nav nav-stacked sensor-detail">
                        <li>
                          <span class="sensor-detail-title">情报名称</span>
                          <span ng-bind="nowPrototype.name"></span>
                        </li>
                        <li>
                          <span class="sensor-detail-title">信心指数</span>
                          <span class="value">
                            <input class="form-control input-sm" type="number" max="100" min="0" ng-model="nowPrototype.config.attributes.confidence">
                          </span>
                        </li>
                        <li>
                          <span class="sensor-detail-title">更新周期</span>
                          <span class="value" ng-if="nowPrototype.config.interval != null">
                            <input class="form-control input-sm" type="number" min="0" ng-model="nowPrototype.config.interval">
                            (秒)
                          </span>
                        </li>
                        <li>
                          <span class="sensor-detail-title">私钥</span>
                          <span>
                            <label class="btn btn-sm btn-default" for="inputFile_pkey">
                              <i class="fa fa-upload"></i>
                              <span>替换私钥</span>
                            </label>
                          </span>
                        </li>
                        <li>
                          <span class="sensor-detail-title" style="width: 140px;">最后一次更新成功时间:</span>
                          <span>
                            2019.01.16
                          </span>
                        </li>
                      </ul>
                    </div>
                    <div class="col-md-6 border-right">
                      <ul class="nav nav-stacked sensor-detail">
                        <li>
                          <span class="sensor-detail-title">情报类型</span>
                          <span ng-bind="nowPrototype.config.attributes.type"></span>
                        </li>
                        <li>
                          <span class="sensor-detail-title">威胁程度</span>
                          <span class="value" ng-if="nowPrototype.config.attributes.threat != null">
                            <input class="form-control input-sm" type="number" max="5" min="0" ng-model="nowPrototype.config.attributes.threat">
                          </span>
                        </li>
                        <li>
                          <span class="sensor-detail-title">情报状态</span>
                          <span>
                            <input class="tgl tgl-ios" id="detail_status" type="checkbox" ng-checked="nowPrototype.node" ng-click="$event.stopPropagation();changeStatus(nowPrototype);">
                            <label class="tgl-btn" for="detail_status"></label>
                          </span>
                        </li>
                        <li>

                        </li>
                        <li>
                          <span class="sensor-detail-title">证书</span>
                          <span>
                            <label class="btn btn-sm btn-default" for="inputFile_cert">
                              <i class="fa fa-upload"></i>
                              <span>替换证书</span>
                            </label>
                          </span>
                        </li>
                        <li>

                       </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

            </section>

          </div>
        </div>
      </div>
    </div>
  </div>


<!--
  <style type="text/css">
    .footer-float{
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 60px;
      background: rgba(255,255,255,.9);
      z-index: 100;
      line-height: 60px;
      text-align: center;
    }
    .footer-seat{
      height: 60px;
    }
  </style>
  <div class="row footer-seat"></div>
  <div class="footer-float" >
    <button class="btn btn-primary margin">应&nbsp;&nbsp;用</button>
    <button class="btn btn-default margin">取&nbsp;&nbsp;消</button>
  </div>
-->
</section>

<!-- /.content -->

<script type="text/javascript" src="/js/controllers/prototype.js"></script>








































