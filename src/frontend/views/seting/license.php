<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = '许可证';
?>
<!-- Main content -->
<section class="content" ng-app="myApp" >

  <style type="text/css">
    .nav-tabs-custom {
      overflow: visible;
    }
  </style>
  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">

        <?php include 'nav.php';?>

        <div class="tab-content">
          <!-- License-->

          <div class="tab-pane active" id="License" ng-controller="LicenseCtrl">
            <section class="ng-cloak" ng-show="License.list">
              <h4 class="seting-header" style="margin-bottom: -1px;">
                <i class="fa fa-key"></i>
                证书列表
              </h4>

              <div class="row">
                <div class="col-sm-12">
                    <table class="table table-hover" style="border-bottom: 1px solid #f4f4f4;">
                        <tr>
                          <th style="text-align:center;">序号</th>
                          <th>序列号</th>
                          <th>受保护机构</th>
                          <th>授权时间</th>
                          <th>授权到期时间</th>
                          <th>许可证状态</th>
                        </tr>

                        <tr style="cursor: pointer;" ng-repeat="(SN, item) in License.list">
                            <td style="text-align: center;" ng-bind="$index+1"></td>
                            <td ng-bind="item.SN"></td>
                            <td ng-bind="item.orgName"></td>
                            <td ng-bind="item.startTime | date:'yyyy-MM-dd HH:mm'"></td>
                            <td ng-bind="item.endTime | date:'yyyy-MM-dd HH:mm'"></td>
                            <td ng-bind="item.status"></td>
                        </tr>
                      </table>
                </div>
              </div>
            </section>

            <section>
              <div class="form-group">
                <input type="file" id="LicenseFile" name="" style="display: none;">
                <div class="btn-group pull-right">
                  <button type="button" class="btn btn-primary" ng-click="online();">在线激活</button>
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu" style="min-width: 100%;">
                    <li>
                      <a href="javascript:void(0);" ng-click="import()">导入许可证</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div id="hide_box" style="display: none;">
                <div id="inputSN" >
                  <form>
                    <div class="box-body">
                      <div class="form-group col-md-12">
                        <label for="InputVersion">序列号：</label>
                        <input class="form-control" ng-model="SN">
                        <p class="help-block" ng-bind="'机器码：'+key"></p>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </section>

          </div>
          <!-- ./ProFile -->

        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>

</section>

<!-- /.content -->


<script type="text/javascript" src="/js/controllers/License.js"></script>








































