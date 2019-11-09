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

<div class="text-c search-form-group" style="">

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

       echo  " <?php echo \$form->field(\$model, '".$attribute."')->label('".$lableArr[0]."')->dropDownList(\$model->".$jsonV["name"].",['prompt' => '请选择".$lableArr[0]."'])" ." ?>\n\n";
                }
            }else{
                echo "<?php echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
            }

        }
    }
    ?>
    <div class="form-group">
        <button type="submit" class="btn btn-success radius" ><i class="Hui-iconfont">&#xe665;</i> 查询</button>
    </div>

    <?= "<?php " ?>ActiveForm::end();?>

</div>
