<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\systemconfig\SystemConfigModel */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="system-config-model-form">

    <?php $form = ActiveForm::begin(['id'=>'create_update_active_form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_val')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'add_time')->textInput(); ?>

    <?php //echo $form->field($model, 'update_time')->textInput(); ?>

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