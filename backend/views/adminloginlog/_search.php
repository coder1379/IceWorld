<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminLoginLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="display:none;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

 <?php //echo $form->field($model, 'admin_id')->label('名称')->dropDownList($model->getAdminRecordList(),['prompt' => '全部']) ?>

 <?php echo $form->field($model, 'type')->label('登录类型')->dropDownList($model->typePredefine,['prompt' => '全部']) ?>

<?php echo $form->field($model, 'add_time') ?>

<?php echo $form->field($model, 'ip') ?>

<?php echo $form->field($model, 'device_desc') ?>

 <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '全部']) ?>

    <input type="hidden" id="export_file_flag" name="export_file_flag" value="0">
    <div class="form-group">
        <button type="button" onclick="indexSearchSubmitButton('export_file_flag','w0')" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>

        <button style="display: none;" type="button" onclick="exportFileSubmitButton('export_file_flag','w0')" class="btn btn-warning radius export_file_submit_btn" ><i class="Hui-iconfont">&#xe644;</i> 导出</button>
    </div>

    <?php ActiveForm::end();?>

</div>
