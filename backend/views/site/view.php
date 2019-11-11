<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\site\SiteModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '网站内容', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'introduce',
            'seo_title',
            'seo_keywords',
            'seo_description',
            'telphone',
            'mobile',
            'qq',
            'email:email',
            'add_time',
['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
['label'=>'类型','value'=>$model->typePredefine[$model->type]],
        ],
    ]) ?>

</div>
