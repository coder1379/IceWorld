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

echo "<?php\n";
?>
namespace <?= StringHelper::dirname(ltrim($generator->logic, '\\')) ?>;

use Yii;
use common\ComBase;

class <?= $logicModelClass ?>
{
    /**
     * 创建
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function create($data=[],$scenario='create'){
        if(!empty($data)){
            $model = new <?php echo $modelClass; ?>();
            $model->scenario = $scenario;
            if($model->load($data,'')==true && $model->validate()==true){
                $model->add_time = date('Y-m-d H:i:s',time());
                $result = $model->save(false);
                if($result){
                    //创建成功
                    return ComBase::getReturnArray(['id'=>Yii::$app->db->getLastInsertID()],ComBase::CODE_RUN_SUCCESS,ComBase::MESSAGE_CREATE_SUCCESS);
                }else{
                    //创建失败
                    return ComBase::getReturnArray([],ComBase::CODE_SERVER_ERROR,ComBase::MESSAGE_SERVER_ERROR);
                    }
            }else{
                //获取并输出全部错误默认屏蔽根据需求使用
                //return ComBase::getReturnArray($model->getErrors(),ComBase::CODE_PARAM_FORMAT_ERROR,ComBase::MESSAGE_PARAM_FORMAT_ERROR);
                return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 修改
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function update($data=[],$scenario='update'){
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);
            if($id>0){
                $model = <?php echo $modelClass; ?>::findOne(['id'=>$id,'is_delete'=>0]);
                if(!empty($model)){
                    $model->scenario = $scenario;
                    if($model->load($data,'')==true && $model->validate()==true){
                        $result = $model->save(false);
                        if($result){
                            //修改成功
                            return ComBase::getReturnArray([],ComBase::CODE_RUN_SUCCESS,ComBase::MESSAGE_UPDATE_SUCCESS);
                        }else{
                            //修改失败
                            return ComBase::getReturnArray([],ComBase::CODE_SERVER_ERROR,ComBase::MESSAGE_SERVER_ERROR);
                        }
                    }else{
                            //获取并输出全部错误默认屏蔽根据需求使用
                            //return ComBase::getReturnArray($model->getErrors(),ComBase::CODE_PARAM_FORMAT_ERROR,ComBase::MESSAGE_PARAM_FORMAT_ERROR);
                            return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
                    }
                }
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 删除
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function delete($data=[],$scenario='delete'){
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);
            if($id>0){
                $model = <?php echo $modelClass; ?>::findOne(['id'=>$id,'is_delete'=>0]);
                if(!empty($model)){
                    $model->scenario = $scenario;
                    $model->is_delete=1;
                    $result = $model->save(false);
                    if($result){
                        //删除成功
                        return ComBase::getReturnArray([],ComBase::CODE_RUN_SUCCESS,ComBase::MESSAGE_DELETE_SUCCESS);
                    }else{
                        //删除失败
                        return ComBase::getReturnArray([],ComBase::CODE_SERVER_ERROR,ComBase::MESSAGE_SERVER_ERROR);
                    }
                }
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 获取详情
     * @param array $data 数据
     * @param string $fieldsScenarios 字段场景 根据配置控制输出字段
     * @return array
     */
    public function detail($data=[],$fieldsScenarios='detail'){
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);
            if($id>0){
                $detailModel = new <?php echo $modelClass; ?>();
                $showFields = $detailModel->fieldsScenarios()[$fieldsScenarios]??[];
                $model = $detailModel->find()->select($showFields)->where(['id'=>$id,'is_delete'=>0])->one();
                if(!empty($model)){
                    //直接获取数组数据并返回，可以将关联数据在此加入或者将预定义状态等返回
                    $modelArray = $model->oldattributes;
                    return ComBase::getReturnArray($modelArray,ComBase::CODE_RUN_SUCCESS);
                }
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 获取列表
     * @param array $data 数据
     * @param string $fieldsScenarios list 字段场景 控制列表输出到前端字段
     * @return array
     */
    public function list($data=[],$fieldsScenarios='list'){
        $searchModel = new <?php echo $searchModelClass; ?>();
        $dataProvider = $searchModel->search($data,$fieldsScenarios);

        $listObjects = $dataProvider->getModels();
        $list = [];

        if(!empty($listObjects)){
            //循环获取对象的数组值并根据需要将关联数据加入到返回数组中
            foreach ($listObjects as $l) {
                $lArray = $l->oldAttributes;
                //$lArray = $l->attributes;
                //$lArray['record'] = $l->addAdminRecord;
                $list[] = $lArray;
            }
        }

        $pagination = [
            'page_size'=>$dataProvider->getPagination()->pageSize,
            'total_page'=>$dataProvider->getPagination()->getPageCount(),
            'page'=>$dataProvider->getPagination()->page+1,
            'total_count'=>$dataProvider->getPagination()->totalCount,
        ];
        $listArray = [
            'list'=>$list,
            'pagination'=>$pagination,
        ];

        return ComBase::getReturnArray($listArray,ComBase::CODE_RUN_SUCCESS);
    }

}
