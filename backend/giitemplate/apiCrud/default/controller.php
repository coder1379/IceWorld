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

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
use <?= ltrim($generator->logic, '\\') ?>;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\ComBase;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionList()
    {
        $logic = new <?= $logicClass ?>();
        $params = [];
        //$include = [ [ 'name'=>'xxxRecord', 'fields'=>['id','name'] ] ];//支持关联数据获取
        $result = $logic->list($this->post());
        return Json::encode($result);
    }

    public function actionDetail()
    {
        $logic = new <?= $logicClass ?>();
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $params = ['id'=>$id];
        //$include = [ [ 'name'=>'xxxRecord', 'fields'=>['id','name'] ] ];//支持关联数据获取
        $result = $logic->detail($params);
        return Json::encode($result);
    }

    public function actionCreate()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->create($this->post());
        return Json::encode($result);
    }

    public function actionUpdate()
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->update($this->post());
        return Json::encode($result);
    }

    public function actionDelete()
    {
        $logic = new <?= $logicClass ?>();
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $params = ['id'=>$id];
        $result = $logic->delete($params);
        return Json::encode($result);
    }

}
