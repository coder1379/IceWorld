<?php

namespace api\controllers;

use Yii;
use common\services\account\AccountLogic;
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
     * @return json yes {"data":{"user_type":1,"token":"token"}}
     */
    public function actionRegisterbyusername()
    {
        $logic = new AccountLogic();
        $result = $logic->registerByUsername($this->post());
        return Json::encode($result);
    }

    /**
     * 账号密码登录(自动判断类型username,mobile,email)
     * @notes
     * @param string $username 用户名 1
     * @param string $password 密码 1
     * @param string $area_code 区号(默认空,允许手机号密码登录时可能使用) 0
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=1和2必填) 0
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"user_type":1,"token":"token"}}
     */
    public function actionLogin()
    {
        $logic = new AccountLogic();
        $result = $logic->loginByAccountPwd($this->post());
        return Json::encode($result);
    }

    /**
     * 发送手机短信验证码
     * @notes
     * @param string $mobile 手机号 1
     * @param string $area_code 区号(默认空) 0
     * @param int $scene 场景(1=注册,2=登录,3=绑定手机号,4=忘记密码) 1
     * @return json yes {"code":200}
     */
    public function actionSendmobilecode()
    {
        $logic = new AccountLogic();
        $result = $logic->sendMobileCode($this->post());
        return Json::encode($result);
    }

    /**
     * 手机号注册
     * @notes
     * @param string $mobile 手机号 1
     * @param string $code 验证码 1
     * @param string $area_code 区号(默认空) 0
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=3使用fingerprintjs生成) 1
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"user_type":1,"token":"token"}}
     */
    public function actionRegisterbymobile()
    {
        $logic = new AccountLogic();
        $result = $logic->registerByMobile($this->post());
        return Json::encode($result);
    }

    /**
     * 手机号验证码登录
     * @notes
     * @param string $mobile 手机号 1
     * @param string $code 验证码 1
     * @param string $area_code 区号(默认空) 0
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=1和2必填) 0
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"user_type":1,"token":"token"}}
     */
    public function actionMobilecodelogin()
    {
        $logic = new AccountLogic();
        $result = $logic->loginByMobileCode($this->post());
        return Json::encode($result);
    }

    /**
     * token续签
     * @notes
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=1和2必填) 0
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"user_type":1,"token":"token"}}
     * @return json no {"code":401,"msg":"尚未登录","data":{}}
     */
    public function actionRenewal(){
        $logic = new AccountLogic();
        $result = $logic->deviceTokenRenewal($this->post());
        return Json::encode($result);
    }

    /**
     * 获取游客token
     * @notes
     * @param int $device_type 设备类型1=app,2=pc,3=web 1 0
     * @param string $device_code 设备号(device_type=3使用fingerprintjs生成) 1
     * @param string $system 系统(device_type=1和2必填),选项：IOS|Android 0
     * @param string $model 型号(device_type=1和2必填),如RedMi5等 0
     * @return json yes {"data":{"user_type":-1,"token":"token"}}
     */
    public function actionVisitortoken(){
        $logic = new AccountLogic();
        $result = $logic->getVisitorToken($this->post());
        return Json::encode($result);
    }

    /**
     * 邮箱注册待扩展
     */

}
