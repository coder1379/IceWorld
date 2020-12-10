<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\user\UserModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
//            'mobile',
//            'username',
//            'login_password',
['label'=>'状态','value'=>@$model->statusPredefine[$model->status]],
['label'=>'用户类型','value'=>@$model->typePredefine[$model->type]],
//            'level',
//            'realname',
//            'email:email',
['attribute' => 'avatar','label' => '头像','format' => 'raw','value'  => Html::a(Html::img($model->avatar,['class'=>'backend-view-img']),$model->avatar,['target' => '_blank']),],
            'introduce:ntext',
['label'=>'性别','value'=>@$model->sexPredefine[$model->sex]],
['label'=>'生日','value'=>(($model->birthday)>0?date('Y-m-d H:i:s',$model->birthday):'')],
            'district',
            'title',
//            'token',
['label'=>'注册时间','value'=>(($model->add_time)>0?date('Y-m-d H:i:s',$model->add_time):'')],
        ],
    ]) ?>

</div>
