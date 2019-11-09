<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\mobilesms\MobileSmsModel */

$this->title = '修改手机短信：' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '手机短信', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont mobile-sms-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
