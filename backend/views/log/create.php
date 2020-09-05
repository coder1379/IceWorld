<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\log\LogModel */

$this->title = '添加 系统日志';
$this->params['breadcrumbs'][] = ['label' => '系统日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont log-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
