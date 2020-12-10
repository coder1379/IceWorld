<?php

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;

class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

    //apiModel独有function 用于控制哪些字段输出到前端
    public function fieldsScenarios()
    {
    <?php
    $tempstr='';
    foreach ($tableSchema->columns as $column){
        if($column->name=='is_delete'){
            continue;
        }

        //设置敏感字段自动不加入显示列表
        if(stripos($column->name,'password')!==false){
            continue;
        }

        if(stripos($column->name,'pwd')!==false){
            continue;
        }

        if(stripos($column->name,'mobile')!==false){
            continue;
        }

        if(stripos($column->name,'phone')!==false){
            continue;
        }

        if(stripos($column->name,'email')!==false){
            continue;
        }

        if(stripos($column->name,'token')!==false){
            continue;
        }

        if(stripos($column->name,'auth')!==false){
            continue;
        }

        if(stripos($column->name,'user_name')!==false){
            continue;
        }

        if(stripos($column->name,'username')!==false){
            continue;
        }

        $tempstr.="'".$column->name."'".",";
    }
?>
    return [
            'list' => [<?php echo $tempstr; ?>],//列表

            'detail' => [<?php echo $tempstr; ?>],//详情
        ];
    }

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }*/

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function scenarios()
    {
    <?php
    $tempstr='';
    foreach ($tableSchema->columns as $column){
        if ($column->autoIncrement) {
            continue;
        }
        if($column->name=='id' || $column->name=='is_delete' || $column->name=='add_time'){
            continue;
        }
        $tempstr.="'".$column->name."'".",";
    }
    ?>
    return [
            'create' => [<?php echo $tempstr; ?>],//创建场景

            'update' => [<?php echo $tempstr; ?>],//修改场景

            'delete' => ['status'],//删除场景 status = -1
        ];
    }*/


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    /*public function attributeLabels()
    {
        return [
    <?php foreach ($labels as $name => $label): ?>
        <?= "'$name' => " . $lableArr = ($generator->generateString(explode('=+=',$label)[0])) . ",\n" ?>
    <?php endforeach; ?>
        ];
    }*/

}
