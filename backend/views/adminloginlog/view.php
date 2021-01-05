<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminLoginLogModel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '管理员登录日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-login-log-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
['label'=>'名称','value'=>@$model->adminRecord->nickname],
['label'=>'登录类型','value'=>@$model->typePredefine[$model->type]],
['label'=>'登录时间','value'=>(($model->add_time)>0?date('Y-m-d H:i:s',$model->add_time):'')],
            'ip',
            'device_desc',
//['label'=>'状态','value'=>@$model->statusPredefine[$model->status]],
        ],
    ]) ?>

</div>
