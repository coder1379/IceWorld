<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

//获取表备注
$tempClass = $generator->modelClass;
$tempClassDb = $tempClass::getDb();
$dbNameString = $tempClassDb->dsn;
$dbName1=explode(';',$dbNameString);
$dbNameArr=explode('=',$dbName1[1]);
$dbName=$dbNameArr[1];
$tableNmae = $generator->getTableSchema()->fullName;

$tableCommentObj = $tempClassDb->createCommand("select table_name,table_comment from information_schema.tables where table_schema = '".$dbName."' and table_name ='".$tableNmae."'")->queryOne();
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
         $commentStr =   empty($tablecolumn->columns[$name]->comment)!=true?$tablecolumn->columns[$name]->comment:$name;
          $comarr = explode('=+=',$commentStr);
          $jsonV=false;

          if(count($comarr)>1){
                $jsonV=json_decode($comarr[1],true);
           }
        if(empty($jsonV)!=true){
            $hideStr = '';
            if(!empty($jsonV["viewHide"]) && $jsonV["viewHide"]==1){
                $hideStr = '//';
            }
           if($jsonV["type"]=="text"){
            //echo $hideStr."['label'=>'".$comarr[0]."','value'=>@\$model->".$jsonV["name"]."[\$model->".$name."]],\n"; // 原设置lable形式
            echo $hideStr."['attribute'=>'".$name."','value'=>@\$model->".$jsonV["name"]."[\$model->".$name."]],\n";
                }else if($jsonV["type"]=="db"){
            // echo $hideStr."['label'=>'".$comarr[0]."','value'=>@\$model->".$jsonV["name"]."->".$jsonV["showName"]."],\n";
            echo $hideStr."['attribute'=>'".$name."','value'=>@\$model->".$jsonV["name"]."->".$jsonV["showName"]."],\n";
               }else if($jsonV["type"]=="upload_image"){
               //echo $hideStr."['attribute' => '" . $name . "','label' => '".$comarr[0]."','format' => 'raw','value'  => Html::a(Html::img(\$model->" . $name . ",['class'=>'backend-view-img']),\$model->" . $name . ",['target' => '_blank']),],\n";
               echo $hideStr."['attribute' => '" . $name . "','format' => 'raw','value'  => Html::a(Html::img(Yii::\$app->params['local_static_link'].\$model->" . $name . ",['class'=>'backend-view-img']),Yii::\$app->params['local_static_link'].\$model->" . $name . ",['target' => '_blank']),],\n";
           }else if($jsonV["type"]=="more_text"){
               echo $hideStr."            '" . $name . "',\n";
           }else if($jsonV["type"]=="val"){
               if(!empty($jsonV["TimeFormat"]) && $jsonV["TimeFormat"]==1){
                   //echo $hideStr."['label'=>'".$comarr[0]."','value'=>((\$model->".$name.")>0?date('Y-m-d H:i:s',\$model->".$name."):''),\n";
                   echo $hideStr."['attribute'=>'".$name."','value'=>((\$model->".$name.")>0?date('Y-m-d H:i:s',\$model->".$name."):''),\n";
               }else{
                   echo $hideStr."            '" . $name . "',\n";
               }
           }else if($jsonV["type"]=="rich_text"){
               //echo $hideStr."['attribute' => '" . $name . "','label' => '".$comarr[0]."','format' => 'raw','value'=>'<iframe srcdoc=\''.\$model->".$name.".'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],\n";
               echo $hideStr."['attribute' => '" . $name . "','format' => 'raw','value'=>'<iframe srcdoc=\''.\$model->".$name.".'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],\n";
           }
          }else{
            if($name=='is_delete'){

            }else{
                echo "            '" . $name . "',\n";
            }

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
            $hideStr = '';
            if(!empty($jsonV["viewHide"]) && $jsonV["viewHide"]==1){
                $hideStr = '//';
            }
           if($jsonV["type"]=="text"){
	   $format = $generator->generateColumnFormat($column);

            //echo $hideStr."['label'=>'".$comarr[0]."','value'=>@\$model->".$jsonV["name"]."[\$model->".$column->name."]],\n";
            echo $hideStr."['attribute'=>'".$column->name."','value'=>@\$model->".$jsonV["name"]."[\$model->".$column->name."]],\n";
                }else if($jsonV["type"]=="db"){

             //echo $hideStr."['label'=>'".$comarr[0]."','value'=>@\$model->".$jsonV["name"]."->".$jsonV["showName"]."],\n";
               echo $hideStr."['attribute'=>'".$column->name."','value'=>@\$model->".$jsonV["name"]."->".$jsonV["showName"]."],\n";
                                                  }else if($jsonV["type"]=="upload_image"){
               //echo $hideStr."['attribute' => '" . $column->name . "','label' => '".$comarr[0]."','format' => 'raw','value'  => Html::a(Html::img(\$model->" . $column->name . ",['class'=>'backend-view-img']),\$model->" . $column->name . ",['target' => '_blank']),],\n";
               echo $hideStr."['attribute' => '" . $column->name . "','format' => 'raw','value'  => Html::a(Html::img(Yii::\$app->params['local_static_link'].\$model->" . $column->name . ",['class'=>'backend-view-img']),Yii::\$app->params['local_static_link'].\$model->" . $column->name . ",['target' => '_blank']),],\n";
           }else if($jsonV["type"]=="more_text"){
               echo $hideStr."            '" . $column->name . ":ntext" . "',\n";
           }else if($jsonV["type"]=="val"){
               $format = $generator->generateColumnFormat($column);
               if(!empty($jsonV["TimeFormat"]) && $jsonV["TimeFormat"]==1){
                   //echo $hideStr."['label'=>'".$comarr[0]."','value'=>((\$model->".$column->name.")>0?date('Y-m-d H:i:s',\$model->".$column->name."):'')],\n";
                   echo $hideStr."['attribute'=>'".$column->name."','value'=>((\$model->".$column->name.")>0?date('Y-m-d H:i:s',\$model->".$column->name."):'')],\n";
               }else{
                   echo $hideStr."            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
               }
           }else if($jsonV["type"]=="rich_text"){
               //echo $hideStr."['attribute' => '" . $column->name . "','label' => '".$comarr[0]."','format' => 'raw','value'=>'<iframe srcdoc=\''.\$model->".$column->name.".'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],\n";
               echo $hideStr."['attribute' => '" . $column->name . "','format' => 'raw','value'=>'<iframe srcdoc=\''.\$model->".$column->name.".'\' class=\'backend-view-iframe\' frameborder=\'1\'></iframe>'],\n";
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
