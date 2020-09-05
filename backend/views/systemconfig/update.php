<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\systemconfig\SystemConfigModel */

$this->title = '修改系统配置文件：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '系统配置文件', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont system-config-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
