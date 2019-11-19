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

        if(stripos($column->name,'password')){
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
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    public function scenarios()
    {
    <?php
    $tempstr='';
    foreach ($tableSchema->columns as $column){
        if ($column->autoIncrement) {
            continue;
        }
        if($column->name=='is_delete'){
            continue;
        }
        $tempstr.="'".$column->name."'".",";
    }
    ?>
    return [
            'create' => [<?php echo $tempstr; ?>],//创建场景

            'update' => [<?php echo $tempstr; ?>],//修改场景

            'delete' => ['is_delete'],//删除场景
        ];
    }


    //apiModel覆盖父类默认屏蔽，仅在父类与api存在冲突时才独立使用
    public function attributeLabels()
    {
        return [
    <?php foreach ($labels as $name => $label): ?>
        <?= "'$name' => " . $lableArr = ($generator->generateString(explode('=+=',$label)[0])) . ",\n" ?>
    <?php endforeach; ?>
        ];
    }

}
