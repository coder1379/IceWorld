<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\adminmenu\AdminMenuModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-menu-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'm_level')->label('等级')->dropDownList($model->levelPredefine,['prompt' => '请选择等级','options'=>[$model->m_level=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'parent_id')->label('上级菜单')->dropDownList($model->getParentMenuRecordList(),['options'=>[$model->parent_id=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'controller')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_action')->textInput(['maxlength' => true]) ?>

 <!--<?= $form->field($model, 'type')->label('类型')->dropDownList($model->typePredefine,['prompt' => '请选择类型','options'=>[$model->type=>['Selected'=>true]]]) ?>

 <?= $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态','options'=>[$model->status=>['Selected'=>true]]]) ?>-->

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'show_sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function adminMenuLevelChange() {
        if($("#adminmenumodel-m_level").val()==2){
            $(".field-adminmenumodel-parent_id").show();
            $(".field-adminmenumodel-controller").show();
            $(".field-adminmenumodel-c_action").show();
            $(".field-adminmenumodel-icon").hide();
            $("#adminmenumodel-icon").val('');

        }else if($("#adminmenumodel-m_level").val()==1){
            $("#adminmenumodel-parent_id").val(0);
            $("#adminmenumodel-controller").val('');
            $("#adminmenumodel-c_action").val('');

            $(".field-adminmenumodel-parent_id").hide();
            $(".field-adminmenumodel-controller").hide();
            $(".field-adminmenumodel-c_action").hide();
            $(".field-adminmenumodel-icon").show();
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

        $("#adminmenumodel-m_level").change(function() {
            adminMenuLevelChange();
        });

        adminMenuLevelChange();
    });

</script>