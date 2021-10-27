<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\log\LogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php //echo $form->field($model, 'id') ?>

    <?php echo $form->field($model, 'level')->label('等级')->dropDownList($model->levelPredefine, ['prompt' => '全部等级']) ?>

    <?php echo $form->field($model, 'category') ?>

    <div>
        <?php echo $form->field($model, 'log_time_start')->textInput(['onclick'=>"WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:new Date()})",'readonly'=>'readonly','placeholder'=>'选择时间'])->label('日志时间开始') ?>

        <?php echo $form->field($model, 'log_time_end')->textInput(['onclick'=>"WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:new Date()})",'readonly'=>'readonly','placeholder'=>'选择时间'])->label('日志时间结束') ?>

    </div>

    <?php echo $form->field($model, 'prefix')->input('string', ['placeholder' => 'like搜索,大数据量谨慎使用']) ?>

    <?php echo $form->field($model, 'message')->input('string', ['placeholder' => 'like搜索,大数据量谨慎使用']) ?>

    <div>
        <div class="form-group">
            <button type="submit" class="btn btn-success radius"><i class="Hui-iconfont">&#xe665;</i> 查询</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
