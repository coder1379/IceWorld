<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdministratorModel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '管理员', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="administrator-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login_username',
            //'avatar',
            //'realname',
            'nickname',
            'mobile',
            'remark',
            'email:email',
            //'qq',
            //'wechat',
            //'company',
['label'=>'角色','value'=>@$model->adminRoleRecord->name],
//['label'=>'分组','value'=>@$model->adminGroupRecord->name],
//['label'=>'城市','value'=>@$model->areaRecord->name],
            //'login_password',
            //'token',
['label'=>'添加人','value'=>@$model->addAdminRecord->nickname],
            'add_time',
            //'show_sort',
//['label'=>'类型','value'=>$model->typePredefine[$model->type]],
['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
            'last_login_time',
            'last_login_ip',
//['label'=>'在线状态','value'=>$model->onlinePredefine[$model->online]],
            //'is_admin',
        ],
    ]) ?>

</div>
