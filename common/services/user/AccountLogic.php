<?php


namespace common\services\user;

use common\lib\StringHandle;
use Yii;
use common\ComBase;
use common\base\UserCommon;

/**
 * 账号逻辑封装用户登录注册等
 * @package common\services\user
 */
class AccountLogic
{

    /**
     * 检查密码是否符合要求
     * @param $password
     * @return array|bool
     */
    private function checkPasswordFormat($password)
    {
        //检查密码
        if (!preg_match('/^[a-zA-Z0-9~!@#$%&*_?]{6,30}$/', $password)) {
            return ComBase::getParamsFormatErrorReturnArray('密码格式错误,密码需由字母(区分大小写)数字和~!@#$%&*_?组合，长度为6-30');
        }

        return false;
    }

    /**
     * 获取设备信息 如有错误并返回错误信息
     * @param $data
     * @return array
     */
    private function getDeviceInfo($data)
    {
        $deviceType = ComBase::getIntVal('device_type', $data); //设备类型
        if (!in_array($deviceType, UserCommon::USER_DEVICE_ARR, true)) {
            return ComBase::getParamsErrorReturnArray('设备类型参数错误');
        }
        $deviceCode = '';
        $userDeviceSystem = '';
        $userDeviceModel = '';
        $deviceDesc = '';

        if ($deviceType === UserCommon::USER_DEVICE_TYPE_WEB) {
            $deviceDesc = Yii::$app->request->getUserAgent();
            $deviceCode = trim(ComBase::getStrVal('device_code', $data));
            $userDeviceSystem = StringHandle::getWebSystem($deviceDesc);
            $userDeviceModel = StringHandle::getBrowser($deviceDesc);
        } else {
            $deviceCode = trim(ComBase::getStrVal('device_code', $data));
            $userDeviceSystem = trim(ComBase::getStrVal('system', $data));
            $userDeviceModel = trim(ComBase::getStrVal('model', $data));
        }
        if (empty($deviceCode) || empty($userDeviceSystem) || empty($userDeviceModel)) {
            return ComBase::getParamsErrorReturnArray('device相关参数错误');
        }
        return ComBase::getReturnArray(['device_type' => $deviceType, 'device_code' => $deviceCode, 'system' => $userDeviceSystem, 'model' => $userDeviceModel, 'device_desc' => $deviceDesc]);
    }

    /**
     * 更新登录设备信息token等,$deviceArr必须通过getDeviceInfo获取保障内容不能为空
     * @param $userId
     * @param $token
     * @param $deviceArr
     * @return int
     * @throws \yii\db\Exception
     */
    private function updateUserLoginDevice($userId, $token, $deviceArr)
    {
        if (empty($userId) || empty($token) || empty($deviceArr['code']) || $deviceArr['code'] != ComBase::CODE_RUN_SUCCESS) {
            throw new \Exception('updateUserLoginDevice 更新用户登录设备参数错误');
        }
        $deviceCode = $deviceArr['device_code'] ?? '';//设备号
        $userDeviceData = $this->getUserLoginDeviceByDeviceCode($userId, $deviceCode);
        if (!empty($userDeviceData)) {
            $updateSql = 'update {{%user_login_device}} set token=:token where id=:id';
            Yii::$app->db->createCommand($updateSql, [':id' => $userDeviceData['id']])->execute();
        } else {
            $deviceType = $deviceArr['device_type'];//设备类型
            $deviceSystem = $deviceArr['system'];//设备系统
            $deviceModel = $deviceArr['model'];//设备型号
            $deviceDesc = $deviceArr['device_desc'];//设备描述
            try {
                $insertSql = 'INSERT INTO {{%user_login_device}} (`user_id`, `device_code`, `type`, `system`, `model`, `token`, `add_time`, `device_desc`) VALUES (:user_id,:device_code,:type,:system,:model,:token,:add_time,:device_desc);';
                Yii::$app->db->createCommand($insertSql, [':user_id' => $userId,':device_code'=>$deviceCode,':type'=>$deviceType,':system'=>$deviceSystem,':model'=>$deviceModel,':token'=>$token,':add_time'=>time(),':device_desc'=>$deviceDesc])->execute();
            } catch (\Exception $ex) {
                Yii::error('写入登录设备信息错误:' . $ex->getMessage());
            }
        }

        return true;
    }

    private function getUserLoginDeviceByDeviceCode($userId, $deviceCode, $type = 0)
    {
        if (empty($userId) || empty($deviceCode)) {
            throw new \Exception('getUserLoginDeviceByDeviceCode 获取用户设备信息参数不能为空');
        }

        $sql = 'select id,user_id,type,device_code from {{%user_login_device}} where user_id=:user_id and device_code=:device_code limit 1';
        return Yii::$app->db->createCommand($sql, [':user_id' => $userId, ':device_code' => $deviceCode])->queryOne();
    }

    /**
     * 账号密码登录
     * @param $data
     * @return array
     */
    public function loginByAccountPwd($data)
    {
        $token = '';
        $userName = trim(ComBase::getStrVal('username', $data));
        $password = trim(ComBase::getStrVal('password', $data));
        if (empty($userName) || empty($password)) {
            return ComBase::getParamsErrorReturnArray('账号或密码不能为空');
        }

        //检查用户名
        if (!preg_match('/^[a-zA-Z0-9@._]{6,50}$/', $userName)) {
            return ComBase::getParamsFormatErrorReturnArray('账号格式错误');
        }

        //检查密码
        $pwdRes = $this->checkPasswordFormat($password);
        if (!empty($pwdRes)) {
            return $pwdRes;
        }

        $deviceArr = $this->getDeviceInfo($data);
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        $bindData = UserCommon::getUserLoginBindWithPwdTypes($userName);
        if (!empty($bindData)) {
            $userData = UserCommon::getUserByid($bindData['user_id']);//通过登录绑定的user_id获取用户密码
            if (!empty($userData) && !empty($userData['login_password']) && $userData['login_password'] === UserCommon::getUserLoginMd5Password($password)) {
                $token = UserCommon::getUserLoginToken($userData['id'], $userData['type']);
                $tokenArr = explode('.', $token);
                //更新用户设备信息
                $this->updateUserLoginDevice($userData['id'], end($tokenArr), $deviceArr);


                return ComBase::getReturnArray(['token' => $token]);
            }
        }

        return ComBase::getParamsErrorReturnArray('账号或密码错误');
    }


    /**
     * 通过用户名密码注册
     * @param array $data
     * @return array
     */
    public function registerByUsername($data)
    {
        if (empty($data) || empty($data['username'])) {
            return ComBase::getParamsErrorReturnArray('用户名,密码参数错误');
        }

        $userName = trim(ComBase::getStrVal('username', $data));
        $password1 = trim(ComBase::getStrVal('password1', $data));
        $password2 = trim(ComBase::getStrVal('password2', $data));


        $deviceArr = $this->getDeviceInfo($data);//获取并判断设备信息,后续保存用户设备信息使用
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        if (empty($password1)) {
            return ComBase::getParamsFormatErrorReturnArray('密码不能为空');
        }

        //检查用户名
        if (!preg_match('/^[a-zA-Z0-9_]{8,30}$/', $userName)) {
            return ComBase::getParamsFormatErrorReturnArray('用户名需由字母数字和下划线组合，长度为8-30');
        }

        //检查密码
        $pwdRes = $this->checkPasswordFormat($password1);
        if (!empty($pwdRes)) {
            return $pwdRes;
        }

        if ($password1 !== $password2) {
            return ComBase::getParamsFormatErrorReturnArray('两次密码不匹配');
        }

        $bindUser = UserCommon::getUserLoginBindWithPwdTypes($userName);
        if (!empty($bindUser)) {
            return ComBase::getParamsFormatErrorReturnArray('用户名已经存在');
        }

        //创建user并设置绑定
        //user表数据
        $newTime = time();

        $userData = [
            'name' => '用户_' . StringHandle::getRandomString(6, 'ACDEFGHIJKLMNOPQRSTUVWXYZ2356789acdefghijkmnpqrstuvwxyz'),
            'username' => $userName,
            'login_password' => UserCommon::getUserLoginMd5Password($password1),
            'status' => UserCommon::USER_STATUS_YES,
            'type' => UserCommon::USER_TYPE_REGISTER,
            'add_time' => $newTime,
        ];

        $userLoginBindData = [
            'type' => UserCommon::USER_LOGIN_TYPE_USERNAME,
            'bind_key' => $userName,
        ];

        $token = '';

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert('{{%user}}', $userData)->execute(); //创建用户主表
            $userId = $db->getLastInsertID();//获取用户主表id
            $tokenLongStr = UserCommon::getUserLoginToken($userId, UserCommon::USER_TYPE_REGISTER);//获取用户登录token
            $tempTokenArr = explode('.', $tokenLongStr);
            $token = $tempTokenArr[1] . '.' . $tempTokenArr[2];
            $userLoginBindData['user_id'] = $userId;
            $saveDbToken = end($tempTokenArr);

            $db->createCommand()->insert('{{%user_login_bind}}', $userLoginBindData)->execute();//创建用户登录绑定表

            $this->updateUserLoginDevice($userId, $saveDbToken, $deviceArr);//更新用户登录设备信息

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('用户注册事务回滚:'.$e->getMessage());
            return ComBase::getServerBusyReturnArray();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('用户注册事务回滚:'.$e->getMessage());
            return ComBase::getServerBusyReturnArray();
        }

        return ComBase::getReturnArray(['token' => $token]);
    }


}