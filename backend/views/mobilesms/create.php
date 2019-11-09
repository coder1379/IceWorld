<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\mobilesms\MobileSmsModel */

$this->title = '添加 手机短信';
$this->params['breadcrumbs'][] = ['label' => '手机短信', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont mobile-sms-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
