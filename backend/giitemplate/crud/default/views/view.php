<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

//获取表备注
$dbNameString = Yii::$app->db->dsn;
$dbName1=explode(';',$dbNameString);
$dbNameArr=explode('=',$dbName1[1]);
$dbName=$dbNameArr[1];
$tableNmae = $generator->getTableSchema()->fullName;

$tableCommentObj = Yii::$app->db->createCommand("select table_name,table_comment from information_schema.tables where table_schema = '".$dbName."' and table_name ='".$tableNmae."'")->queryOne();
$tableComment = $tableCommentObj['table_comment'];
if(empty($tableComment)){
    $tableComment=$tableCommentObj['table_name'];
}
//获取表备注

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= $tableComment ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <!--<h1><?= "<?= " ?>Html::encode($this->title) ?></h1>-->

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php

      $tablecolumn=$generator->getTableSchema();

if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
         $commentStr =   empty($tablecolumn->columns[$name]->comment)!=true?$tablecolumn->columns[$column->name]->comment:$name;
          $comarr = explode('=+=',$commentStr);
          $jsonV=false;
          if(count($comarr)>1){
                $jsonV=json_decode($comarr[1],true);
           }
        if(empty($jsonV)!=true){
           if($jsonV["type"]=="text"){
            echo "['label'=>'".$comarr[0]."','value'=>\$model->".$jsonV["name"]."[\$model->".$name."]],\n";
                }else if($jsonV["type"]=="db"){
             echo "['label'=>'".$comarr[0]."','value'=>@\$model->".$jsonV["name"]."->".$jsonV["showName"]."],\n";
                                                  }
          }else{
        echo "            '" . $name . "',\n";
         }
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {

	$commentStr =   empty($tablecolumn->columns[$column->name]->comment)!=true?$tablecolumn->columns[$column->name]->comment:$column->name;
          $comarr = explode('=+=',$commentStr);
          $jsonV=false;
          if(count($comarr)>1){
                $jsonV=json_decode($comarr[1],true);
           }
        if(empty($jsonV)!=true){
           if($jsonV["type"]=="text"){
	   $format = $generator->generateColumnFormat($column);

            echo "['label'=>'".$comarr[0]."','value'=>\$model->".$jsonV["name"]."[\$model->".$column->name."]],\n";
                }else if($jsonV["type"]=="db"){

             echo "['label'=>'".$comarr[0]."','value'=>@\$model->".$jsonV["name"]."->".$jsonV["showName"]."],\n";
                                                  }
          }else{
                 if($column->name=='is_delete'){

                 }else{

        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
             }
         }


    }
}
?>
        ],
    ]) ?>

</div>
