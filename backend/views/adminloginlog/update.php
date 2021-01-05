<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminLoginLogModel */

$this->title = '修改管理员登录日志：' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '管理员登录日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont admin-login-log-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
