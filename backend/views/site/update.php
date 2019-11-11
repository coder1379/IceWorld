<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\site\SiteModel */

$this->title = '修改网站内容：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '网站内容', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont site-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
