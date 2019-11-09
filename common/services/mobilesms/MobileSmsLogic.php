<?php

namespace common\services\mobilesms;

use Yii;
use common\BaseCommon;
class MobileSmsLogic
{
    /**
     * 创建
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function create($data=[],$scenario='create'){
        $common = new BaseCommon();
        if(!empty($data)){
          $model = new MobileSmsApiModel();
          $model->scenario = $scenario;
          if($model->load($data,'')==true && $model->validate()==true){
              $model->add_time = date('Y-m-d H:i:s',time());
              $result = $model->save(false);
              if($result){
                  return $common->getJsonArray(['id'=>Yii::$app->db->getLastInsertID()],200,$common->createMessage);
              }else{
                  return $common->getOperationFailedMassage(true); //直接获取操作错误返回
              }
          }else{
              //return $common->getJsonArray($model->getErrors(),10001,'');
              return $common->getFormatErrorsArray($common->getModelErrorsToArray($model->getErrors()));
          }
        }else{
            return $common->getParameterErrorMassage(true); //直接获取参数错误返回
        }
    }

    /**
     * 修改
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function update($data=[],$scenario='update'){
        $common = new BaseCommon();
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);
            $model = MobileSmsApiModel::findOne(['id'=>$id,'is_delete'=>0]);
            if(empty($model)){
                return $common->getParameterErrorMassage(true); //直接获取参数错误返回
            }
            $model->scenario = $scenario;
            if($model->load($data,'')==true && $model->validate()==true){
                $result = $model->save(false);
                if($result){
                    return $common->getJsonArray([],200,$common->updateMessage);
                }else{
                    return $common->getOperationFailedMassage(true); //直接获取操作错误返回
                }
            }else{
                return $common->getFormatErrorsArray($common->getModelErrorsToArray($model->getErrors()));
            }
        }else{
            return $common->getParameterErrorMassage(true); //直接获取参数错误返回
        }
    }

    /**
     * 删除
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function delete($data=[],$scenario='delete'){
        $common = new BaseCommon();
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);
            $model = MobileSmsApiModel::findOne(['id'=>$id]);
            if(empty($model)){
                return $common->getParameterErrorMassage(true); //直接获取参数错误返回
            }
            $model->scenario = $scenario;
            $model->is_delete=1;
            $result = $model->save(false);
            if($result){
                return $common->getJsonArray([],200,$common->deleteMessage);
            }else{
                return $common->getOperationFailedMassage(true); //直接获取操作错误返回
            }
        }else{
            return $common->getParameterErrorMassage(true); //直接获取参数错误返回
        }
    }

    /**
     * 获取详情
     * @param array $data 数据
     * @param string $scenario 场景 默认返回的是list也可以单独指定
     * @return array
     */
    public function detail($data=[],$scenario='list'){
        $common = new BaseCommon();
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);

            $modelModel = new MobileSmsApiModel();
            $showFields = $modelModel->fieldsScenarios()[$scenario]??[];
            $model = $modelModel->find()->select($showFields)->where(['id'=>$id,'is_delete'=>0])->one();
            if(empty($model)){
                return $common->getParameterErrorMassage(true); //直接获取参数错误返回
            }
           //直接获取数组数据并返回，可以将关联数据在此加入或者将预定义状态等返回
            $modelArray = $model->oldattributes;

            return $common->getJsonArray($modelArray,200,$common->successMessage);
        }else{
            return $common->getParameterErrorMassage(true); //直接获取参数错误返回
        }
    }

    /**
     * 获取列表
     * @param array $data 数据
     * @param string $scenario 场景
     * @return array
     */
    public function list($data=[],$scenario='list'){
        $common = new BaseCommon();
        $searchModel = new MobileSmsSearch();
        $dataProvider = $searchModel->search($data,$scenario);

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

        return $common->getJsonArray($listArray,200,$common->successMessage);
    }

}