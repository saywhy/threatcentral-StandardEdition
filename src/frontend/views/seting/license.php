<?php
/* @var $this yii\web\View */

$this->title = '许可证';
?>
<!-- Main content -->
<section class="content" ng-app="myApp">

    <style type="text/css">
        .nav-tabs-custom {
            overflow: visible;
        }

        .btn_color:hover,
        .btn_color:active,
        .zeromodal-btn-primary,
        .btn_color,
        .btn:focus,
        .btn-primary {
            background-color: #0070FF;
            border-color: #0070FF;
            color: #fff;
        }

        .zeromodal-container {
            padding: 36px;
            border-radius: 8px;
        }

        .zeromodal-header {
            margin: 0;
            font-size: 18px;
            color: #333333;
            /* border-left: 3px solid #0070ff; */
            border-radius: 2px;
            height: 20px;
            font-weight: 500;
        }

        .zeromodal-body {
            font-size: 14px;
            color: #333;
            padding-top: 24px;
        }

        .zeromodal-title1 {
            padding: 0;
        }

        .alert_table_tr {
            height: 48px;
            line-height: 48px;
        }


.domain_table tr:nth-child(odd) {
  background: #eef6ff;
}

.domain_table tr:nth-child(even) {
  background: #fff;
}

.domain_table {
  width: 100%;
  table-layout: fixed;
}

.domain_table tr {
  height: 48px;
  line-height: 48px;
  padding-left: 26px;
}

.domain_table td,
.domain_table th {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  text-align: center;
}
    </style>
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="License" ng-controller="LicenseCtrl">
                        <section class="ng-cloak" ng-show="License.list">
                            <h4 class="seting-header" style="margin-bottom: -1px;">
                                <i class="fa fa-key"></i>
                                证书列表
                            </h4>
                            <div class="row">
                                <div class="col-sm-12" style="padding:0;">
                                    <table class="table  domain_table" style="border-bottom: 1px solid #f4f4f4;">
                                        <tr>
                                            <th style="text-align:center;width:80px;">序号</th>
                                            <th>序列号</th>
                                            <th>受保护机构</th>
                                            <th style="width: 180px;">授权时间</th>
                                            <th style="width: 180px;">授权到期时间</th>
                                            <th style="width: 150px;">许可证状态</th>
                                        </tr>
                                        <tr style="cursor: pointer;" class="alert_table_tr"
                                            ng-repeat="(SN, item) in License.list">
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
                                    <button type="button" class="btn btn_color" ng-click="online();">在线激活</button>
                                    <button type="button" class="btn dropdown-toggle btn_color" data-toggle="dropdown">
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
                                <div id="inputSN">
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
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="/js/controllers/License.js"></script>
