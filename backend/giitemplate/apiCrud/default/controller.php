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
        if($c == 'is_delete'){
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
use <?= ltrim($generator->modelClass, '\\') ?>;
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
    * 获取<?php echo $tableComment; ?>列表
    * @notes
    * @param int $page 页数 0 0
    * @param int $page_size 每页数量 0 10
    * @return json yes {"data":{"list":[{"@model":"<?php echo $generator->modelClass; ?>","@fields":"list"}],@pagination}}
    */
    public function actionList()
    {
        $fieldScenarios = 'list';
        $logic = new <?= $logicClass ?>();
        $params = $this->post();//获取前端上传的参数

        //创建查询对象
        $searchModel = new <?= $modelClass ?>();
        $searchDataQuery = $searchModel::find();
        $where = [];//添加过滤条件，注意默认是无条件的
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$this->getUserId();//***默认加入了user_id过滤";
        }
        ?>

        $searchDataQuery->where($where);
        $searchDataQuery->orderBy('id desc');//添加默认排序规则

        //获取输出字段
        $printFields = $searchModel->fieldsScenarios()[$fieldScenarios];

        //获取post内的分页数据并格式化
        $paginationParams = $logic->getPaginationParams($params);

        //$include = [ [ 'name'=>'xxxRecord', 'fields'=>['id','name'] ] ];//支持关联数据获取
        $result = $logic->list($searchDataQuery, $printFields,$paginationParams);
        return Json::encode($result);
    }

    /**
    * 获取<?php echo $tableComment; ?>详情
    * @param int $id ID 1
    * @return json yes {"data":{"@model":"<?php echo $generator->modelClass; ?>","@fields":"detail"}}
    */
    public function actionDetail()
    {
        $logic = new <?= $logicClass ?>();
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $fieldScenarios = 'detail';
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$this->getUserId();//***默认加入了user_id过滤";
        }
        ?>

        $detailModel = new <?php echo $modelClass; ?>();
        $detailQuery = $detailModel::find();
        $detailQuery->where($where);
        $printFields = $detailModel->fieldsScenarios()[$fieldScenarios];
        //$include = [ [ 'name'=>'xxxRecord', 'fields'=>['id','name'] ] ];//支持关联数据获取
        $result = $logic->detail($detailQuery,$printFields);
        return Json::encode($result);
    }

    /**
    * 创建<?php echo $tableComment.PHP_EOL; ?>
    * @param @model <?php echo $generator->modelClass; ?> create
    * @return json yes {"data":{"id":"[number] ID"}}
    */
    public function actionCreate()
    {
        $logic = new <?= $logicClass ?>();
        $params = $this->post();
        $result = $logic->create($params);
        return Json::encode($result);
    }

    /**
    * 修改<?php echo $tableComment.PHP_EOL; ?>
    * @param int $id ID 1
    * @param @model <?php echo $generator->modelClass; ?> update
    * @return json yes {"data":null}
    */
    public function actionUpdate()
    {
        $logic = new <?= $logicClass ?>();
        $params = $this->post();
        $id = $params['id']??0;
        $id = intval($id);
        if($id == 0){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$this->getUserId();//***默认加入了user_id过滤";
        }
        ?>

        $result = $logic->update($where, $params);
        return Json::encode($result);
    }

    /**
    * 删除<?php echo $tableComment.PHP_EOL; ?>
    * @param int $id ID 1
    * @return json yes {"data":null}
    */
    public function actionDelete()
    {
        $logic = new <?= $logicClass ?>();
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$this->getUserId();//***默认加入了user_id过滤";
        }
        ?>

        $result = $logic->delete($where);
        return Json::encode($result);
    }

    /**
    * 物理删除<?= $tableComment ?> 默认屏蔽，需要自行打开
    * @param int $id ID 1
    * @return json yes {"data":null}
    */
    /*public function actionPhysiedelete()
    {
        $logic = new <?= $logicClass ?>();
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$this->getUserId();//***默认加入了user_id过滤";
        }
        ?>

        $result = $logic->physieDelete($where);
        return Json::encode($result);
    }*/

}
