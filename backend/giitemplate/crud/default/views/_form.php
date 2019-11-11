<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

<?php
$uploadImage = 0;
$richText = 0;
$richTextNoNullList = [];

$labelList =  $generator->getTableSchema();
$oldLabels=[];
foreach($labelList->columns as $nx){
    $oldLabels[$nx->name]=$nx->comment;
}
foreach ($generator->getColumnNames() as $attribute) {

    $commontStr = $oldLabels[$attribute];

    $lableArr = explode('=+=',$commontStr);
    $jsonV=false;
    if(count($lableArr)>1){
        $jsonV=json_decode($lableArr[1],true);
    }

    if(empty($jsonV)!=true){
        if($jsonV["type"]=="upload_image"){
            $uploadImage = 1;
        }else if($jsonV["type"]=="rich_text"){
            $richText = 1;
        }
    }

}
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
<?php if($uploadImage==1){ echo "use common\lib\widgets\FileUploadHtml;"; } ?>
<?php if($richText==1){ echo "\n"."use common\lib\widgets\UeditorHtml;"."\n"; } ?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */

<?php if($uploadImage==1){ echo '$fileUploadHtml = new FileUploadHtml();'; } ?>
<?php if($richText==1){ echo "\n".'$ueditorHtml = new UeditorHtml();'; } ?>
?>

<?php if($uploadImage==1){ echo "\n".'<?php echo $fileUploadHtml->getLinkScript(); ?>'; } ?>
<?php if($richText==1){ echo "\n".'<?php echo $ueditorHtml->getLinkScript(); ?>'."\n"; } ?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php
$flag=0;

foreach ($generator->getColumnNames() as $attribute) {
    $flag++;
    if($flag==1){
        continue;
    }

    if(in_array($attribute,array('add_admin_id'))==true){
        continue;
    }

    $commontStr = $oldLabels[$attribute];

    $lableArr = explode('=+=',$commontStr);
    $jsonV=false;
    if(count($lableArr)>1){
        $jsonV=json_decode($lableArr[1],true);
    }

    if (in_array($attribute, $safeAttributes)) {
        if(empty($jsonV)!=true){
            if($jsonV["type"]=="text"){
                echo  " <?= \$form->field(\$model, '".$attribute."')->label('".$lableArr[0]."')->dropDownList(\$model->".$jsonV["name"].",['prompt' => '请选择".$lableArr[0]."','options'=>[\$model->".$attribute."=>['Selected'=>true]]]) ?>\n\n";
            }else if($jsonV["type"]=="db"){
                echo  " <?= \$form->field(\$model, '".$attribute."')->label('".$lableArr[0]."')->dropDownList(\$model->".$jsonV["functionName"]."List(),['prompt' => '请选择".$lableArr[0]."','options'=>[\$model->".$attribute."=>['Selected'=>true]]]) ?>\n\n";
            }else if($jsonV["type"]=="upload_image"){
                echo '<?php echo $fileUploadHtml->createFileUpload($model,"'.$attribute.'","'.$lableArr[0].'",["hide_input"=>1]); ?>'."\n\n";
                if($jsonV["must"]==1){
                    echo '<?php echo $form->field($model, "'.$attribute.'")->label(false)->hiddenInput(["maxlength" => true,"id"=>$fileUploadHtml->getHideInputId("'.$attribute.'")]); ?> <!--加入非空提示-->'."\n\n";
                }

            }else if($jsonV["type"]=="rich_text"){
                echo '<?php echo $ueditorHtml->createUeditor($model,"'.$attribute.'","'.$lableArr[0].'"); ?>'."\n\n";
                if($jsonV["must"]==1){
                    echo '<?php echo $form->field($model, "'.$attribute.'")->label(false)->textarea(["maxlength" => true,"style"=>"display:none;"]); ?>'."\n\n";
                    $richTextNoNullList[] = $attribute;
                }

            }
        }
        else{
            if(in_array($attribute,array('is_delete','add_admin_id','add_time'))==true){}else{
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
                }
        }


    }
} ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('添加') ?> : <?= $generator->generateString('修改') ?>, ['class' => $model->isNewRecord ? 'btn btn-primary radius' : 'btn btn-primary radius']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

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

    /*
       //当富文本内容不能为空时采用此方式
       $("#w0").on("beforeValidate", function (event) {
           $("#websitearticlemodel-content").val($('ueditor-id-content').getContent());

        });*/

</script>