<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\site\SiteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php echo $form->field($model, 'id') ?>

<?php echo $form->field($model, 'name') ?>

<?php echo $form->field($model, 'introduce') ?>

<?php echo $form->field($model, 'seo_title') ?>

<?php echo $form->field($model, 'seo_keywords') ?>

<?php echo $form->field($model, 'seo_description') ?>

<?php echo $form->field($model, 'telphone') ?>

<?php echo $form->field($model, 'mobile') ?>

<?php echo $form->field($model, 'qq') ?>

<?php echo $form->field($model, 'email') ?>

<?php echo $form->field($model, 'add_time') ?>

 <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态']) ?>

 <?php echo $form->field($model, 'user_id')->label('用户')->dropDownList($model->getUserRecordList(),['prompt' => '请选择用户']) ?>

 <?php echo $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型']) ?>

    <div class="form-group">
        <button type="submit" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>
    </div>

    <?php ActiveForm::end();?>

</div>
