<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;

AppAsset::register($this);

// function isActive($urls)
// {
//     $url = explode('/', Yii::$app->request->getUrl())[2];

//     if (in_array($url, $urls)) {
//         return 'active';
//     } else {
//         return '';
//     }
// }
function isActive($urls)
{
    $url = Yii::$app->request->getUrl();
    if (in_array($url, $urls)) {
        return 'active';
    } else {
        return '';
    }
}

function getPath($path)
{
    $url = explode('?', Yii::$app->request->getUrl())[0];
    $url = rtrim($url, '/');
    if ($url == $path) {
        return 'javascript:void(0);';
    } else {
        return $path;
    }

}

// $bodyClassName = empty($_COOKIE['bodyClassName']) ? 'hold-transition skin-blue sidebar-mini' : $_COOKIE['bodyClassName'];

?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>上汽集团威胁情报系统 <?=$this->title?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/font-awesome-4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/plugins/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/dist/css/skins/_all-skins.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="/plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <link rel="stylesheet" href="/css/zeroModal.css">

  <script type="text/javascript" src="/plugins/angular/angular.min.js"></script>
  <script src="/js/controllers/news.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->


  <link rel="stylesheet" href="/plugins/iCheck/minimal/_all.css">

  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/common.css">
  <link rel="stylesheet" href="/plugins/ztree/zTreeStyle.css">
   <!-- <link rel="stylesheet" href="/plugins/switch/bootstrap-switch.css"> -->

</head>
<body class="hold-transition skin-blue sidebar-mini" >
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo" style="background-color: #374051;height:64px">
      <span class="logo-lg" style="line-height: 62px;"><img src="/images/shangqi_logo.png" style="height: 38px;width:112px"></span>
    </a>
    <nav class="navbar navbar-static-top" style=" background-color: #374051; ">
      <div class="navbar-custom-menu" style="float: left;  ">
        <ul class="nav navbar-nav">
          <?php include 'nav.php';?>
        </ul>
      </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- <?php include 'self.php';?>
          <?php include 'news.php';?> -->
          <?php include 'PersonalCenter.php';?>
        </ul>
      </div>
    </nav>
  </header>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: #F8F8F8;">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="height: 72px;background-color: #fff;padding:0;">
      <h1 style="font-size: 20px;color:#333;line-height:72px;padding-left:48px;">
        <?=$this->title?>
        <small></small>
      </h1>
      <ol class="breadcrumb" style="padding:0;padding-right:48px;top:0; font-size: 16px;color: #999999; line-height: 72px;">
        <?php if (isActive(['/site/index']) == 'active') {?>
        <li><a href="/site/index"><i class="fa fa-home"></i>首页</a></li>
        <?php }?>
        <?php if (isActive(['/search/index']) == 'active') {?>
        <li><a href="/search/index'"><i class="fa fa-podcast"></i>情报</a></li>
        <?php }?>
        <?php if (isActive(['/agent/index']) == 'active') {?>
        <li><a href="/agent/index"><i class="fa fa-podcast"></i>情报</a></li>
        <?php }?>
        <?php if (isActive(['/share/index']) == 'active') {?>
        <li><a href="/share/index"><i class="fa fa-podcast"></i>情报</a></li>
        <?php }?>
        <?php if (isActive(['/intelligence/source-management']) == 'active') {?>
        <li><a href="/intelligence/source-management"><i class="fa fa-podcast"></i>情报</a></li>
        <?php }?>
        <?php if (isActive(['/search/apt-lib']) == 'active') {?>
        <li><a href="/search/apt-lib"><i class="fa fa-podcast"></i>情报</a></li>
        <?php }?>
        <?php if (isActive(['/assets/asset-management']) == 'active') {?>
        <li><a href="/assets/asset-management"><i class="fa fa-database"></i>资产</a></li>
        <?php }?>
        <?php if (isActive(['/assets/asset-risky']) == 'active') {?>
        <li><a href="/assets/asset-risky"><i class="fa fa-database"></i>资产</a></li>
        <?php }?>
        <?php if (isActive(['/alert/index']) == 'active') {?>
        <li><a href="/alert/index"><i class="fa fa-heartbeat"></i>预警</a></li>
        <?php }?>
        <?php if (isActive(['/alert/loophole']) == 'active') {?>
        <li><a href="/alert/loophole"><i class="fa fa-heartbeat"></i>预警</a></li>
        <?php }?>
        <?php if (isActive(['/alert/darknet']) == 'active') {?>
        <li><a href="/alert/darknet"><i class="fa fa-heartbeat"></i>预警</a></li>
        <?php }?>
        <?php if (isActive(['/report/index']) == 'active') {?>
        <li><a href="/report/index"><i class="fa fa-area-chart"></i>报表</a></li>
        <?php }?>
        <?php if (isActive(['/report/send']) == 'active') {?>
        <li><a href="/report/send"><i class="fa fa-area-chart"></i>报表</a></li>
        <?php }?>
        <?php if (isActive(['/seting/network']) == 'active') {?>
        <li><a href="/seting/network"><i class="fa fa-cog"></i>设置</a></li>
        <?php }?>
        <?php if (isActive(['/seting/systemnotice']) == 'active') {?>
        <li><a href="/seting/systemnotice"><i class="fa fa-cog"></i>设置</a></li>
        <?php }?>
        <?php if (isActive(['/seting/centralmanager']) == 'active') {?>
        <li><a href="/seting/centralmanager"><i class="fa fa-cog"></i>设置</a></li>
        <?php }?>
        <?php if (isActive(['/seting/user']) == 'active') {?>
        <li><a href="/seting/user"><i class="fa fa-cog"></i>设置</a></li>
        <?php }?>
        <?php if (isActive(['/seting/log']) == 'active') {?>
        <li><a href="/seting/log"><i class="fa fa-cog"></i>设置</a></li>
        <?php }?>




        <li class="active"><?=$this->title?></li>
      </ol>
    </section>

    <!-- Main content -->
    <?=$content?>
    <!-- /.content -->
    <div class="hoohoolab-footer">
      <!-- <span>&copy; 2017 虎特信息科技(上海)有限公司 版权所有</span> -->
      <span></span>
    </div>
  <!-- /.content-wrapper -->
  <!--
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>版本</b> 2.1.1
    </div>
    <strong>HooHooLab &copy; 2017 <a href="http://www.hoohoolab.com/" target="_blank">虎特信息科技(上海)有限公司</a>.</strong> All rights
    reserved.
  </footer>
  -->


  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <!-- <div class="control-sidebar-bg"></div> -->
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<!-- <script src="/js/jquery-ui.min.js"></script> -->
<script src="/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="/bootstrap/js/bootstrap.min.js"></script>

<!-- Sparkline -->
<script src="/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/plugins/knob/jquery.knob.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/plugins/fastclick/fastclick.js"></script>
<!-- date-range-picker -->
<script src="/plugins/daterangepicker/moment.min.js"></script>
<script src="/plugins/daterangepicker/moment-zh.js"></script>
<script src="/plugins/daterangepicker/daterangepicker.js"></script>
<!-- zeroModal -->
<script src="/js/zeroModal.min.js"></script>
<!-- 引入图标 -->
<script src="/js/iconfont.js"></script>
<!-- Fileupload -->
<!-- <script src="/js/jquery-ui.min.js"></script> -->
<script src="/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery.fileupload.js"></script>
<!-- Chart.js -->
<script src="/plugins/chartjs/Chart.min.2.5.js"></script>
<!-- canvasjs.js -->
<script src="/plugins/canvasjs-1.9.8/canvasjs.min.js"></script>
<!-- bootstrap-treeview -->
<script src="/js/bootstrap-treeview.js"></script>
<!-- echarts.min -->
<script src="/plugins/echarts/echarts.min.js"></script>
<!-- <script src="/plugins/echarts/echarts.js"></script> -->
<!-- DataTables -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- autoTextarea -->
<script src="/plugins/autoTextarea/autoTextarea.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/app.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<script src="/js/up.js"></script>
<script src="/js/wangEditor.min.js"></script>
<script src="/plugins/angular-sanitize/angular-sanitize.min.js"></script>
<script src="/plugins/ztree/jquery.ztree.all.js"></script>
<script src="/plugins/ztree/jquery.ztree.exhide.js"></script>

<!-- <script src="/plugins/pdf/html2canvas.js"></script> -->
<!-- <script src="/plugins/pdf/jspdf_debug.js"></script> -->
<!-- <script src="/plugins/switch/bootstrap-switch.min.js"></script> -->

</body>
</html>




