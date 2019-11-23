<?php
namespace common\services\site;

use Yii;
use common\ComBase;

class SiteLogic{
    /**
     * 创建
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组前缀
     * @return array
     */
    public function create($data=[],$scenario='create',$formName=''){
        if(!empty($data)){
            $model = new SiteApiModel();
            $model->scenario = $scenario;
            if($model->load($data,$formName)==true && $model->validate()==true){
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
                //获取并返回错误内容数组
                return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 修改
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组前缀
     * @return array
     */
    public function update($data=[],$scenario='update',$formName=''){
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);
            if($id>0){
                $model = SiteApiModel::findOne(['id'=>$id,'is_delete'=>0]);
                if(!empty($model)){
                    $model->scenario = $scenario;
                    if($model->load($data,$formName)==true && $model->validate()==true){
                        $result = $model->save(false);
                        if($result){
                            //修改成功
                            return ComBase::getReturnArray([],ComBase::CODE_RUN_SUCCESS,ComBase::MESSAGE_UPDATE_SUCCESS);
                        }else{
                            //修改失败
                            return ComBase::getReturnArray([],ComBase::CODE_SERVER_ERROR,ComBase::MESSAGE_SERVER_ERROR);
                        }
                    }else{
                            //获取并返回错误内容数组
                            return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
                    }
                }
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 删除
     * @param array $data sql数据
     * @param string $scenario 场景
     * @return array
     */
    public function delete($data=[],$scenario='delete'){
        if(!empty($data)){
            $data['is_delete'] = 0;
            $model = SiteApiModel::findOne($data);
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
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 获取详情并根据参数附带关联数据
     * @param array $data sql查询数据
     * @param string $fieldsScenarios 字段场景 根据配置控制输出字段
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude
     * @return array | model
     */
    public function detail($data=[],$fieldsScenarios='detail',$include=[]){
        if(!empty($data)){
            $data['is_delete'] = 0;
            $detailModel = new SiteApiModel();
            $printFields = $detailModel->fieldsScenarios()[$fieldsScenarios]??[];
            $model = $detailModel->find()->where($data)->one();
            if(!empty($model)){
                //直接获取数组数据并返回，可以将关联数据在此加入或者将预定义状态等返回
                $modelArray = $model->getAttributes($printFields);
                if(!empty($include)){
                    $includeList = ComBase::getLogicInclude($model, $include);
                    if(!empty($includeList)){
                        foreach ($includeList as $inc){
                            $modelArray[$inc['name']] = $inc['data'];
                        }
                    }
                }
                return ComBase::getReturnArray($modelArray,ComBase::CODE_RUN_SUCCESS);
            }
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 获取列表 并根据参数附带关联数据
     * @param array $data 数据
     * @param string $fieldsScenarios list 字段场景 控制列表输出到前端字段
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude
     * @return array
     */
    public function list($data=[],$fieldsScenarios='list',$include=[]){
        $searchModel = new SiteApiSearch();
        $dataProvider = $searchModel->search($data);
        $printFields = $searchModel->fieldsScenarios()[$fieldsScenarios]??[];
        $listObjects = $dataProvider->getModels();
        $list = [];

        if(!empty($listObjects)){
            //循环获取对象的数组值并根据需要将关联数据加入到返回数组中
            foreach ($listObjects as $model) {
                $modelArray = $model->getAttributes($printFields);
                if(!empty($include)){
                    $includeList = ComBase::getLogicInclude($model, $include);
                    if(!empty($includeList)){
                        foreach ($includeList as $inc){
                            $modelArray[$inc['name']] = $inc['data'];
                        }
                    }
                }
                $list[] = $modelArray;
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
