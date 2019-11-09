<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\adminauth\AdminAuthModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-auth-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型','options'=>[$model->type=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_flag')->textInput(['maxlength' => true]) ?>

 <?= $form->field($model, 'parent_id')->label('上级')->dropDownList($model->getParentAdminAuthRecordList(),['options'=>[$model->parent_id=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'other_auth_url')->textarea(['rows'=>3]) ?>

 <?= $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态','options'=>[$model->status=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'show_sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success radius' : 'btn btn-primary radius']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function adminAuthTypeChange() {
        if($("#adminauthmodel-type").val()==2){
            $(".field-adminauthmodel-parent_id").show();
        }else if($("#adminauthmodel-type").val()==1){
            $("#adminauthmodel-parent_id").val(0);
            $(".field-adminauthmodel-parent_id").hide();
        }
    }

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

        $("#adminauthmodel-type").change(function() {
            adminAuthTypeChange();
        });

        adminAuthTypeChange();
    });

</script>