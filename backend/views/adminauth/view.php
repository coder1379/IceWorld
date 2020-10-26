<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminAuthModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '管理员权限', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-model-view">

   <!-- <h3><?/*= Html::encode($this->title) */?></h3>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'auth_flag',
['label'=>'上级','value'=>@$model->parentAdminAuthRecord->name],
            'other_auth_url',
['label'=>'类型','value'=>$model->typePredefine[$model->type]],
['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
['label'=>'添加人','value'=>@$model->addAdminRecord->nickname],
            'add_time',
            'show_sort',
        ],
    ]) ?>

</div>
