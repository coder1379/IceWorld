<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\mobilesms\MobileSmsModel */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '手机短信', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobile-sms-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'object_id',
['label'=>'消息对象类型','value'=>$model->objectTypePredefine[$model->object_type]],
['label'=>'用户','value'=>@$model->userRecord->name],
            'access_ip',
            'mobile',
            'contents:ntext',
            'params_json',
['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
            'add_time',
            'send_time',
            'send_number',
['label'=>'类型','value'=>$model->typePredefine[$model->type]],
['label'=>'发送类型','value'=>$model->sendTypePredefine[$model->send_type]],
['label'=>'消息类型','value'=>$model->smsTypePredefine[$model->sms_type]],
            'template',
            'feedback',
            'remark',
        ],
    ]) ?>

</div>
