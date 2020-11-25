<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$logicClass = StringHelper::basename($generator->logic);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

$columnNames = $generator->getColumnNames();
$includeUserId = 0;
if(!empty($columnNames)){
    foreach ($columnNames as $c){
        if($c == 'user_id'){
            $includeUserId = 1;
        }
    }
}

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

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
//use <?= ltrim($generator->modelClass, '\\') ?>;
use <?= ltrim($generator->logic, '\\') ?>;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\ComBase;

/**
 * <?php echo $tableComment.PHP_EOL; ?>
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public $enableCsrfValidation = false;

    /**
     * 获取<?php echo $tableComment.'列表'.PHP_EOL; ?>
     * @notes
     * @param int $page 页数 0 0
     * @param int $page_size 每页数量 0 10
     * @return json yes {"data":{"list":[{"@model":"<?php echo $generator->modelClass; ?>","@fields":"list"}],@pagination}}
     */
    public function actionList()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->list($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 获取<?php echo $tableComment.'详情'.PHP_EOL; ?>
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":{"@model":"<?php echo $generator->modelClass; ?>","@fields":"detail"}}
     */
    public function actionDetail()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->detail($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 创建<?php echo $tableComment.PHP_EOL; ?>
     * @notes
     * @param @model <?php echo $generator->modelClass; ?> create
     * @return json yes {"data":{"id":"[number] ID"}}
     */
    public function actionCreate()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->create($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 修改<?php echo $tableComment.PHP_EOL; ?>
     * @notes
     * @param int $id ID 1
     * @param @model <?php echo $generator->modelClass; ?> update
     * @return json yes {"data":null}
     */
    public function actionUpdate()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->update($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 删除<?php echo $tableComment.PHP_EOL; ?>
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":null}
     */
    public function actionDelete()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->delete($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 物理删除默认屏蔽，需要自行打开<?php echo $tableComment.PHP_EOL; ?>
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":null}
     */
    /*public function actionPhysiedelete()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->physieDelete($this->post(), $this->getUserId());
        return Json::encode($result);
    }*/

}
