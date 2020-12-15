<?php

namespace api\controllers;

use Yii;
use common\services\user\AccountLogic;
use common\controllers\ApiCommonContoller;
use yii\helpers\Json;

/**
 * 账号
 * UserController implements the CRUD actions for UserApiModel model.
 */
class AccountController extends ApiCommonContoller
{
    public $enableCsrfValidation = false;

    /**
     * 通过用户名注册
     * @notes
     * @param string $username 用户名 1
     * @param string $password1 密码1 1
     * @param string $password2 密码2 1
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=3使用fingerprintjs生成) 1
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"token":"token"}}
     */
    public function actionRegisterbyusername()
    {
        $logic = new AccountLogic();
        $result = $logic->registerByUsername($this->post());
        return Json::encode($result);
    }

    /**
     * 账号密码登录
     * @notes
     * @param string $username 用户名 1
     * @param string $password 密码 1
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=1和2必填) 0
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"token":"token"}}
     */
    public function actionLogin()
    {
        $logic = new AccountLogic();
        $result = $logic->loginByAccountPwd($this->post());
        return Json::encode($result);
    }

    /**
     * token续签
     * @notes
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=1和2必填) 0
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"token":"token"}}
     * @return json no {"code":401,"msg":"尚未登录","data":{}}
     */
    public function actionRenewal(){
        $logic = new AccountLogic();
        $result = $logic->deviceTokenRenewal($this->post());
        return Json::encode($result);
    }


}