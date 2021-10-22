<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="text-c search-form-group" style="display:none;">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php
    $labelList =  $generator->getTableSchema();
    $oldLabels=[];
    foreach($labelList->columns as $nx){
        $oldLabels[$nx->name]=$nx->comment;
    }
    
    foreach ($generator->getColumnNames() as $attribute) {
        if(in_array($attribute,array('is_delete'))!=true){
            $commontStr = $oldLabels[$attribute];

            $lableArr = explode('=+=',$commontStr);
            $jsonV=false;
            if(count($lableArr)>1){
                $jsonV=json_decode($lableArr[1],true);
            }


            if(empty($jsonV)!=true){
                if($jsonV["type"]=="text"){
// ->label('".$lableArr[0]."') //2021-10-22 移除了使用配置的
       echo  " <?php echo \$form->field(\$model, '".$attribute."')->dropDownList(\$model->".$jsonV["name"].",['prompt' => '全部'])" ." ?>\n\n";
                }else if($jsonV["type"]=="db"){
                    echo  " <?php //echo \$form->field(\$model, '".$attribute."')->dropDownList(\$model->".$jsonV["functionName"]."List(),['prompt' => '全部'])" ." ?>\n\n";
                }else if($jsonV["type"]=="upload_image"){

                }else if($jsonV["type"]=="rich_text"){

                }else {
                    echo "<?php echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
                }
            }else{
                echo "<?php echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
            }

        }
    }
    ?>
    <input type="hidden" id="export_file_flag" name="export_file_flag" value="0">
    <div class="form-group">
        <button type="button" onclick="indexSearchSubmitButton('export_file_flag','w0')" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>

        <button style="display: none;" type="button" onclick="exportFileSubmitButton('export_file_flag','w0')" class="btn btn-warning radius export_file_submit_btn" ><i class="Hui-iconfont">&#xe644;</i> 导出</button>
    </div>

    <?= "<?php " ?>ActiveForm::end();?>

</div>
