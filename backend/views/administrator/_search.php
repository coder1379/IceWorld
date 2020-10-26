<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdministratorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="display:none;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

<?php echo $form->field($model, 'login_username') ?>

<?php echo $form->field($model, 'avatar') ?>

<?php echo $form->field($model, 'realname') ?>

<?php echo $form->field($model, 'nickname') ?>

<?php echo $form->field($model, 'mobile') ?>

<?php echo $form->field($model, 'remark') ?>

<?php echo $form->field($model, 'email') ?>

<?php echo $form->field($model, 'qq') ?>

<?php echo $form->field($model, 'wechat') ?>

<?php echo $form->field($model, 'company') ?>

<?php echo $form->field($model, 'login_password') ?>

<?php echo $form->field($model, 'token') ?>

<?php echo $form->field($model, 'add_time') ?>

<?php echo $form->field($model, 'show_sort') ?>

 <?php echo $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型']) ?>

 <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态']) ?>

<?php echo $form->field($model, 'last_login_time') ?>

<?php echo $form->field($model, 'last_login_ip') ?>

 <?php echo $form->field($model, 'online')->label('在线状态')->dropDownList($model->onlinePredefine,['prompt' => '请选择在线状态']) ?>

<?php echo $form->field($model, 'is_admin') ?>

    <div class="form-group">
        <button type="submit" class="btn btn-success" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>
    </div>

    <?php ActiveForm::end();?>

</div>
