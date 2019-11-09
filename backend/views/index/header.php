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
    <title>后台管理系统</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <style>
        .loading {
            width: auto;
        }
    </style>
</head>
<body>
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo Yii::$app->urlManager->createUrl('index/index'); ?>">后台管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="<?php echo Yii::$app->urlManager->createUrl('index/index'); ?>">后台管理系统</a> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li>
                        <?php echo Yii::$app->session["admin.rolename"]; ?>
                    </li>
                    <li class="dropDown dropDown_hover">
                        <a href="#" class="dropDown_A"><?php echo Yii::$app->session["admin.nickname"]; ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li>
                                <a href="#">个人信息</a>
                            </li>
                            <li>
                                <a href="<?php echo Yii::$app->urlManager->createUrl('index/logout'); ?>">退出</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2">
        <?php if(empty($menuList)!=true){
            foreach($menuList as $ml){
                if(empty($ml['nextMenu'])==true){
                    continue;
                }
                ?>
                <dl id="menu-product"><dt><i class="Hui-iconfont"><?php echo $ml['icon']; ?></i> <?php echo $ml['name']; ?><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt><dd><ul>
                 <?php 
                      foreach ($ml['nextMenu'] as $nl) {
                          ?>
                          <li><a data-href="<?php echo Yii::$app->urlManager->createUrl($nl['controller'] . '/' . $nl['c_action']); ?>" data-title="<?php echo $nl['name']; ?>" href="javascript:void(0)"><?php echo $nl['name']; ?></a></li>
                          <?php
                      }     
                ?>
              </ul></dd></dl>
        <?php
            }
        } ?>
    </div>
</aside>
<div class="dislpayArrow hidden-xs">
    <a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a>
</div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active">
                    <span title="我的桌面" data-href="<?php echo Yii::$app->urlManager->createUrl('index/welcome'); ?>">我的桌面</span><em></em>
                </li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
            <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a>
            <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="<?php echo Yii::$app->urlManager->createUrl('index/welcome'); ?>"></iframe>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?= $staticFilePath ?>/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?= $staticFilePath ?>/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="<?= $staticFilePath ?>/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="<?= $staticFilePath ?>/static/h-ui.admin/js/H-ui.admin.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
