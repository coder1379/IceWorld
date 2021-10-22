<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\site\SiteModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '网站设置', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'introduce:ntext',
            'seo_title',
            'seo_keywords',
            'seo_description:ntext',
            'telphone',
            'mobile',
            'qq',
            'email:email',
['attribute' => 'img_url','format' => 'raw','value'  => Html::a(Html::img($model->img_url,['class'=>'backend-view-img']),$model->img_url,['target' => '_blank']),],
['attribute' => 'cover','format' => 'raw','value'  => Html::a(Html::img($model->cover,['class'=>'backend-view-img']),$model->cover,['target' => '_blank']),],
['attribute' => 'content','format' => 'raw','value'=>'<iframe srcdoc=\''.$model->content.'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],
['attribute' => 'about_us','format' => 'raw','value'=>'<iframe srcdoc=\''.$model->about_us.'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],
['attribute'=>'add_time','value'=>(($model->add_time)>0?date('Y-m-d H:i:s',$model->add_time):'')],
['attribute'=>'status','value'=>@$model->statusPredefine[$model->status]],
//['attribute'=>'type','value'=>@$model->typePredefine[$model->type]],
        ],
    ]) ?>

</div>
