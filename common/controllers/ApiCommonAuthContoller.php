<?php

namespace common\controllers;

use common\base\UserCommon;
use common\ComBase;
use Yii;
use yii\helpers\Json;

/**
 * 接口需用户登录及权限控制器基类
 */
class ApiCommonAuthContoller extends ApiCommonContoller
{
    public $allowAccessActions = null;//设置不校验用户允许访问的actions
    public $verifyShortTokenActions = null;//需要进行短token校验的action,注意大小写要保持一致，是区分大小写的

    //public $loginAccessActions = [];//设置登录就能访问的页面
    public function beforeAction($action)
    {

        $verifyCode = $this->setUser();
        if (Yii::$app->params['open_jwt_expire_verify']) {
            if ($verifyCode == ComBase::CODE_LOGIN_EXPIRE) {
                //jwt过期，返回要求前端续签
                echo Json::encode($this->getJsonArray([], ComBase::CODE_LOGIN_EXPIRE, ComBase::MESSAGE_LOGIN_EXPIRE));
                exit();
            }
        }
        $actionId = $action->id;

        //先校验是否在允许例外的actions数组内
        if (empty($this->allowAccessActions) || !in_array($actionId, $this->allowAccessActions, true)) {
            if (empty($this->userId) || empty($this->shortToken) || $this->userType <= 0) { //userId,userType<=0,shortToken为空均表示未登录
                //没有权限
                echo Json::encode($this->getJsonArray([], ComBase::CODE_NO_LOGIN_ERROR, ComBase::MESSAGE_NO_LOGIN_ERROR));
                exit();
            }
        }

        //二次校验是否在需要进行短token数据库查询验证的actions数组内，存在则进行token数据库校验
        if (!empty($this->verifyShortTokenActions) && in_array($actionId, $this->verifyShortTokenActions)) {
            $appId = 0;//此处app_id使用默认0 根据业务调整是否需要验证app_id，例如是否运行跨app使用相同jwt等
            $deviceData = UserCommon::getUserDeviceByUserIdToken($this->userId, $this->shortToken, $appId);
            if (empty($deviceData)) {
                //通过token未查询到对应设备，没有权限
                echo Json::encode($this->getJsonArray([], ComBase::CODE_NO_LOGIN_ERROR, ComBase::MESSAGE_NO_LOGIN_ERROR));
                exit();
            }

        }

        return true;
    }


}
