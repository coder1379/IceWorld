<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\lib\widgets\FileUploadHtml;
use common\lib\widgets\UeditorHtml;

/* @var $this yii\web\View */
/* @var $model common\services\site\SiteModel */
/* @var $form yii\widgets\ActiveForm */

$fileUploadHtml = new FileUploadHtml();
$ueditorHtml = new UeditorHtml();?>


<?php echo $fileUploadHtml->getLinkScript(); ?>
<?php echo $ueditorHtml->getLinkScript(); ?>

<div class="site-model-form">

    <?php $form = ActiveForm::begin(['id'=>'create_update_active_form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'introduce')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'seo_description')->textarea(['rows' => 3,'maxlength' => true]); ?>

    <?= $form->field($model, 'telphone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qq')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php echo $fileUploadHtml->createFileUpload($model,"img_url","logo"); ?>
    <?php echo $form->field($model, "img_url")->label(false)->hiddenInput(["maxlength" => true,"id"=>$fileUploadHtml->getHideInputId("img_url")]); ?> 

    <?php echo $fileUploadHtml->createFileUpload($model,"cover","封面"); ?>
    <?php echo $form->field($model, "cover")->label(false)->hiddenInput(["maxlength" => true,"id"=>$fileUploadHtml->getHideInputId("cover")]); ?> 

    <?php echo $ueditorHtml->createUeditor($model,"content","详细介绍"); ?>

    <?php echo $ueditorHtml->createUeditor($model,"about_us","关于我们"); ?>

    <?php //echo $form->field($model, 'add_time')->textInput(); ?>

    <?php echo $form->field($model, 'status')->dropDownList($model->statusPredefine,['options'=>[$model->status=>['Selected'=>true]]]); ?>

    <?php //echo $form->field($model, 'type')->dropDownList($model->typePredefine,['options'=>[$model->type=>['Selected'=>true]]]); ?>

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