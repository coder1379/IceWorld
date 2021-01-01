<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\sms\SmsMobileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="display:none;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

<?php echo $form->field($model, 'name') ?>

<?php echo $form->field($model, 'object_id') ?>

 <?php echo $form->field($model, 'object_type')->label('短信对象类型')->dropDownList($model->objectTypePredefine,['prompt' => '全部']) ?>

 <?php //echo $form->field($model, 'user_id')->label('接收用户')->dropDownList($model->getUserRecordList(),['prompt' => '全部']) ?>

<?php echo $form->field($model, 'area_code') ?>

<?php echo $form->field($model, 'mobile') ?>

<?php echo $form->field($model, 'other_mobiles') ?>

<?php echo $form->field($model, 'content') ?>

<?php echo $form->field($model, 'params_json') ?>

<?php echo $form->field($model, 'send_time') ?>

<?php echo $form->field($model, 'send_num') ?>

 <?php echo $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '全部']) ?>

 <?php echo $form->field($model, 'send_type')->label('发送类型')->dropDownList($model->sendTypePredefine,['prompt' => '全部']) ?>

 <?php echo $form->field($model, 'sms_type')->label('短信渠道')->dropDownList($model->smsTypePredefine,['prompt' => '全部']) ?>

<?php echo $form->field($model, 'template') ?>

<?php echo $form->field($model, 'feedback') ?>

<?php echo $form->field($model, 'remark') ?>

<?php echo $form->field($model, 'add_time') ?>

 <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '全部']) ?>

    <div class="form-group">
        <button type="submit" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>
    </div>

    <?php ActiveForm::end();?>

</div>
