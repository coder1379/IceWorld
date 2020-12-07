<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\sms\SmsMobileModel */

$this->title = '添加 短信记录';
$this->params['breadcrumbs'][] = ['label' => '短信记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont sms-mobile-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
