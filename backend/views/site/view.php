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
//            'telphone',
//            'mobile',
//            'qq',
            'email:email',
['attribute' => 'img_url','label' => 'logo','format' => 'raw','value'  => Html::a(Html::img($model->img_url,['class'=>'backend-view-img']),$model->img_url,['target' => '_blank']),],
['attribute' => 'cover','label' => '封面','format' => 'raw','value'  => Html::a(Html::img($model->cover,['class'=>'backend-view-img']),$model->cover,['target' => '_blank']),],
['attribute' => 'content','label' => '详细介绍','format' => 'raw','value'=>'<iframe srcdoc=\''.$model->content.'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],
['attribute' => 'about_us','label' => '关于我们','format' => 'raw','value'=>'<iframe srcdoc=\''.$model->about_us.'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],
            'add_time',
['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
['label'=>'用户','value'=>@$model->userRecord->name],
//['label'=>'类型','value'=>$model->typePredefine[$model->type]],
        ],
    ]) ?>

</div>
