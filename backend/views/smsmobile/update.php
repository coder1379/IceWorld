<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\sms\SmsMobileModel */

$this->title = '修改短信记录：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '短信记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont sms-mobile-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
