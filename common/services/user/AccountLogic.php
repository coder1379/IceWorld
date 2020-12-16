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
     * 写入用户密码类登录绑定表
     * @param $userId
     * @param $type
     * @param $key
     * @param int $appId 应用id
     * @return bool
     * @throws \yii\db\Exception
     */
    private function insertUserLoginBindWithPwd($userId, $type, $key, $appId = 0)
    {
        if (empty($userId) || empty($type) || empty($key)) {
            $allArgs = func_get_args();
            throw new \Exception('insertUserLoginBindWithPwd 写入用户登录绑定表参数不能为空:' . json_encode($allArgs));
        }

        $insertSql = 'INSERT INTO {{%user_login_bind}} (`user_id`, `type`, `bind_key`,`app_id`) VALUES (:user_id,:type,:bind_key,:app_id);';
        Yii::$app->db->createCommand($insertSql, [':user_id' => $userId, ':type' => $type, ':bind_key' => $key, ':app_id' => $appId])->execute();
        return Yii::$app->db->getLastInsertID();
    }

    private function updateUserLoginDevice($deviceId, $token)
    {
        $updateSql = 'update {{%user_login_device}} set token=:token where id=:id';
        return Yii::$app->db->createCommand($updateSql, [':token' => $token, ':id' => $deviceId])->execute();
    }

    /**
     * 写入用户登录记录
     * @param $userId
     * @param $deviceArr
     * @param $token
     * @param int $appId 应用id
     * @return int
     */
    private function insertUserLoginDevice($userId, $deviceArr, $token, $appId = 0)
    {
        try {
            $deviceCode = $deviceArr['data']['device_code'] ?? '';//设备号
            $deviceType = $deviceArr['data']['device_type'];//设备类型
            $deviceSystem = $deviceArr['data']['system'];//设备系统
            $deviceModel = $deviceArr['data']['model'];//设备型号
            $deviceDesc = $deviceArr['data']['device_desc'];//设备描述
            $insertSql = 'INSERT INTO {{%user_login_device}} (`user_id`,`device_code`, `app_id`, `type`, `system`, `model`, `token`, `add_time`, `device_desc`) VALUES (:user_id,:device_code,:app_id,:type,:system,:model,:token,:add_time,:device_desc);';
            Yii::$app->db->createCommand($insertSql, [':user_id' => $userId, ':device_code' => $deviceCode, ':app_id' => $appId, ':type' => $deviceType, ':system' => $deviceSystem, ':model' => $deviceModel, ':token' => $token, ':add_time' => time(), ':device_desc' => $deviceDesc])->execute();
            return intval(Yii::$app->db->getLastInsertID());
        } catch (\Exception $ex) {
            Yii::error('写入登录设备信息错误:' . $ex->getMessage());
            return 0;
        }
    }

    /**
     * 更新登录设备信息token等,$deviceArr必须通过getDeviceInfo获取保障内容不能为空
     * @param $userId
     * @param $token
     * @param $deviceArr
     * @param int $appId 应用id 默认0
     * @return int
     * @throws \yii\db\Exception
     */
    private function saveUserLoginDevice($userId, $token, $deviceArr, $appId = 0)
    {
        if (empty($userId) || empty($token) || empty($deviceArr['code']) || $deviceArr['code'] != ComBase::CODE_RUN_SUCCESS) {
            $allArgs = func_get_args();
            throw new \Exception('saveUserLoginDevice 更新用户登录设备参数错误:' . json_encode($allArgs));
        }
        $deviceId = 0;
        $deviceCode = $deviceArr['data']['device_code'] ?? '';//设备号
        $userDeviceData = $this->getUserLoginDeviceByDeviceCode($userId, $deviceCode, $appId);
        if (!empty($userDeviceData)) {
            $deviceId = intval($userDeviceData['id']);
            $this->updateUserLoginDevice($deviceId, $token);
        } else {
            $deviceId = $this->insertUserLoginDevice($userId, $deviceArr, $token, $appId);
        }

        return $deviceId;
    }

    /**
     * 获取用户设备信息by user_id 设备号
     * @param $userId
     * @param $deviceCode
     * @param int $type
     * @param int $appId 应用id 默认0
     * @return array|false
     * @throws \yii\db\Exception
     */
    private function getUserLoginDeviceByDeviceCode($userId, $deviceCode, $type = 0, $appId = 0)
    {
        if (empty($userId) || empty($deviceCode)) {
            $allArgs = func_get_args();
            throw new \Exception('getUserLoginDeviceByDeviceCode 获取用户设备信息参数不能为空:' . json_encode($allArgs));
        }

        $sql = 'select id,user_id,type,device_code,app_id from {{%user_login_device}} where user_id=:user_id and device_code=:device_code and app_id=:app_id limit 1';
        return Yii::$app->db->createCommand($sql, [':user_id' => $userId, ':device_code' => $deviceCode, ':app_id' => $appId])->queryOne();
    }

    /**
     * 设备token续签
     * @param $data
     * @return array
     */
    public function deviceTokenRenewal($data)
    {
        $userId = 0; //续签的用户id不会串过来所以重新获取
        $shortToken = null;
        $oldToken = Yii::$app->request->post('token', '');

        $appId = 0;//应用ID 0为默认 根据业务自行控制是否需要获取 搜索**app_id** 更换需要app_id逻辑的地方 根据业务可以选择由前端传入或者从jwt中获取a_i

        if (!empty($oldToken) && strlen($oldToken) < 500) {
            $jwtUser = UserCommon::decodeUserLoginToken($oldToken);
            if (!empty($jwtUser)) {
                $nowTime = time();
                $userId = $jwtUser->u_i ?? 0;
                $userId = intval($userId);
                $jwtTime = $jwtUser->o_t ?? 0;
                $jwtTime = intval($jwtTime);
                $exMaxTime = $nowTime - $jwtTime;

                if (empty($userId)) {
                    //用户为空直接返回未登录
                    return ComBase::getNoLoginReturnArray();
                }

                $lastExTime = $jwtTime - $nowTime;
                if(!empty($jwtTime)){//$jwtTime = 0为永久有效，但在续签的业务中如果调用了续签还是生成新的jwt-token防止用户状态不刷新
                    if ($lastExTime > 3600 ) {
                        return ComBase::getReturnArray(['token' => $oldToken]);//如果过期时间为0或者离过期超过1小时则直接返回当前token,防止无意义刷新
                    } else if ($exMaxTime > 2592000) { //****如果过期时间已经超过30天则不能再进行续签，直接过期重新登录 具体时间自行调整****
                        return ComBase::getNoLoginReturnArray();
                    }
                }


                $tempTokenArr = explode('.', $oldToken);
                $shortToken = end($tempTokenArr);
                if(!empty($shortToken)){
                    $deviceArr = $this->getDeviceInfo($data);

                    if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
                        return ComBase::getNoLoginReturnArray($deviceArr['msg']);
                    }

                    try {
                        $deviceData = UserCommon::getUserDeviceByUserIdToken($userId, $shortToken, $appId);//获取设备信息
                        $userData = UserCommon::getUserByid($userId);//获取用户信息判断是否续签，防止问题用户无限续签
                        if (!empty($deviceData) && !empty($userData)) {
                            $userStatus = intval($userData['status']);
                            if ($userStatus > UserCommon::USER_STATUS_DEL) {//如果是已经删除的用户则无法续签，这里自行根据业务修改用户状态判断值
                                $tokenArr = UserCommon::getUserLoginToken($userId, $userData['type'], $appId);
                                $deviceType = $deviceArr['data']['device_type'];//设备类型
                                $deviceCode = $deviceArr['data']['device_code'] ?? '';//设备号
                                $dbDeviceType = intval($deviceData['type']);

                                if (!empty($deviceCode) && $deviceType === $dbDeviceType && $deviceCode === $deviceData['device_code']) {
                                    //设备code相同直接更新,刷新需要判断devicetype是否相同,防止刷新了app的token
                                   $updateFlg = $this->updateUserLoginDevice($deviceData['id'], $tokenArr['token']);
                                    if (!empty($updateFlg)) {
                                        return ComBase::getReturnArray(['token' => $tokenArr['jwt_token']]);//返回新的jwt-token
                                    }
                                } else {

                                    if ($deviceType === UserCommon::USER_DEVICE_TYPE_WEB) {
                                        $deviceDataWeb = $this->getUserLoginDeviceByDeviceCode($userId, $deviceCode, $appId);//获取设备信息
                                        if (!empty($deviceDataWeb)) {
                                            $webDeviceType = intval($deviceDataWeb['type'] ?? 0);
                                            if ($webDeviceType === UserCommon::USER_DEVICE_TYPE_WEB) {
                                                $updateFlg = $this->updateUserLoginDevice($deviceDataWeb['id'], $tokenArr['token']);//浏览器设备号相同视为同一个浏览器直接更新旧token
                                                if (!empty($updateFlg)) {
                                                    return ComBase::getReturnArray(['token' => $tokenArr['jwt_token']]);//返回新的jwt-token
                                                }
                                            }

                                        } else {
                                            //浏览器类型可以直接续签新浏览器主要用于app内打开网页场景
                                            $deviceId = $this->insertUserLoginDevice($userId, $deviceArr, $tokenArr['token'], $appId);
                                            if (!empty($deviceId)) {
                                                return ComBase::getReturnArray(['token' => $tokenArr['jwt_token']]);//返回新的jwt-token
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } catch (\Exception $exc) {
                        Yii::error('jwt续签异常:' . $exc->getMessage());
                    }
                }
            }
        }

        return ComBase::getNoLoginReturnArray();
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

        $appId = 0;//应用ID 0为默认 根据业务自行控制是否需要获取 搜索**app_id** 更换需要app_id逻辑的地方

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

        $bindData = UserCommon::getUserLoginBindWithPwdTypes($userName, $appId);
        if (!empty($bindData)) {
            $userData = UserCommon::getUserByid($bindData['user_id']);//通过登录绑定的user_id获取用户密码
            if (!empty($userData) && !empty($userData['login_password']) && $userData['login_password'] === UserCommon::getUserLoginMd5Password($password)) {
                $userId = $userData['id'];
                $tokenArr = UserCommon::getUserLoginToken($userId, $userData['type']);
                //更新用户设备信息
                $deviceId = $this->saveUserLoginDevice($userId, $tokenArr['token'], $deviceArr, $appId);

                $this->insertUserLoginLog($userId, $bindData['id'], $deviceId, UserCommon::USER_LOGIN_TYPE_USERNAME, $deviceArr, $appId);//写入登录日志

                return ComBase::getReturnArray(['token' => $tokenArr['jwt_token']]);
            }
        }

        return ComBase::getParamsErrorReturnArray('账号或密码错误');
    }

    /**
     * 写入用户登录日志,$deviceArr必须通过getDeviceInfo获取保障内容不能为空
     * @param int $userId
     * @param int $bindId
     * @param int $deviceId
     * @param int $loginType
     * @param $deviceArr
     * @param int $appId 应用id默认0
     * @return bool
     */
    private function insertUserLoginLog($userId = 0, $bindId = 0, $deviceId = 0, $loginType = 0, $deviceArr, $appId = 0)
    {
        $deviceCode = $deviceArr['data']['device_code'] ?? '';//设备号
        $deviceType = $deviceArr['data']['device_type'] ?? 0;//设备类型
        $deviceSystem = $deviceArr['data']['system'] ?? '';//设备系统
        $deviceModel = $deviceArr['data']['model'] ?? '';//设备型号
        $deviceDesc = $deviceArr['data']['device_desc'] ?? '';//设备描述
        try {
            $insertSql = 'INSERT INTO {{%user_login_log}} (`user_id`,`app_id`,`bind_id`,`device_id`,`type`,`login_type`, `device_code`, `system`, `model`,`district`,`device_desc`,`ip`,`add_time`) VALUES (:user_id,:app_id,:bind_id,:device_id,:type,:login_type,:device_code,:system,:model,:district,:device_desc,:ip,:add_time);';
            $bindParams = [
                ':user_id' => $userId,
                ':app_id' => $appId,
                ':bind_id' => $bindId,
                ':device_id' => $deviceId,
                ':type' => $deviceType,
                ':login_type' => $loginType,
                ':device_code' => $deviceCode,
                ':system' => $deviceSystem,
                ':model' => $deviceModel,
                ':district' => '',
                ':device_desc' => $deviceDesc,
                ':ip' => Yii::$app->request->getRemoteIP(),
                ':add_time' => time(),
            ];
            Yii::$app->db->createCommand($insertSql, $bindParams)->execute();
        } catch (\Exception $ex) {
            $allArgs = func_get_args();
            Yii::error('写入登录日志错误:' . $ex->getMessage() . '_' . json_encode($allArgs));
        }

        return true;
    }

    private function getUserDefaultName()
    {
        return '用户_' . StringHandle::getRandomString(6, 'ACDEFGHIJKLMNOPQRSTUVWXYZ2356789acdefghijkmnpqrstuvwxyz');
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

        $appId = 0;//应用ID 0为默认 根据业务自行控制是否需要获取 搜索**app_id** 更换需要app_id逻辑的地方

        $deviceArr = $this->getDeviceInfo($data);//获取并判断设备信息,后续保存用户设备信息使用
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        if (empty($password1)) {
            return ComBase::getParamsFormatErrorReturnArray('密码不能为空');
        }

        //检查用户名
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{7,29}$/', $userName)) {
            return ComBase::getParamsFormatErrorReturnArray('用户名需由字母开头，字母数字和下划线组合，长度为8-30'); //字母开头区分手机号邮箱等内容防止用户名手机号邮箱用户名重合
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
            'name' => $this->getUserDefaultName(),
            'username' => $userName,
            'login_password' => UserCommon::getUserLoginMd5Password($password1),
            'status' => UserCommon::USER_STATUS_YES,
            'type' => UserCommon::USER_TYPE_REGISTER,
            'add_time' => $newTime,
            'app_id' => $appId,
        ];

        $token = '';
        $userId = 0;
        $bindId = 0;
        $deviceId = 0;
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert('{{%user}}', $userData)->execute(); //创建用户主表
            $userId = $db->getLastInsertID();//获取用户主表id
            $tokenArr = UserCommon::getUserLoginToken($userId, UserCommon::USER_TYPE_REGISTER, $appId);//获取用户登录token
            $bindId = $this->insertUserLoginBindWithPwd($userId, UserCommon::USER_LOGIN_TYPE_USERNAME, $userName, $appId); //写入用户登录绑定密码类
            $deviceId = $this->saveUserLoginDevice($userId, $tokenArr['token'], $deviceArr, $appId);//更新用户登录设备信息
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('registerByUsername 用户注册事务回滚:' . $e->getMessage());
            return ComBase::getServerBusyReturnArray();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error('registerByUsername 用户注册事务回滚:' . $e->getMessage());
            return ComBase::getServerBusyReturnArray();
        }

        $this->insertUserLoginLog($userId, $bindId, $deviceId, UserCommon::USER_LOGIN_TYPE_USERNAME, $deviceArr, $appId);//注册默认登录并写入登录日志

        return ComBase::getReturnArray(['token' => $tokenArr['jwt_token']]);
    }


}