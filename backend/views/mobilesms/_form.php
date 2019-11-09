<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\mobilesms\MobileSmsModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mobile-sms-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'object_id')->textInput() ?>

 <?= $form->field($model, 'object_type')->label('消息对象类型')->dropDownList($model->objectTypePredefine,['prompt' => '请选择消息对象类型','options'=>[$model->object_type=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'user_id')->label('用户')->dropDownList($model->getUserRecordList(),['prompt' => '请选择用户','options'=>[$model->user_id=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'access_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contents')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'params_json')->textInput(['maxlength' => true]) ?>

 <?= $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态','options'=>[$model->status=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'send_time')->textInput() ?>

    <?= $form->field($model, 'send_number')->textInput() ?>

 <?= $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型','options'=>[$model->type=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'send_type')->label('发送类型')->dropDownList($model->sendTypePredefine,['prompt' => '请选择发送类型','options'=>[$model->send_type=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'sms_type')->label('消息类型')->dropDownList($model->smsTypePredefine,['prompt' => '请选择消息类型','options'=>[$model->sms_type=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'template')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'feedback')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-primary radius' : 'btn btn-primary radius']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function(){
        // 防止重复提交
        $('form').on('beforeValidate', function (e) {
            $(':submit').attr('disabled', true).addClass('disabled');
        });
        $('form').on('afterValidate', function (e) {
            if (cheched = $(this).data('yiiActiveForm').validated == false) {
                $(':submit').removeAttr('disabled').removeClass('disabled');
            }
        });
        $('form').on('beforeSubmit', function (e) {
            $(':submit').attr('disabled', true).addClass('disabled');
        });
    });

</script>