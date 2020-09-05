<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\systemconfig\SystemConfigModel */

$this->title = '添加 系统配置文件';
$this->params['breadcrumbs'][] = ['label' => '系统配置文件', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont system-config-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
