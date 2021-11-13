<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use common\ComBase;
<?php
$haveArray=[];
foreach ($labels as $name => $label){
    $lableArr = explode('=+=',$label);
    if(count($lableArr)>1){
        $jsonV=json_decode($lableArr[1],true);

        if($jsonV!=false && $jsonV["type"]=="db"){
            if(in_array($jsonV["modelNamespace"],$haveArray)!=true){
                if(empty($jsonV["modelNamespace"])!=true) {
                    echo $jsonV["modelNamespace"] . "\n";
                }
            }

            $haveArray[] = $jsonV["modelNamespace"];
        }
    }
} ?>

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
    <?php
    $reqrieArr=[];
    foreach ($labels as $name => $label){
        $lableArr = explode('=+=',$label);

        if(count($lableArr)>1){
            $jsonV=json_decode($lableArr[1],true);
            if(!empty($jsonV["type"])){
                if(!empty($jsonV["must"]) && $jsonV["must"]==1){
                    $reqrieArr[]="'".$name."'";
                }

            }
        }

    }
    if(empty($reqrieArr)!=true){
        $rules[]="[[".implode(",",$reqrieArr)."], 'required']";
    }
    ?>

        ////////////字段验证规则
        return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
    }

    public function scenarios()
    {
        ///////模型使用场景
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

        'delete' => ['status'],//删除场景
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $lableArr = ($generator->generateString(explode('=+=',$label)[0])) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>

    /*
    * @配置信息写入
    */
<?php foreach ($labels as $name => $label){
    $lableArr = explode('=+=',$label);
    if(count($lableArr)>1){
        $jsonV=json_decode($lableArr[1],true);
        if($jsonV["type"]=="text"){
            echo "    //对应字段:".$name.",备注：".$lableArr[0]."\n";
            echo "    public $".$jsonV["name"]."=".$jsonV["list"].";\n";
        }
    }
} ?>


    /*
    * @关系内容写入
    */
<?php foreach ($labels as $name => $label){
    $lableArr = explode('=+=',$label);
    if(count($lableArr)>1){
        $jsonV=json_decode($lableArr[1],true);
        if($jsonV["type"]=="db"){
            $idFeildArr = explode(",",$jsonV["selectFeild"]);
            ?>
    //对应字段：<?php echo $name; ?>,<?php echo $lableArr[0]; ?>

    public function <?php echo $jsonV["functionName"]; ?>()
    {
        return $this->hasOne(<?php echo $jsonV["modelName"]; ?>::class, ['<?php echo $jsonV["joinTableId"]; ?>' => '<?php echo $name; ?>']);
    }

    //获取<?php echo $name; ?>,<?php echo $lableArr[0]; ?> 的LIST
    public function <?php echo $jsonV["functionName"]; ?>List(){
        return [];
        //根据实际使用完善下方获取列表功能
        /*
        $array = <?php echo $jsonV["modelName"]; ?>::find()->select('<?php echo $jsonV["selectFeild"]; ?>')->andWhere(['>','status',ComBase::STATUS_COMMON_DELETE])->orderBy("<?php echo $idFeildArr[0]; ?> desc")->limit(100)->asArray()->all();
        $newArr = [];

        if(empty($array)!=true){
            foreach($array as $v){
                $newArr[$v["<?php echo $idFeildArr[0]; ?>"]]=$v["<?php echo $idFeildArr[1]; ?>"];
            }
        }
        return $newArr;
        */
    }

   <?php
        }
    }
} ?>



}
