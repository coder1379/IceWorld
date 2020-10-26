<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminAuthModel */

$this->title = '修改管理员权限：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '管理员权限', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont admin-auth-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
