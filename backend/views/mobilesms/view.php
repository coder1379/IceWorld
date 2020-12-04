<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\message\MobileSmsModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '短信记录', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobile-sms-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'object_id',
['label'=>'短信对象类型','value'=>@$model->objectTypePredefine[$model->object_type]],
['label'=>'接收用户','value'=>@$model->userRecord->name],
            'mobile',
            'other_mobiles:ntext',
            'content:ntext',
            'params_json:ntext',
['label'=>'发送时间','value'=>(($model->send_time)>0?date('Y-m-d H:i:s',$model->send_time):'')],
            'send_num',
['label'=>'类型','value'=>@$model->typePredefine[$model->type]],
['label'=>'发送类型','value'=>@$model->sendTypePredefine[$model->send_type]],
['label'=>'短信渠道','value'=>@$model->smsTypePredefine[$model->sms_type]],
            'template:ntext',
            'feedback:ntext',
            'remark:ntext',
['label'=>'添加时间','value'=>(($model->add_time)>0?date('Y-m-d H:i:s',$model->add_time):'')],
['label'=>'状态','value'=>@$model->statusPredefine[$model->status]],
        ],
    ]) ?>

</div>
