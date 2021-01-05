<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminLoginLogModel */

$this->title = '添加 管理员登录日志';
$this->params['breadcrumbs'][] = ['label' => '管理员登录日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont admin-login-log-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
