<?php
namespace common\controllers;

use common\ComBase;
use Yii;
use yii\helpers\Json;
/**
 * 接口需用户登录及权限控制器基类
 */

class ApiCommonAuthContoller extends ApiCommonContoller
{
    public $allowAccessActions = [];//设置允许访问的actions
    //public $loginAccessActions = [];//设置登录就能访问的页面
    public function beforeAction($action)
    {
        $this->setUser();
        $actionId=$action->id;
        if(!in_array($actionId,$this->allowAccessActions,true)){
            if(empty($this->userId)){
                //没有权限
                echo Json::encode($this->getJsonArray([],ComBase::CODE_NO_LOGIN_ERROR,ComBase::MESSAGE_NO_LOGIN_ERROR));
                exit();
            }
        }

        return true;
    }


}
