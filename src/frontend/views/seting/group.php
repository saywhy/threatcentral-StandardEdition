<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = '用户组';
?>
<!-- Main content -->
<section class="content" ng-app="myApp" ng-controller="group">


  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">

        <?php include 'nav.php';?>
        
        <div class="tab-content">

          <!-- group-->
          <div class="tab-pane active" id="group">

            <div class="row">
              <div class="col-xs-3">
                <div class="box-header with-border">
                  <h3 class="box-title">用户组</h3>
                  <div class="box-tools pull-right">
                    <div class="btn-group">
                      <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-navicon"></i>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void(0);" ng-click="add()">新建组</a></li>
                        <li ng-show="nowGroup"><a href="javascript:void(0);" ng-click="add('children')">新建下级组</a></li>
                        <li class="divider" ng-show="nowGroup"></li>
                        <li ng-show="nowGroup"><a href="javascript:void(0);" ng-click="del()">删除组</a></li>
                      </ul>
                    </div>
                  </div>

                </div>
                <div class="box-body" id="groupTree"></div>
              </div>
              <div class="col-xs-9 ng-cloak" ng-show="nowGroup">
                <div class="box-header with-border">
                  <h3 class="box-title">编辑用户组</h3>
                </div>
                <div class="box-body">
                  <div class="row margin">
                    <div class="form-group col-md-8">
                      <label>分组名称</label>
                      <input type="text" class="form-control" ng-model="nowGroup.text" />
                    </div>
                    <div class="form-group col-md-4">
                      <label style="width: 100%;">&nbsp;</label>
                      <button type="button" style="max-width: 120px;" class="form-control btn btn-sm btn-primary" ng-click="save()" ng-disabled="!nowGroup.text">保&nbsp;&nbsp;存</button>
                    </div>
                  </div>
                </div>
                <div class="box-header with-border">
                  <h3 class="box-title">用户列表</h3>
                </div>
                <div class="box-body">
                  <div class="row margin">
                    <table class="table table-hover" id="userList">
                      <thead>
                        <tr>
                          <th>用户名</th>
                          <th>角色</th>
                          <th>创建时间</th>
                          <th>操作</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ./group -->

        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>

</section>

           
<!-- /.content -->
<script type="text/javascript">
var GroupList = <?= json_encode($GroupList) ?>;
</script>
<script type="text/javascript" src="/js/controllers/group.js"></script>

