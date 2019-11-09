<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\mobilesms\MobileSmsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="display:none;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

<?php echo $form->field($model, 'object_id') ?>

 <?php echo $form->field($model, 'object_type')->label('消息对象类型')->dropDownList($model->objectTypePredefine,['prompt' => '请选择消息对象类型']) ?>

<?php echo $form->field($model, 'access_ip') ?>

<?php echo $form->field($model, 'mobile') ?>

<?php echo $form->field($model, 'contents') ?>

<?php echo $form->field($model, 'params_json') ?>

 <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态']) ?>

<?php echo $form->field($model, 'add_time') ?>

<?php echo $form->field($model, 'send_time') ?>

<?php echo $form->field($model, 'send_number') ?>

 <?php echo $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型']) ?>

 <?php echo $form->field($model, 'send_type')->label('发送类型')->dropDownList($model->sendTypePredefine,['prompt' => '请选择发送类型']) ?>

 <?php echo $form->field($model, 'sms_type')->label('消息类型')->dropDownList($model->smsTypePredefine,['prompt' => '请选择消息类型']) ?>

<?php echo $form->field($model, 'template') ?>

<?php echo $form->field($model, 'feedback') ?>

<?php echo $form->field($model, 'remark') ?>

    <div class="form-group">
        <button type="submit" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>
    </div>

    <?php ActiveForm::end();?>

</div>
