<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\log\LogModel */

$this->title = '修改系统日志：' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '系统日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont log-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
