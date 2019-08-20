<?php
/* @var $this yii\web\View */

$this->title = '信息导入';
?>
<!-- Main content -->
<section class="content" ng-app="myApp">

    <style type="text/css">
    .yara_rule{
    padding: 10px 20px;
}
.btn_yara{
    min-width: 80px;
    height: 30px;
    line-height: 30px;
    padding: 0
}
.yara_span{
    display: inline-block;
    width: 100px;
}
    </style>
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <?php include 'nav.php';?>
                <div class="tab-content">
                    <!-- License-->
                    <div class="tab-pane active" id="infoImport" ng-controller="infoImportCtrl" ng-cloak>
                        <div class="yara_rule">
                            <h4 style="margin-bottom:10px;">URL/IP信息导入</h4>
                            <!-- <p ng-if="yara_file">
                                <span class="yara_span">文件大小:</span>
                                <span ng-bind="yara_data.file_size">212KB</span>
                            </p>
                            <p ng-if="yara_file">
                                <span class="yara_span">上次更新时间:</span>
                                <span ng-bind="yara_data.update_time">2018年06月12日 22:23:22</span>
                            </p> -->
                            <p ng-if="!yara_file">并未发现任何URL/IP信息文件，请先上传信息文件！</p>
                            <div style="margin-top: 20px">
                                <button class="btn btn-primary btn_yara" style="margin-right: 20px" ng-disabled="!yara_file"
                                    ng-click="download()">下&nbsp;载</button>
                                <button class="btn btn-primary btn_yara" ng-cloak style="margin-right: 50px" ng-click="yara_replace()"
                                    ng-bind="yara_file?'替 换':'上 传'"></button>
                                <button class="btn btn-primary btn_yara" ng-disabled="!yara_file" ng-click="del()">删&nbsp;除</button>
                            </div>
                            <div class="input-file" style="display: none">
                                <form id="upload" method="post" enctype="multipart/form-data">
                                    <input type="text" id="avatval" placeholder="请选择文件···" readonly="readonly" style="vertical-align: middle;" />
                                    <input type="file" name="file" id="avatar" />
                                    <button ng-click="upload()" class="btn btn-primary upload_button" id="avatsel1">上传文件</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="yara_rule">
                        <h4 style="margin-bottom:10px;">应用信息导入</h4>
                            <p ng-if="yara_file">
                                <span class="yara_span">文件大小:</span>
                                <span ng-bind="yara_data.file_size">212KB</span>
                            </p>
                            <p ng-if="yara_file">
                                <span class="yara_span">上次更新时间:</span>
                                <span ng-bind="yara_data.update_time">2018年06月12日 22:23:22</span>
                            </p>
                            <p ng-if="!yara_file">并未发现任何应用信息文件，请先上传应用信息文件！</p>
                            <div style="margin-top: 20px">
                                <button class="btn btn-primary btn_yara" style="margin-right: 20px" ng-disabled="!yara_file"
                                    ng-click="get_date('download')">下&nbsp;载</button>
                                <button class="btn btn-primary btn_yara" ng-cloak style="margin-right: 50px" ng-click="app_replace()"
                                    ng-bind="yara_file?'替 换':'上 传'"></button>
                                <button class="btn btn-primary btn_yara" ng-disabled="!yara_file" ng-click="del()">删&nbsp;除</button>
                            </div>
                            <div class="input-file" style="display: none">
                                <form id="upload_app" method="post" enctype="multipart/form-data">
                                    <input type="text" id="avatval_app" placeholder="请选择文件···" readonly="readonly" style="vertical-align: middle;" />
                                    <input type="file" name="file_app" id="avatar_app" />
                                    <button ng-click="upload_app()" class="btn btn-primary upload_button" id="avatsel1">上传文件</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- /.content -->


<script type="text/javascript" src="/js/controllers/infoImport.js"></script>