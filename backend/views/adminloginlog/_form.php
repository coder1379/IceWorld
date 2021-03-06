<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminLoginLogModel */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="admin-login-log-model-form">

    <?php $form = ActiveForm::begin(['id'=>'create_update_active_form']); ?>

    <?php echo $form->field($model, 'admin_id')->label('名称')->dropDownList($model->getAdminRecordList(),['options'=>[$model->admin_id=>['Selected'=>true]]]); ?>

    <?php echo $form->field($model, 'type')->label('登录类型')->dropDownList($model->typePredefine,['options'=>[$model->type=>['Selected'=>true]]]); ?>

    <?php //echo $form->field($model, 'add_time')->textInput(); ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_desc')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['options'=>[$model->status=>['Selected'=>true]]]); ?>

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