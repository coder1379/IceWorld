<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";


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


?>

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('添加 '.$tableComment) ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= $tableComment ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">

    <h3><?= "<?= " ?>Html::encode($this->title) ?></h3>

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
