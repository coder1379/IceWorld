<?php

namespace common\controllers;

use common\services\account\AccountCommon;
use common\ComBase;
use common\services\application\AppCommon;
use common\services\user\UserCommon;
use Yii;
use yii\helpers\Json;

/**
 * 接口需用户登录及权限控制器基类
 */
class AuthApiCommonContoller extends ApiCommonContoller
{
    public $allowAccessActions = null;//设置不做任何校验用户即可访问的actions

    public $allowVisitorAccessActions = null; //允许游客访问的actions,由于权限验证类大部分不允许游客访问所以采用允许例外而不是排除,仅当用户类型为游客时判断,若要排除游客验证直接使用 allowAccessActions不做任何验证,游客校验会受到全局配置影响,注意如果关闭了游客模式，允许游客访问的内容将自动降级为不做任何访问限制

    public $verifyShortTokenActions = [];//需要进行短token校验的action(强安全性要求时使用例如修改密码,发表评论等),注意大小写要保持一致，是区分大小写的,建议均使用小写进行action命名,大写action 注意需要转换为-才能访问，$action->id 也为-值

    public function beforeAction($action)
    {
        $verifyCode = $this->setUser();
        $actionId = $action->id;
        $checkRet = $this->userVerification($verifyCode, $actionId);//用户权限验证

        if($checkRet!==true){ //防止返回值写错导致错误权限通过进行二次验证
            echo Json::encode(ComBase::getNoLoginReturnArray());
            exit();//结束后面所有流程
        }
        return true;
    }

    public function userVerification($verifyCode, $actionId)
    {
        //先校验是否在允许例外的actions数组内，在特例内不做任何校验直接返回通过，后续流程一般在业务层自行校验
        if (!empty($this->allowAccessActions) && in_array($actionId, $this->allowAccessActions, true)) {
            return true;
        }

        //然后校验是否关闭了游客模式并且有允许游客访问的内容，有则降级为允许直接访问并结束后面流程
        if(Yii::$app->params['jwt']['jwt_device_visitor_verification']===false && !empty($this->allowVisitorAccessActions) && in_array($actionId,$this->allowVisitorAccessActions,true)){
            return true;
        }

        //之后才开始jwt正确性的校验
        if ($this->userType != UserCommon::TYPE_DEVICE_VISITOR) { //userType != -1即为正式用户
            if (ComBase::CODE_RUN_SUCCESS === $verifyCode) { //jwt验证状态为成功为正式用户jwt解密权限验证成功

                /**
                 * 此处可以统一扩展更多用户的权限验证
                 * 例如判断用户是否有资格进行发言，数据写入等权限,并直接返回相应错误即可无需考虑游客相关问题
                 * 由于此处统一查询性能可能受到一定影响 少量独立验证建议在业务层自行实现
                 * 可在controller中重写beforeAction控制独立业务，但需要注意必须优先执行父类beforeAction
                 */

                //查询数据库token进行二次校验,开启严格模式或者在需要token验证的数组内执行 具体根据情况配置
                if (Yii::$app->params['jwt']['jwt_strict_verification'] === true || in_array($actionId, $this->verifyShortTokenActions)) {
                    $appId = AppCommon::getAppId($this->post());
                    $deviceData = AccountCommon::getAccountDeviceByUserIdToken($this->userId, $this->shortToken, $appId);
                    if (empty($deviceData)) {
                        //通过token未查询到对应设备，根据配置判断返回
                        echo Json::encode($this->getJwtVerificationFailReturnArray($actionId));
                        exit();
                    }
                }
                return true;
            } else if (ComBase::CODE_LOGIN_EXPIRE === $verifyCode) {

                if(Yii::$app->params['jwt']['jwt_expire_renewal']){
                    // 配置开启续签返回续签给前端
                    echo Json::encode(ComBase::getReturnArray([], ComBase::CODE_LOGIN_EXPIRE, ComBase::MESSAGE_LOGIN_EXPIRE));
                    exit();//结束后面所有流程
                }else{
                    //直接返回需要重新登录
                    echo Json::encode(ComBase::getNoLoginReturnArray());
                    exit();//结束后面所有流程
                }

            } else {
                //无效jwt直接返回未登录 判断是否允许游客访问 返回内容
                echo Json::encode($this->getJwtVerificationFailReturnArray($actionId));
                exit();//结束后面所有流程
            }

        } else {
            /**
             * 游客判断模式 主要判断是否开启游客模式，是否在允许游客访问中
             * 不在直接返回重新登录,未开启游客模式的这类情况已在前面处理不会走到这步
             */
            if(Yii::$app->params['jwt']['jwt_device_visitor_verification'] === true && !empty($this->allowVisitorAccessActions) && in_array($actionId,$this->allowVisitorAccessActions,true)){
                if(ComBase::CODE_RUN_SUCCESS === $verifyCode){
                    return true;
                }else if(ComBase::CODE_LOGIN_EXPIRE === $verifyCode){
                    if(Yii::$app->params['jwt']['jwt_expire_renewal']){
                        // 配置开启续签返回续签给前端
                        echo Json::encode(ComBase::getReturnArray([], ComBase::CODE_LOGIN_EXPIRE, ComBase::MESSAGE_LOGIN_EXPIRE));
                        exit();//结束后面所有流程
                    }else{
                        //直接返回需要重新登录
                        echo Json::encode(ComBase::getNoLoginReturnArray());
                        exit();//结束后面所有流程
                    }
                }else{
                    //开启了游客模式并且允许游客访问此action，游客无效返回重新后去游客token重试
                    echo Json::encode(ComBase::getReturnArray([], ComBase::CODE_GET_VISITOR_TOKEN_RETRY, ComBase::MESSAGE_GET_VISITOR_TOKEN_RETRY));
                    exit();//结束后面所有流程
                }
            }

            //直接返回需要重新登录
            echo Json::encode(ComBase::getNoLoginReturnArray());
            exit();//结束后面所有流程
        }

    }

    /**
     * 获取jwt验证失败后的返回，主要封装根据是否开启游客返回内容不同
     */
    private function getJwtVerificationFailReturnArray($actionId)
    {
        if (Yii::$app->params['jwt']['jwt_device_visitor_verification'] === true) { //开启了游客模式不返回重新登录而是根据是否允许游客访问判断是否返回一个获取游客token重试
            if (!empty($this->allowVisitorAccessActions) && in_array($actionId, $this->allowVisitorAccessActions, true)) {
                //在允许游客访问列表内返回获取游客token重试
                return ComBase::getReturnArray([], ComBase::CODE_GET_VISITOR_TOKEN_RETRY, ComBase::MESSAGE_GET_VISITOR_TOKEN_RETRY);
            }
        }
        return ComBase::getNoLoginReturnArray();
    }


}
