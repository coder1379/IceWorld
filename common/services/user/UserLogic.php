<?php

namespace common\services\user;

use common\lib\StringHandle;
use common\lib\Validate;
use Yii;
use common\BaseCommon;
class UserLogic
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
          $model = new UserApiModel();
          $model->scenario = $scenario;
          if($model->load($data,'')==true && $model->validate()==true){
              $model->login_password=$common->encryptPassword($model->login_password);
              $stringHandle = new StringHandle();
              $model->token=$stringHandle->createToken();
              $tokenOutTime = intval(Yii::$app->params['userTokenOutTime']);
              $model->token_out_time = date('Y-m-d H:i:s',time()+$tokenOutTime);
              $result = $model->save(false);
              if($result){
                  return $common->getJsonArray(['id'=>Yii::$app->db->getLastInsertID(),'token'=>$model->token],200,$common->createMessage);
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
            $model = UserApiModel::findOne(['id'=>$id,'is_delete'=>0]);
            if(empty($model)){
                return $common->getParameterErrorMassage(true); //直接获取参数错误返回
            }
            $model->scenario = $scenario;
            if($model->load($data,'')==true && $model->validate()==true){
                if($model->login_password!=$model->oldAttributes['login_password']){
                    $model->login_password=$common->encryptPassword($model->login_password);
                }
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
            $model = UserApiModel::findOne(['id'=>$id]);
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
     * @param string $user 手机号 必填
     * @param string $pwd 密码  与验证码有一个必填
     * @param string $code 验证码 与密码有一个必填
     * @param string $push_acc 机器吗ACC 必填
     * @param string $push_plist 所在客户端类型 1,2,3,4,5,6 必填
     * @param array $data 数据
     * @param string $scenario 场景 默认返回的是list也可以单独指定
     * @return json 成功 {"code":200,"msg":"success","data":{}}，失败 {"code":1001,"msg":"手机已经注册存在","data":{}}
     */
    public function detail($data=[],$scenario='list'){
        $common = new BaseCommon();
        if(!empty($data)){
            $id = $data['id']??0;
            $id = intval($id);

            $modelModel = new UserApiModel();
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
        $searchModel = new UserApiSearch();
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

    public function signin($data=[]){
        $common = new BaseCommon();
        $validate = new Validate();
        if(!empty($data)){
            $mobile = $data['mobile']??'';
            $password = $data['login_password']??'';
            if(!$validate->isPhone($mobile)){
                return $common->getJsonArray([],10001,'手机号格式错误。');
            }

            if(empty($password) || strlen($password)<6 || strlen($password)>30){
                return $common->getJsonArray([],10001,'密码格式错误。');
            }

            $model = UserApiModel::findOne(['mobile'=>$mobile,'login_password'=>$common->encryptPassword($password),'is_delete'=>0]);
            if(empty($model)){
                return $common->getJsonArray([],10001,'手机号或者密码错误。');
            }else{
                $stringHandle = new StringHandle();
                $model->token=$stringHandle->createToken();
                $tokenOutTime = intval(Yii::$app->params['userTokenOutTime']);
                $model->token_out_time = date('Y-m-d H:i:s',time()+$tokenOutTime);
                $flag = $model->save(false);
                if(!empty($flag)){
                    return $common->getJsonArray(['id'=>$model->id,'token'=>$model->token],200,$common->successMessage);
                }else{
                    return $common->getOperationFailedMassage(true); //直接获取操作错误返回
                }

            }
        }else{
            return $common->getParameterErrorMassage(true); //直接获取参数错误返回
        }
    }

    public function signout($data=[]){
        $common = new BaseCommon();
        if(!empty($data)){
            $token = $data['token']??'';
            if(empty($token) || strlen($token)<30 || strlen($token)>100){
                return $common->getJsonArray([],200,$common->successMessage);
            }

            $model = UserApiModel::findOne(['token'=>$token,'is_delete'=>0]);
            if(!empty($model)){
                $model->token='';
                $model->save(false);
                //$model->scenario = 'update';
                return $common->getJsonArray([],200,$common->successMessage);
            }else{
                return $common->getJsonArray([],200,$common->successMessage);
            }
        }else{
            return $common->getParameterErrorMassage(true); //直接获取参数错误返回
        }
    }

}