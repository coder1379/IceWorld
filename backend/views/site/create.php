<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\site\SiteModel */

$this->title = '添加 网站内容';
$this->params['breadcrumbs'][] = ['label' => '网站内容', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont site-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
