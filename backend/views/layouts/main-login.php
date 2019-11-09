<?php
use yii\helpers\Html;
$staticFilePath=empty(Yii::$app->params["staticFilePath"])!=true?Yii::$app->params["staticFilePath"]:'';
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <?= Html::csrfMetaTags() ?>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/html5shiv.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/respond.min.js"></script>
    <![endif]-->
    <link href="<?= $staticFilePath ?>/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $staticFilePath ?>/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
    <link href="<?= $staticFilePath ?>/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="<?= $staticFilePath ?>/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/layer/2.4/layer.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/static/js/common.js"></script>
    <title>登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
