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
    <?= Html::csrfMetaTags() ?>
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="<?= $staticFilePath ?>/favicon.ico" >
    <LINK rel="Shortcut Icon" href="<?= $staticFilePath ?>/favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/html5shiv.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?= $staticFilePath ?>/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?= $staticFilePath ?>/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="<?= $staticFilePath ?>/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="<?= $staticFilePath ?>/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="<?= $staticFilePath ?>/static/h-ui.admin/css/style.css" />
    <link href="<?= $staticFilePath ?>/static/css/elementui.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= $staticFilePath ?>/static/css/common.css?1" />
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/layer/2.4/layer.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/static/h-ui/js/H-ui.min.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/static/h-ui.admin/js/H-ui.admin.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/static/js/vue.min.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/static/js/elementui.min.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="<?= $staticFilePath ?>/static/js/common.js?2"></script>
    <title><?= Html::encode($this->title) ?></title>
    <style>
        .loading {
            width: auto;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

