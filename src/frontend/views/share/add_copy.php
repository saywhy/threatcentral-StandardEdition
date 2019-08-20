<?php
/* @var $this yii\web\View */

$this->title = '共享';
?>
<!-- Main content -->
<section class="content" ng-app="myApp" ng-controller="myCtrl">

  <style type="text/css">
    .table>tbody>tr>th{
      border-top: 0px solid;
    }
    .table {
        margin-bottom: 0;
    }
    .nav-tabs-custom>.nav-tabs>li{
      cursor: pointer;
    }
    .table>tbody>tr>td.value{
      padding: 3px;
    }
    .value input{
      max-width: 64px;
    }
    .list-group-item:first-child,.list-group-item:last-child{
      border-radius:0px;
    }
  </style>
  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom ">

        <ul class="nav nav-tabs">
          <li ng-class="tab=='import'?'active':''">
            <a ng-click="tab = 'import'">1.导入文件</a>
          </li>
          <li ng-class="tab=='select'?'active':''">
            <a ng-click="tab = 'select'">2.选择指标</a>
          </li>
          <li ng-class="tab=='setData'?'active':''">
            <a ng-click="tab = 'setData'">3.填充信息</a>
          </li>
          <li ng-class="tab=='setTags'?'active':''">
            <a ng-click="tab = 'setTags'">4.添加标签</a>
          </li>
        </ul>

        <div class="tab-content">

          <!-- import -->
          <div class="tab-pane" ng-class="tab=='import'?'active':''" id="import">
            <label style="text-align: center;padding: 40px 0;width: 100%;cursor: pointer;" for="InputFile">
              <label class="fa fa-cloud-upload text-green" style="font-size: 64px;cursor: pointer;" for="InputFile">
              </label>
              <p class="help-block" style="font-weight:400;">请点击选择要导入的文件</p>
              <p class="help-block" style="font-weight:400;">PDF、DOCX、XLS、XLSX、TXT</p>
            </label>

            <input type="file" id="InputFile" style="display: none;width: 1px;height: 1px">
          </div>
          <!-- ./import -->

          <!-- select -->
          <div class="tab-pane" ng-class="tab=='select'?'active':''" id="select">

            <div class="row margin">

                <table class="table table-hover ng-cloak">
                    <tr >
                      <th>序号</th>
                      <th>指标值</th>
                      <th>指标类型</th>
                      <th>
                        <span class="icheckbox_minimal-blue" ng-class="indicatorsList.length == indicatorsKeyList.length ? 'checked' : ''" ng-click="selectAll()"></span>
                      </th>
                    </tr>

                    <tr style="cursor: pointer;" ng-repeat="item in indicatorsList" ng-click="selectOne(item)">
                      <td ng-bind="$index+1"></td>
                      <td ng-bind="item.indicators"></td>
                      <td ng-bind="item.type"></td>
                      <td>
                        <span class="icheckbox_minimal-blue" ng-class="indicatorsKeyList.indexOf(item.indicators) != -1 ? 'checked' : ''"></span>
                      </td>
                    </tr>
                </table>
            </div>
          </div>
          <!-- ./select -->

          <!-- setData -->
          <div class="tab-pane" ng-class="tab=='setData'?'active':''" id="setData">
            <div class="row margin">

                <table class="table table-hover ng-cloak">
                    <tr >
                        <th>序号</th>
                        <th>指标值</th>
                        <th>指标类型</th>
                        <th>威胁度</th>
                        <th>置信度</th>
                        <th>新指标</th>
                    </tr>

                    <tr style="cursor: pointer;" ng-repeat="key in indicatorsKeyList">
                        <td ng-bind="$index+1"></td>
                        <td ng-bind="indicatorsObj[key].indicators"></td>
                        <td ng-bind="indicatorsObj[key].type"></td>
                        <td class="value">
                          <input class="form-control input-sm" type="number" max="5" min="0" ng-model="indicatorsObj[key].threat"/>
                        </td>
                        <td class="value">
                          <input class="form-control input-sm" type="number" max="100" min="0" ng-model="indicatorsObj[key].confidence"/>
                        </td>
                        <td>
                          <small class="label bg-green" ng-if="!indicatorsObj[key].old">新的指标</small>
                          <small class="label bg-yellow" ng-if="indicatorsObj[key].old">已有指标</small>
                        </td>
                    </tr>
                </table>
            </div>
          </div>
          <!-- ./setData -->

          <!-- setTags -->
          <div class="tab-pane" ng-class="tab=='setTags'?'active':''" id="setTags">
            <div class="row" style="max-width: 800px;margin: 10px auto;">
              <div class="form-horizontal">
                <div class="form-group">
                  <label for="share_name" class="col-sm-2 control-label">分享名称</label>
                  <div class="col-sm-10">
                    <input id="share_name" class="form-control" ng-model="share.name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="share_group" class="col-sm-2 control-label">所属分组</label>

                  <div class="col-sm-10">
                    <input class="form-control" id="share_group" readonly ng-model="share.groupName" ng-click="addGroups();">
                  </div>

                </div>

                <div class="form-group">
                  <label for="share_group" class="col-sm-2 control-label">标签</label>

                  <div class="col-sm-10" style="line-height: 34px;">
                    <span class="group-lable">
                      <a href="javascript:void(0);" id="showTagBox" ng-click="showTagBox();">添加标签</a>
                    </span>
                    <div class="alert alert-info alert-dismissible group-lable" style="margin-bottom: auto;" ng-repeat="item in share.tagNames">
                      <span ng-bind="item"></span>
                      <span class="close" ng-click="delTagName(item)">×</span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="share_group" class="col-sm-2 control-label">描述</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" maxlength="255" rows="3" id="describe" placeholder="请输入描述内容..." style="border-radius: 0" ng-model="share.describe"></textarea>
                  </div>
                </div>

                <div id="hide_box" style="display: none;">
                  <div id="groupTree"></div>
                  <div id="tagBox">
                    <div class="form-horizontal">
                      <div class="form-group" style="margin-right: 0;margin-left: 0;">
                        <div class="col-sm-12">
                          <input id="share_name" class="form-control" ng-model="tagText">
                        </div>
                      </div>
                      <div class="form-group" style="margin-right: 15px;margin-left: 15px;">
                        <div class="alert alert-info alert-dismissible group-lable" style="margin-bottom: 8px;" ng-repeat="item in tagTop">
                          <span ng-bind="item.tagName"></span>
                          <span class="close" ng-click="addTagName(item.tagName)"><i class="fa fa-plus"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ./setTags -->

          <section class="ng-cloak">
            <div class="row" style="border-top: 1px solid #eee; padding-top: 10px;margin-top: 10px">
              <div class="col-xs-12">
                <div class="pull-right">
                  <button class="btn btn-default" ng-if="tabs.indexOf(tab) > 0" ng-click="back()">上一步</button>
                  <button class="btn btn-default" ng-if="tabs.indexOf(tab)+1 != tabs.length" ng-click="next()">下一步</button>
                  <button class="btn btn-success" ng-if="tabs.indexOf(tab)+1 == tabs.length" ng-click="save()">保&nbsp;&nbsp;存</button>
                </div>
              </div>
            </div>
          </section>

        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>

</section>
<script type="text/javascript">
  var userName = '<?=Yii::$app->user->identity->username?>';
  var GroupList = <?=json_encode($GroupList)?>;
  var TagTop = <?=json_encode($TagTop)?>;
</script>
<script src="/plugins/xlsx/xlsx.full.min.js"></script>
<script src="/js/controllers/share-add.js"></script>









































