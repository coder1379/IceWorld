<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\sms\SmsMobileModel */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="sms-mobile-model-form">

    <?php $form = ActiveForm::begin(['id'=>'create_update_active_form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'object_id')->textInput() ?>

    <?php echo $form->field($model, 'object_type')->label('短信对象类型')->dropDownList($model->objectTypePredefine,['options'=>[$model->object_type=>['Selected'=>true]]]); ?>

    <?php echo $form->field($model, 'user_id')->label('接收用户')->dropDownList($model->getUserRecordList(),['options'=>[$model->user_id=>['Selected'=>true]]]); ?>

    <?php echo $form->field($model, 'area_num')->textInput(); ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'other_mobiles')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php echo $form->field($model, 'content')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php echo $form->field($model, 'params_json')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php //echo $form->field($model, 'send_time')->textInput(); ?>

    <?php //echo $form->field($model, 'send_num')->textInput(); ?>

    <?php echo $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['options'=>[$model->type=>['Selected'=>true]]]); ?>

    <?php echo $form->field($model, 'send_type')->label('发送类型')->dropDownList($model->sendTypePredefine,['options'=>[$model->send_type=>['Selected'=>true]]]); ?>

    <?php echo $form->field($model, 'sms_type')->label('短信渠道')->dropDownList($model->smsTypePredefine,['options'=>[$model->sms_type=>['Selected'=>true]]]); ?>

    <?php //echo $form->field($model, 'template')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php //echo $form->field($model, 'feedback')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php echo $form->field($model, 'remark')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php //echo $form->field($model, 'add_time')->textInput(); ?>

    <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['options'=>[$model->status=>['Selected'=>true]]]); ?>

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

    //禁用input回车提交
    $(document).on("keydown","#create_update_active_form input[type='text']", function(event) {
        return event.key != "Enter";
    });

    

</script>