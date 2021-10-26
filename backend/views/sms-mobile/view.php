<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\sms\SmsMobileModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '短信记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-mobile-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'object_id',
['attribute'=>'object_type','value'=>@$model->objectTypePredefine[$model->object_type]],
['attribute'=>'user_id','value'=>@$model->userRecord->name],
            'mobile',
            'other_mobiles:ntext',
            'content:ntext',
            'params_json:ntext',
['attribute'=>'send_time','value'=>(($model->send_time)>0?date('Y-m-d H:i:s',$model->send_time):'')],
            'send_num',
['attribute'=>'type','value'=>@$model->typePredefine[$model->type]],
['attribute'=>'send_type','value'=>@$model->sendTypePredefine[$model->send_type]],
['attribute'=>'sms_type','value'=>@$model->smsTypePredefine[$model->sms_type]],
            'template:ntext',
            'feedback:ntext',
            'remark:ntext',
['attribute'=>'add_time','value'=>(($model->add_time)>0?date('Y-m-d H:i:s',$model->add_time):'')],
['attribute'=>'status','value'=>@$model->statusPredefine[$model->status]],
        ],
    ]) ?>

</div>
