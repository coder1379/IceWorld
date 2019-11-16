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

    <?= "<?php " ?>$form = ActiveForm::begin(['id'=>'create_update_active_form']); ?>

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
            $hideStr = '';
            if(!empty($jsonV["cuHide"]) && $jsonV["cuHide"]==1){
                $hideStr = '//';
            }
            if($jsonV["type"]=="text"){
                echo  "    <?php ".$hideStr."echo \$form->field(\$model, '".$attribute."')->label('".$lableArr[0]."')->dropDownList(\$model->".$jsonV["name"].",['prompt' => '请选择".$lableArr[0]."','options'=>[\$model->".$attribute."=>['Selected'=>true]]]); ?>\n\n";
            }else if($jsonV["type"]=="db"){
                echo  "    <?php ".$hideStr."echo \$form->field(\$model, '".$attribute."')->label('".$lableArr[0]."')->dropDownList(\$model->".$jsonV["functionName"]."List(),['prompt' => '请选择".$lableArr[0]."','options'=>[\$model->".$attribute."=>['Selected'=>true]]]); ?>\n\n";
            }else if($jsonV["type"]=="upload_image"){
                $mustFlag = 0;
                echo '    <?php '.$hideStr.'echo $fileUploadHtml->createFileUpload($model,"'.$attribute.'","'.$lableArr[0].'"); ?>'."\n";
                echo '    <?php '.$hideStr.'echo $form->field($model, "'.$attribute.'")->label(false)->hiddenInput(["maxlength" => true,"id"=>$fileUploadHtml->getHideInputId("'.$attribute.'")]); ?> '."\n\n";
            }else if($jsonV["type"]=="rich_text"){
                echo '    <?php '.$hideStr.'echo $ueditorHtml->createUeditor($model,"'.$attribute.'","'.$lableArr[0].'"); ?>'."\n\n";
                if(!empty($jsonV["must"]) && $jsonV["must"]==1){
                    echo '    <?php '.$hideStr.'echo $form->field($model, "'.$attribute.'")->label(false)->textarea(["maxlength" => true,"style"=>"display:none;"]); ?>'."\n\n";
                    $richTextNoNullList[] = $attribute;
                }

            }else{
                echo "    <?php ".$hideStr."echo " . $generator->generateActiveField($attribute) . "; ?>\n\n";
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

    //禁用input回车提交
    $(document).on("keydown","#create_update_active_form input[type='text']", function(event) {
        return event.key != "Enter";
    });

    <?php
    if(!empty($richTextNoNullList)){
        ?>
    //百度富文本是用此方式进行必填检查
    $("#create_update_active_form").on("beforeValidate", function (event) {
        <?php

        foreach ($richTextNoNullList as $r){
            ?>
        try
        {
            $("#<?php echo strtolower($model->formName()).'-'.$r; ?>").val(<?php echo 'ueObj_'.$r; ?>.getContent());
        }
        catch(err)
        {
            console.log("富文本对象缺失:"+err);
        }

        <?php
        }
        ?>
    });
    <?php
    }
    ?>


</script>