<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\sms\SmsMobileSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

<?php echo $form->field($model, 'name') ?>

<?php echo $form->field($model, 'object_id') ?>

 <?php echo $form->field($model, 'object_type')->dropDownList($model->objectTypePredefine,['prompt' => '全部']) ?>

 <?php //echo $form->field($model, 'user_id')->dropDownList($model->getUserRecordList(),['prompt' => '全部']) ?>

<?php echo $form->field($model, 'area_code') ?>

<?php echo $form->field($model, 'mobile') ?>

<?php echo $form->field($model, 'other_mobiles') ?>

<?php echo $form->field($model, 'content') ?>

<?php echo $form->field($model, 'params_json') ?>

                        <div>
                            <?php echo $form->field($model, 'send_time_search_start_val')->textInput(['onclick'=>"WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd',maxDate:new Date()})",'readonly'=>'readonly','placeholder'=>'选择时间'])->label('发送时间开始') ?>

<?php echo $form->field($model, 'send_time_search_end_val')->textInput(['onclick'=>"WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd',maxDate:new Date()})",'readonly'=>'readonly','placeholder'=>'选择时间'])->label('发送时间结束') ?>

                        </div>
                        <?php echo $form->field($model, 'send_num') ?>

 <?php echo $form->field($model, 'type')->dropDownList($model->typePredefine,['prompt' => '全部']) ?>

 <?php echo $form->field($model, 'send_type')->dropDownList($model->sendTypePredefine,['prompt' => '全部']) ?>

 <?php echo $form->field($model, 'sms_type')->dropDownList($model->smsTypePredefine,['prompt' => '全部']) ?>

<?php echo $form->field($model, 'template') ?>

<?php echo $form->field($model, 'feedback') ?>

<?php echo $form->field($model, 'remark') ?>

                        <div>
                            <?php echo $form->field($model, 'add_time_search_start_val')->textInput(['onclick'=>"WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd',maxDate:new Date()})",'readonly'=>'readonly','placeholder'=>'选择时间'])->label('添加时间开始') ?>

<?php echo $form->field($model, 'add_time_search_end_val')->textInput(['onclick'=>"WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd',maxDate:new Date()})",'readonly'=>'readonly','placeholder'=>'选择时间'])->label('添加时间结束') ?>

                        </div>
                         <?php echo $form->field($model, 'status')->dropDownList($model->statusPredefine,['prompt' => '全部']) ?>

    <input type="hidden" id="export_file_flag" name="export_file_flag" value="0">
    <div class="form-group">
        <button type="button" onclick="indexSearchSubmitButton('export_file_flag','w0')" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>

        <button style="display: none;" type="button" onclick="exportFileSubmitButton('export_file_flag','w0')" class="btn btn-warning radius export_file_submit_btn" ><i class="Hui-iconfont">&#xe644;</i> 导出</button>
    </div>

    <?php ActiveForm::end();?>

</div>
