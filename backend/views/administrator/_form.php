<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdministratorModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="administrator-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login_username')->textInput(['maxlength' => true]) ?>

  <!--  <?= $form->field($model, 'avatar')->textInput(['maxlength' => true]) ?>-->

    <!--<?= $form->field($model, 'realname')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

   <!-- <?= $form->field($model, 'qq')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wechat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>-->

 <?= $form->field($model, 'role_id')->label('角色')->dropDownList($model->getAdminRoleRecordList(),['prompt' => '请选择角色','options'=>[$model->role_id=>['Selected'=>true]]]) ?>

 <!--<?= $form->field($model, 'group_id')->label('分组')->dropDownList($model->getAdminGroupRecordList(),['prompt' => '请选择分组','options'=>[$model->group_id=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'area_id')->label('城市')->dropDownList($model->getAreaRecordList(),['prompt' => '请选择城市','options'=>[$model->area_id=>['Selected'=>true]]]) ?>-->

    <?= $form->field($model, 'login_password')->passwordInput(['maxlength' => true]) ?>

   <!-- <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'show_sort')->textInput() ?>

 <?= $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型','options'=>[$model->type=>['Selected'=>true]]]) ?>-->

 <?= $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态','options'=>[$model->status=>['Selected'=>true]]]) ?>

    <!--<?= $form->field($model, 'last_login_time')->textInput() ?>

    <?= $form->field($model, 'last_login_ip')->textInput(['maxlength' => true]) ?>

 <?= $form->field($model, 'online')->label('在线状态')->dropDownList($model->onlinePredefine,['prompt' => '请选择在线状态','options'=>[$model->online=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'is_admin')->textInput() ?>-->

    <?= $form->field($model, 'remark')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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