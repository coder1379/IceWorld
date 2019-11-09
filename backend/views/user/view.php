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
            'login_password',
            'mobile',
            'qq',
            'truename',
            'account',
            'email:email',
            'wx_openid',
            'wx_unionid',
            'add_time',
            'reg_ip',
            'last_login_ip',
            'token',
            'token_out_time',
            'last_login_time',
            'head_portrait',
            'birthday',
['label'=>'性别','value'=>$model->sexPredefine[$model->sex]],
['label'=>'邀请人','value'=>@$model->inviterUserRecord->name],
['label'=>'添加人','value'=>@$model->addAdminRecord->nickname],
            'introduce',
['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
['label'=>'类型','value'=>$model->typePredefine[$model->type]],
        ],
    ]) ?>

</div>
