<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->context->layout = false;
if ($exception->statusCode == 404) {
    $msg = '您访问的页面不存在哦！';
    $this->title = '无效请求';
} elseif ($exception->statusCode == 403) {
    $msg = '您的请求越过了您的权限！';
    $this->title = '越权请求';
} else {
    $msg = '发生错误了！';
    $this->title = $msg;
    if (empty($exception->statusCode)) {
        $exception->statusCode = 500;
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Viewport metatags -->
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="320" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<link rel="stylesheet" type="text/css" href="/plugins/error/css/dandelion.css"  media="screen" />

<title><?=$this->title?></title>

</head>

<body>

    <!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
    <div id="da-wrapper" class="fluid">

        <!-- Content -->
        <div id="da-content">

            <!-- Container -->
            <div class="da-container clearfix">

                <div id="da-error-wrapper" style="text-align: center;">

                    <div id="da-error-pin"></div>
                    <div id="da-error-code">
                        error <span><?=$exception->statusCode?></span>
                    </div>

                    <h1 class="da-error-heading"><?=$msg?></h1>
                    <p>
                        如有疑问请联系请联系我们，谢谢！
                    </p>
                    <em>
                        <!-- <a href="http://www.hoohoolab.com/" target="_blank">虎特信息科技(上海)有限公司</a> -->
                    </em>
                    <br/><br/><br/><br/>
                    <p>
                        <a href="javascript:history.back();">返回上一页</a>
                    </p>

                </div>
            </div>
        </div>

    </div>

</body>
</html>


