<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\user\UserModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'login_password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qq')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'truename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wx_openid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wx_unionid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_login_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token_out_time')->textInput() ?>

    <?= $form->field($model, 'last_login_time')->textInput() ?>

    <?= $form->field($model, 'head_portrait')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->textInput() ?>

 <?= $form->field($model, 'sex')->label('性别')->dropDownList($model->sexPredefine,['prompt' => '请选择性别','options'=>[$model->sex=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'inviter_user_id')->label('邀请人')->dropDownList($model->getInviterUserRecordList(),['prompt' => '请选择邀请人','options'=>[$model->inviter_user_id=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'introduce')->textInput(['maxlength' => true]) ?>

 <?= $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态','options'=>[$model->status=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型','options'=>[$model->type=>['Selected'=>true]]]) ?>

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