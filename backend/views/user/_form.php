<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\lib\widgets\FileUploadHtml;
/* @var $this yii\web\View */
/* @var $model common\services\user\UserModel */
/* @var $form yii\widgets\ActiveForm */

$fileUploadHtml = new FileUploadHtml();?>


<?php echo $fileUploadHtml->getLinkScript(); ?>
<div class="user-model-form">

    <?php $form = ActiveForm::begin(['id'=>'create_update_active_form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'mobile')->textInput(['maxlength' => true]); ?>

    <?php //echo $form->field($model, 'username')->textInput(['maxlength' => true]); ?>

    <?php //echo $form->field($model, 'login_password')->textInput(['maxlength' => true]); ?>

    <?php echo $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['options'=>[$model->status=>['Selected'=>true]]]); ?>

    <?php echo $form->field($model, 'type')->label('用户类型')->dropDownList($model->typePredefine,['options'=>[$model->type=>['Selected'=>true]]]); ?>

    <?php //echo $form->field($model, 'level')->textInput(); ?>

    <?php //echo $form->field($model, 'realname')->textInput(['maxlength' => true]); ?>

    <?php //echo $form->field($model, 'email')->textInput(['maxlength' => true]); ?>

    <?php echo $fileUploadHtml->createFileUpload($model,"avatar","头像"); ?>
    <?php echo $form->field($model, "avatar")->label(false)->hiddenInput(["maxlength" => true,"id"=>$fileUploadHtml->getHideInputId("avatar")]); ?> 

    <?php echo $form->field($model, 'introduce')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?php echo $form->field($model, 'sex')->label('性别')->dropDownList($model->sexPredefine,['options'=>[$model->sex=>['Selected'=>true]]]); ?>

    <?php //echo $form->field($model, 'birthday')->textInput(); ?>

    <?php echo $form->field($model, 'district')->textInput(['maxlength' => true]); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]); ?>

    <?php //echo $form->field($model, 'token')->textInput(['maxlength' => true]); ?>

    <?php //echo $form->field($model, 'add_time')->textInput(); ?>

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

    //禁用input回车提交
    $(document).on("keydown","#create_update_active_form input[type='text']", function(event) {
        return event.key != "Enter";
    });

    

</script>