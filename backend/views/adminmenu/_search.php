<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminMenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

<?php echo $form->field($model, 'name') ?>

<?php echo $form->field($model, 'controller') ?>

<?php echo $form->field($model, 'c_action') ?>

 <?php //echo $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型']) ?>

 <?php //echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态']) ?>

<?php //echo $form->field($model, 'icon') ?>

 <?php echo $form->field($model, 'm_level')->label('等级')->dropDownList($model->levelPredefine,['prompt' => '请选择等级']) ?>

<?php //echo $form->field($model, 'add_time') ?>

<?php //echo $form->field($model, 'show_sort') ?>

    <div class="form-group">
        <button type="submit" class="btn btn-success" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>
    </div>

    <?php ActiveForm::end();?>

</div>
