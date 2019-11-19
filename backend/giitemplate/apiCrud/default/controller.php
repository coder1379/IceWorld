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
        $result = $logic->list($this->post());
        return Json::encode($result);
    }

    public function actionDetail(<?= $actionParams ?>)
    {
        $logic = new <?= $logicClass ?>();
        $result = $logic->detail($this->post());
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
        $result = $logic->delete($this->post());
        return Json::encode($result);
    }

}
