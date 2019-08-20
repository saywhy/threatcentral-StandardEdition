<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
$this->context->layout = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?=$this->title?>
    </title>
    <meta name="description" content="particles.js is a lightweight JavaScript library for creating particles.">
    <meta name="author" content="Vincent Garreau" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" media="screen" href="/plugins/particles/style.css">
</head>
<body>



    <!-- particles.js container -->
    <div class="content">

        <div class="header">
            <label>上汽集团威胁情报系统</label>
        </div>
        <div class="body">
            <div class="site-login">
                <div class="row">
                    <div class="col-lg-5">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']);?>

                        <?=$form->field($model, 'username', ['labelOptions' => ['label' => null]])
->textInput([
    'autofocus' => true,
    'placeholder' => '用户名',
])?>

                        <?=$form->field($model, 'password', ['labelOptions' => ['label' => null]])->passwordInput([
    'placeholder' => '密码',
])?>
                            <div class="form-group">
                                <?=Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button'])?>
                            </div>

                            <?php ActiveForm::end();?>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="particles-js"></div>


    <!-- scripts -->
    <script src="/plugins/particles/particles.min.js"></script>
    <script src="/plugins/particles/app.js"></script>


</body>
</html>