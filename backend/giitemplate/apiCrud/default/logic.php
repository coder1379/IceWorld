<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$logicModelClass = StringHelper::basename($generator->logic);
if ($modelClass === $logicModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

$columnNames = $generator->getColumnNames();
$includeIsDelete = 0;
if(!empty($columnNames)){
    foreach ($columnNames as $c){
        if($c == 'is_delete'){
            $includeIsDelete = 1;
        }
    }
}

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
namespace <?= StringHelper::dirname(ltrim($generator->logic, '\\')) ?>;

use Yii;
use common\ComBase;
use common\base\BaseLogic;

class <?= $logicModelClass ?>
{


    /**
     * 基础创建,复杂逻辑建议额外添加
     * @param array $params 前端传递post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function create($params = null, $currentUserId, $scenario = 'create' , $formName = '' ){

        $logic = new BaseLogic();
        $model = new <?php echo $modelClass; ?>();
        $model->add_time = date('Y-m-d H:i:s',time());
        if(!empty($params)){
            <?php
            if($includeUserId == 1){ //包含user_id 默认加入user_id;
            ?>
            if(empty($currentUserId)){
                return ComBase::getNoLoginReturnArray();
            }
            <?php
                echo "\$params['user_id'] = \$currentUserId;//***默认加入了user_id过滤";
            }
            ?>
        }
        return $logic->baseCreate($model, $params, $scenario, $formName);
    }

    /**
     * 基础修改功能,复杂逻辑建议新建
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function update($params = null, $currentUserId, $scenario = 'update', $formName = ''){

        $id = ComBase::getIntVal('id', $params);
        if(empty($id)){
            return ComBase::getParamsErrorReturnArray();
        }

        $logic = new BaseLogic();
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
        ?>
        if(empty($currentUserId)){
            return ComBase::getNoLoginReturnArray();
        }

        <?php
            echo "\$where['user_id'] = \$currentUserId;//***默认加入了user_id过滤";
        }
        ?>

        if(!empty($params)){
            <?php
            if($includeIsDelete == 1){ //包含is_delete 默认加入is_delete = 0;
                echo "\$where['is_delete'] = 0;//有is_delete表默认加入软删除过滤";
            }
            ?>

            $model = <?php echo $modelClass; ?>::findOne($where);
            return $logic->baseUpdate($model, $params, $scenario, $formName);
        }
        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 标记删除 优先使用标记删除
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function delete($params = null, $currentUserId, $scenario = 'delete', $formName = ''){

        $id = ComBase::getIntVal('id', $params);
        if(empty($id)){
            return ComBase::getParamsErrorReturnArray();
        }

        $logic = new BaseLogic();
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$currentUserId;//***默认加入了user_id过滤";
        }
        ?>

        //默认可以传入删除时的data,自动加入is_delete标记
        if(empty($params['is_delete'])){
            $params['is_delete'] = 1;
        }

        if(!empty($params)){
            <?php
            if($includeIsDelete == 1){ //包含is_delete 默认加入is_delete = 0;
                echo "\$where['is_delete'] = 0;//有is_delete表默认加入软删除过滤";
            }
            ?>

            $model = <?php echo $modelClass; ?>::findOne($where);
            return $logic->baseDelete($model, $params, $scenario, $formName);
        }
        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 物理删除 **默认优先使用标记删除 delete
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param boll $backUp 是否备份删除数据到warning日志 默认false
     * @return array
     */
    public function physieDelete($params = null, $currentUserId, $backUp = false){

        $id = ComBase::getIntVal('id', $params);
        if(empty($id)){
            return ComBase::getParamsErrorReturnArray();
        }

        $logic = new BaseLogic();
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$currentUserId;//***默认加入了user_id过滤";
        }
        ?>

        $model = <?php echo $modelClass; ?>::findOne($where);
        return $logic->basePhysieDelete($model, $backUp);
    }

    /**
     * 基础获取详情,复杂逻辑建议新建查询
     * @param array $params 前端传入数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $fieldScenarios 场景默认 detail
     * @return array
     */
    public function detail($params = null, $currentUserId,$fieldScenarios = 'detail'){

        $logic = new BaseLogic();
        $id = ComBase::getIntVal('id', $params);
        if(empty($id)){
            return ComBase::getParamsErrorReturnArray();
        }
        $where = ['id'=>$id];
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$currentUserId;//***默认加入了user_id过滤";
        }
        ?>

        $detailModel = new <?php echo $modelClass; ?>();
        $detailQuery = $detailModel::find();
        $detailQuery->where($where);
        <?php
        if($includeIsDelete == 1){ //包含is_delete 默认加入is_delete = 0;
            echo "\$detailQuery->andWhere(['is_delete'=>0]);//有is_delete表默认加入软删除过滤";
        }
        ?>
        $printFields = $detailModel->fieldsScenarios()[$fieldScenarios];

        $include = null;//[ [ 'name'=>'xxxRecord', 'fields'=>'api_detail' ] ];//支持关联数据获取

        return $logic->baseDetail($detailQuery, $printFields, $include);
    }

    /**
     * 基础获取列表,复杂逻辑建议新建查询
     * @param array $params 前端传入数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $fieldScenarios 场景默认 list
     * @return array
     */
    public function list($params = null, $currentUserId,$fieldScenarios = 'list'){
        $logic = new BaseLogic();

        //创建查询对象
        $searchModel = new <?= $modelClass ?>();
        $searchDataQuery = $searchModel::find();
        $where = [];//添加过滤条件，注意默认是无条件的
        <?php
        if($includeUserId == 1){ //包含user_id 默认加入user_id;
            echo "\$where['user_id'] = \$currentUserId;//***默认加入了user_id过滤";
        }
        ?>

        $searchDataQuery->where($where);
        $searchDataQuery->orderBy('id desc');//添加默认排序规则

        <?php
        if($includeIsDelete == 1){ //包含is_delete 默认加入is_delete = 0;
            echo "\$searchDataQuery->andWhere(['is_delete' => 0 ]);//默认添加标记删除标识";
        }
        ?>

        //获取输出字段
        $printFields = $searchModel->fieldsScenarios()[$fieldScenarios];

        //获取post内的分页数据并格式化
        $paginationParams = $logic->getPaginationParams($params);

        $include = null; //[ [ 'name'=>'xxxRecord', 'fields'=>'api_detail' ] ];//支持关联数据获取

        return $logic->baseList($searchDataQuery, $printFields, $paginationParams, $include);
    }
}
