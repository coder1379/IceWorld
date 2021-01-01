<?php


namespace common\services\account;

use common\lib\StringHandle;
use common\queues\SendMobileSmsJobs;
use common\services\application\AppCommon;
use common\services\captcha\CaptchaLogic;
use common\services\sms\SmsCommon;
use common\services\sms\SmsMobileApiModel;
use common\services\sms\SmsMobileLogic;
use Yii;
use common\ComBase;
use common\services\user\UserCommon;

/**
 * 账号逻辑封装用户登录注册等
 * @package common\services\account
 */
class AccountLogic
{

    /**
     * 检查密码是否符合要求
     * @param $password
     * @return array|bool
     */
    private function checkPasswordFormatReturnError($password)
    {
        //检查密码
        if (!preg_match('/^[a-zA-Z0-9~!@#$%&*_?]{6,30}$/', $password)) {
            return ComBase::getParamsFormatErrorReturnArray('密码格式错误，密码需由字母(区分大小写)数字和~!@#$%&*_?组合，长度为6-30');
        }

        return false;
    }

    /**
     * 获取设备信息 如有错误并返回错误信息
     * @param $params
     * @return array
     */
    private function getDeviceInfo($params)
    {
        $deviceType = ComBase::getIntVal('device_type', $params); //设备类型
        if (!in_array($deviceType, AccountCommon::DEVICE_ARR, true)) {
            return ComBase::getParamsErrorReturnArray('访问类型参数错误');
        }
        $deviceCode = '';
        $userDeviceSystem = '';
        $userDeviceModel = '';
        $deviceDesc = '';

        if ($deviceType === AccountCommon::DEVICE_TYPE_WEB) {
            $deviceDesc = Yii::$app->request->getUserAgent();
            $deviceCode = trim(ComBase::getStrVal('device_code', $params));
            $userDeviceSystem = StringHandle::getWebSystem($deviceDesc);
            $userDeviceModel = StringHandle::getBrowser($deviceDesc);
        } else {
            $deviceCode = trim(ComBase::getStrVal('device_code', $params));
            $userDeviceSystem = trim(ComBase::getStrVal('system', $params));
            $userDeviceModel = trim(ComBase::getStrVal('model', $params));
        }
        if (empty($deviceCode) || empty($userDeviceSystem) || empty($userDeviceModel)) {
            return ComBase::getParamsErrorReturnArray('访问参数无效');
        }
        return ComBase::getReturnArray(['device_type' => $deviceType, 'device_code' => $deviceCode, 'system' => $userDeviceSystem, 'model' => $userDeviceModel, 'device_desc' => $deviceDesc]);
    }

    /**
     * 写入用户密码类登录绑定表:手机号验证码登录也归属于这个表
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
     * @param $params
     * @return array
     */
    public function deviceTokenRenewal($params)
    {
        $userId = 0; //续签的用户id不会串过来所以重新获取
        $shortToken = null;
        $oldToken = Yii::$app->request->post('token', '');

        $appId = AppCommon::getAppId($params);

        if (!empty($oldToken) && strlen($oldToken) < 500) {
            $jwtUser = AccountCommon::decodeUserLoginToken($oldToken);
            if (!empty($jwtUser)) {
                $nowTime = time();
                $userId = $jwtUser->u_i ?? 0;
                $userId = intval($userId);
                $jwtTime = $jwtUser->o_t ?? 0;
                $jwtTime = intval($jwtTime);
                $exMaxTime = $nowTime - $jwtTime;
                $jwtUserType = $jwtUser->u_t ?? -1;
                $jwtUserType = intval($jwtUserType);

                if (empty($userId)) {
                    //用户为空直接返回未登录
                    return ComBase::getNoLoginReturnArray();
                }


                $lastExTime = $jwtTime - $nowTime;
                if (!empty($jwtTime)) {//$jwtTime = 0为永久有效，但在续签的业务中如果调用了续签还是生成新的jwt-token防止用户状态不刷新
                    if ($lastExTime > 3600) {
                        return ComBase::getReturnArray(['user_type'=>$jwtUserType,'token' => $oldToken]);//如果过期时间为0或者离过期超过1小时则直接返回当前token,防止无意义刷新
                    } else if ($exMaxTime > 2592000) { //****如果过期时间已经超过30天则不能再进行续签，直接过期重新登录 具体时间自行调整****
                        if($jwtUserType !== UserCommon::TYPE_DEVICE_VISITOR){ //非游客续签直接不返回重新登录
                            return ComBase::getNoLoginReturnArray();
                        }
                    }
                }

                if($jwtUserType === UserCommon::TYPE_DEVICE_VISITOR){ //设备游客续签，直接调用获取游客token结束流程
                    return $this->getVisitorToken($params);
                }

                $tempTokenArr = explode('.', $oldToken);
                $shortToken = end($tempTokenArr);
                if (!empty($shortToken)) {
                    $deviceArr = $this->getDeviceInfo($params);

                    if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
                        return ComBase::getNoLoginReturnArray($deviceArr['msg']);
                    }

                    try {
                        $deviceData = AccountCommon::getAccountDeviceByUserIdToken($userId, $shortToken, $appId);//获取设备信息
                        $userData = UserCommon::getUserByid($userId);//获取用户信息判断是否续签，防止问题用户无限续签
                        //成功续签前检查是否有禁止登录等内容
                        $checkArr = AccountCommon::getBeforeLoginErrorCheck($userData);
                        if ($checkArr !== false) {
                            return $checkArr;
                        }

                        $userType = intval($userData['type']);
                        if (!empty($deviceData) && !empty($userData)) {
                            $userStatus = intval($userData['status']);
                            if ($userStatus === UserCommon::STATUS_YES) {//只有状态正常的用户才能续签
                                $tokenArr = AccountCommon::getUserLoginToken($userId, $userData['type'], $appId);
                                $deviceType = $deviceArr['data']['device_type'];//设备类型
                                $deviceCode = $deviceArr['data']['device_code'] ?? '';//设备号
                                $dbDeviceType = intval($deviceData['type']);

                                if (!empty($deviceCode) && $deviceType === $dbDeviceType && $deviceCode === $deviceData['device_code']) {
                                    //设备code相同直接更新,刷新需要判断devicetype是否相同,防止刷新了app的token
                                    $updateFlg = $this->updateUserLoginDevice($deviceData['id'], $tokenArr['token']);
                                    if (!empty($updateFlg)) {
                                        return ComBase::getReturnArray(['user_type'=>$userType,'token' => $tokenArr['jwt_token']]);//返回新的jwt-token
                                    }
                                } else {

                                    if ($deviceType === AccountCommon::DEVICE_TYPE_WEB) {
                                        $deviceDataWeb = $this->getUserLoginDeviceByDeviceCode($userId, $deviceCode, $appId);//获取设备信息
                                        if (!empty($deviceDataWeb)) {
                                            $webDeviceType = intval($deviceDataWeb['type'] ?? 0);
                                            if ($webDeviceType === AccountCommon::DEVICE_TYPE_WEB) {
                                                $updateFlg = $this->updateUserLoginDevice($deviceDataWeb['id'], $tokenArr['token']);//浏览器设备号相同视为同一个浏览器直接更新旧token
                                                if (!empty($updateFlg)) {
                                                    return ComBase::getReturnArray(['user_type'=>$userType,'token' => $tokenArr['jwt_token']]);//返回新的jwt-token
                                                }
                                            }

                                        } else {
                                            //浏览器类型可以直接续签新浏览器主要用于app内打开网页场景
                                            $deviceId = $this->insertUserLoginDevice($userId, $deviceArr, $tokenArr['token'], $appId);
                                            if (!empty($deviceId)) {
                                                return ComBase::getReturnArray(['user_type'=>$userType,'token' => $tokenArr['jwt_token']]);//返回新的jwt-token
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
     * 账号密码登录(根据正则判断用户名类型是username,mobile,email)
     * @param $params
     * @return array
     */
    public function loginByAccountPwd($params)
    {
        $userName = trim(ComBase::getStrVal('username', $params));
        $password = trim(ComBase::getStrVal('password', $params));
        if (empty($userName) || empty($password)) {
            return ComBase::getParamsErrorReturnArray('账号或密码不能为空');
        }

        $areaCode = SmsCommon::getMobileAreaCode(ComBase::getStrVal('area_code', $params));

        //先检查用户名 防止国际手机号加入-后验证混乱
        if (!preg_match('/^[a-zA-Z0-9@._]{8,50}$/', $userName)) {
            return ComBase::getParamsFormatErrorReturnArray('账号格式错误');
        }

        if (empty($areaCode) || $areaCode === 86) {
            //区号为空或者86表示大陆不处理
        } else {
            //国际手机号需要将区号通过-添加到mobile前作为用户名查找
            $userName = AccountCommon::getSaveMobile($userName, $areaCode);
        }

        ////判断账号类型严格验证登录防止错误登录其他账号情况（理论上不会发生但还是进行严格校验）
        $accuntType = AccountCommon::LOGIN_TYPE_USERNAME; //默认为用户名
        $tempUserName = str_replace('-', '', $userName);
        if (is_numeric($userName) || is_numeric($tempUserName)) {
            $accuntType = AccountCommon::LOGIN_TYPE_MOBILE;
            //为手机号模式可以加入更多严格验证

        } else if (!empty(filter_var($userName, FILTER_VALIDATE_EMAIL))) {
            $accuntType = AccountCommon::LOGIN_TYPE_EMAIL;
            //为邮箱模式可以加入更多严格验证

        } else {
            //username类型判断 可扩展更为严格的验证方式

        }

        $appId = AppCommon::getAppId($params);

        //检查密码
        $pwdRes = $this->checkPasswordFormatReturnError($password);
        if ($pwdRes !== false) {
            return $pwdRes;
        }

        $deviceArr = $this->getDeviceInfo($params);
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        $bindData = AccountCommon::getUserLoginBindWithKeyType($userName, $accuntType, $appId);
        if (!empty($bindData)) {
            $userData = UserCommon::getUserByid($bindData['user_id']);//通过登录绑定的user_id获取用户密码
            if (!empty($userData) && !empty($userData['login_password']) && $userData['login_password'] === AccountCommon::getUserLoginMd5Password($password)) {

                //成功登录前检查是否有禁止登录等内容
                $checkArr = AccountCommon::getBeforeLoginErrorCheck($userData);
                if ($checkArr !== false) {
                    return $checkArr;
                }

                $userId = $userData['id'];
                $tokenArr = AccountCommon::getUserLoginToken($userId, $userData['type'], $appId);
                //更新用户设备信息
                $deviceId = $this->saveUserLoginDevice($userId, $tokenArr['token'], $deviceArr, $appId);

                $this->insertUserLoginLog($userId, $bindData['id'], $deviceId, AccountCommon::LOGIN_TYPE_USERNAME, $deviceArr, $appId);//写入登录日志

                return ComBase::getReturnArray(['user_type'=>intval($userData['type']),'token' => $tokenArr['jwt_token']]);
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
        return '用户_' . StringHandle::getRandomString(6, 'ACDEFGHIJKLMNOPQRSTUVWXYZ2356789');
    }

    /**
     * 通过用户名密码注册
     * @param array $params
     * @return array
     */
    public function registerByUsername($params)
    {
        if (empty($params) || empty($params['username'])) {
            return ComBase::getParamsErrorReturnArray('用户名，密码参数错误');
        }

        $userName = trim(ComBase::getStrVal('username', $params));
        $password1 = trim(ComBase::getStrVal('password1', $params));
        $password2 = trim(ComBase::getStrVal('password2', $params));

        $appId = AppCommon::getAppId($params);

        $deviceArr = $this->getDeviceInfo($params);//获取并判断设备信息,后续保存用户设备信息使用
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
        $pwdRes = $this->checkPasswordFormatReturnError($password1);
        if ($pwdRes !== false) {
            return $pwdRes;
        }

        if ($password1 !== $password2) {
            return ComBase::getParamsFormatErrorReturnArray('两次密码不匹配');
        }

        $bindUser = AccountCommon::getUserLoginBindWithPwdTypes($userName, $appId);
        if (!empty($bindUser)) {
            return ComBase::getParamsFormatErrorReturnArray('用户名已经存在');
        }

        //创建user并设置绑定
        //user表数据
        $newTime = time();

        $userData = [
            'name' => $this->getUserDefaultName(),
            'username' => $userName,
            'login_password' => AccountCommon::getUserLoginMd5Password($password1),
            'status' => UserCommon::STATUS_YES,
            'type' => UserCommon::TYPE_REGISTER,
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
            $tokenArr = AccountCommon::getUserLoginToken($userId, UserCommon::TYPE_REGISTER, $appId);//获取用户登录token
            $bindId = $this->insertUserLoginBindWithPwd($userId, AccountCommon::LOGIN_TYPE_USERNAME, $userName, $appId); //写入用户登录绑定密码类
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

        $this->insertUserLoginLog($userId, $bindId, $deviceId, AccountCommon::LOGIN_TYPE_USERNAME, $deviceArr, $appId);//注册默认登录并写入登录日志

        return ComBase::getReturnArray(['user_type'=>UserCommon::TYPE_REGISTER,'token' => $tokenArr['jwt_token']]);
    }


    /**
     * 统一获取验证码缓存key便于维护是否进行场景验证
     * @param $appId
     * @param $scene
     * @param $mobile
     * @return string
     */
    private function getCapchaCacheKey($appId, $scene, $mobile)
    {
        //此处统一获取缓存的key，默认按模式进行了区分，如果需要区分验证码模式可以手动删除$scene拼接，但需要注意limit和缓存过个场景公用一个问题
        return $appId . '_' . $scene . '_' . $mobile;
    }

    /**
     * 发送手机验证码
     * @param $params
     * @return array
     */
    public function sendMobileCode($params)
    {
        $mobile = ComBase::getStrVal('mobile', $params);
        $areaCode = SmsCommon::getMobileAreaCode(ComBase::getStrVal('area_code', $params));
        $scene = ComBase::getIntVal('scene', $params);

        if (empty($mobile) || !in_array($scene, SmsCommon::CODE_SCENE_LIST, true)) {
            return ComBase::getParamsErrorReturnArray();
        }

        //检查手机号
        $checkRes = AccountCommon::getMobileFormatReturnError($mobile, $areaCode);
        if ($checkRes !== false) {
            return $checkRes;
        }

        $appId = AppCommon::getAppId($params);

        $saveMobile = AccountCommon::getSaveMobile($mobile, $areaCode);

        $bindUser = AccountCommon::getUserLoginBindWithPwdTypes($saveMobile, $appId);
        if ($scene === SmsCommon::CODE_SCENE_REGISTER) {
            if (!empty($bindUser)) {
                return ComBase::getParamsErrorReturnArray('手机号已经注册');
            }
        } else {
            if (empty($bindUser)) {
                return ComBase::getParamsErrorReturnArray('手机号还未注册');
            }
        }


        $nowTime = time();
        $key = $this->getCapchaCacheKey($appId, $scene, $saveMobile);

        $capLogin = new CaptchaLogic();
        $nextTime = $capLogin->getKeyLimitTime($key);
        if ($nextTime === 0) {
            $bool = $capLogin->setKeyLimitTime($key, $nowTime, $capLogin->sendCodeSplitTime);
            if (empty($bool)) {
                return ComBase::getServerBusyReturnArray('验证码发送失败，请重试');
            }
        } else {
            return ComBase::getReturnArray([], ComBase::CODE_REQUEST_INVALID, '距离下次发送验证码还有' . $nextTime . '秒');
        }

        //创建异步发送验证码任务
        $sendCode = StringHandle::getRandomNumber(6); //生产6位数字字符
        $name = SmsCommon::SCENE_STR_LIST[$scene] ?? '场景未配置';
        $paramsJson = [
            'code' => $sendCode,
        ];

        $saveParams = [
            'name' => '验证码:' . $name,
            'mobile' => $mobile,//发送验证码直接使用区号所有不保存待-的拼接数据
            'area_code' => $areaCode,
            'content' => SmsCommon::MOBILE_CAPTCHA_SENT_STR,
            'params_json' => json_encode($paramsJson),
            'type' => SmsCommon::TYPE_CAPTCHA,
            'send_type' => SmsCommon::SEND_TYPE_USER,
            'sms_type' => SmsCommon::MOBILE_TYPE_AUTO,
            'template' => SmsCommon::MOBILE_CAPTCHA_SENT_TEMPLATE,
            'add_time' => $nowTime,
            'status' => SmsCommon::STATUS_WAIT_SEND,
        ];

        $bool = $capLogin->setSendCodeCache($key, $sendCode);
        if (empty($bool)) {
            return ComBase::getServerBusyReturnArray('验证码发送失败，请重试');
        }

        Yii::$app->db->createCommand()->insert('{{%sms_mobile}}', $saveParams)->execute();
        $lastId = Yii::$app->db->getLastInsertID();
        Yii::$app->queue->push(new SendMobileSmsJobs(['id' => $lastId]));

        return ComBase::getReturnArray();
    }

    /**
     * 通过手机号注册
     * @param array $params
     * @return array
     */
    public function registerByMobile($params)
    {
        if (empty($params) || empty($params['mobile']) || empty($params['code'])) {
            return ComBase::getParamsErrorReturnArray('手机号或验证码参数错误');
        }

        $mobile = trim(ComBase::getStrVal('mobile', $params));
        $areaCode = SmsCommon::getMobileAreaCode(ComBase::getStrVal('area_code', $params));
        $code = ComBase::getStrVal('code', $params);

        $appId = AppCommon::getAppId($params);

        $deviceArr = $this->getDeviceInfo($params);//获取并判断设备信息,后续保存用户设备信息使用
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        //检查手机号格式
        $checkRes = AccountCommon::getMobileFormatReturnError($mobile, $areaCode);
        if ($checkRes !== false) {
            return $checkRes;
        }

        $saveMobile = AccountCommon::getSaveMobile($mobile, $areaCode);

        $key = $this->getCapchaCacheKey($appId, SmsCommon::CODE_SCENE_REGISTER, $saveMobile);
        $capLog = new CaptchaLogic();
        $saveCode = strval($capLog->getSendCodeCache($key));

        if (!empty($saveCode) && !empty($code) && $saveCode === $code) {
            //验证码相同

            $bindUser = AccountCommon::getUserLoginBindWithPwdTypes($saveMobile, $appId);
            if (!empty($bindUser)) {
                return ComBase::getParamsFormatErrorReturnArray('手机号已经注册');
            }

            //创建user并设置绑定
            //user表数据
            $newTime = time();

            $userData = [
                'app_id' => $appId,
                'name' => $this->getUserDefaultName(),
                'area_code' => $areaCode,
                'mobile' => $saveMobile,
                'status' => UserCommon::STATUS_YES,
                'type' => UserCommon::TYPE_REGISTER,
                'add_time' => $newTime,
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
                $tokenArr = AccountCommon::getUserLoginToken($userId, UserCommon::TYPE_REGISTER, $appId);//获取用户登录token
                $bindId = $this->insertUserLoginBindWithPwd($userId, AccountCommon::LOGIN_TYPE_MOBILE, $saveMobile, $appId); //写入用户登录绑定密码类
                $deviceId = $this->saveUserLoginDevice($userId, $tokenArr['token'], $deviceArr, $appId);//更新用户登录设备信息
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error('registerByMobile 用户手机号注册事务回滚:' . $e->getMessage());
                return ComBase::getServerBusyReturnArray();
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::error('registerByMobile 用户手机号注册事务回滚:' . $e->getMessage());
                return ComBase::getServerBusyReturnArray();
            }

            $this->insertUserLoginLog($userId, $bindId, $deviceId, AccountCommon::LOGIN_TYPE_MOBILE, $deviceArr, $appId);//注册默认登录并写入登录日志
            $capLog->deleteSendCodeCache($key);
            return ComBase::getReturnArray(['user_type'=>UserCommon::TYPE_REGISTER,'token' => $tokenArr['jwt_token']]);
        }

        return ComBase::getParamsFormatErrorReturnArray('验证码错误');

    }

    /**
     * 手机号验证码登录
     * @param $params
     * @return array
     */
    public function loginByMobileCode($params)
    {

        if (empty($params) || empty($params['mobile']) || empty($params['code'])) {
            return ComBase::getParamsErrorReturnArray('手机号或验证码参数错误');
        }

        $mobile = trim(ComBase::getStrVal('mobile', $params));
        $areaCode = SmsCommon::getMobileAreaCode(ComBase::getStrVal('area_code', $params));
        $code = ComBase::getStrVal('code', $params);

        $appId = AppCommon::getAppId($params);

        $deviceArr = $this->getDeviceInfo($params);//获取并判断设备信息,后续保存用户设备信息使用
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        //检查手机号格式
        $checkRes = AccountCommon::getMobileFormatReturnError($mobile, $areaCode);
        if ($checkRes !== false) {
            return $checkRes;
        }

        $saveMobile = AccountCommon::getSaveMobile($mobile, $areaCode);

        $key = $this->getCapchaCacheKey($appId, SmsCommon::CODE_SCENE_LOGIN, $saveMobile);
        $capLog = new CaptchaLogic();
        $saveCode = strval($capLog->getSendCodeCache($key));

        if (!empty($saveCode) && !empty($code) && $saveCode === $code) {
            //验证码相同
            $bindData = AccountCommon::getUserLoginBindWithKeyType($saveMobile, AccountCommon::LOGIN_TYPE_MOBILE, $appId);
            if (!empty($bindData)) {
                $userData = UserCommon::getUserByid($bindData['user_id']);//通过登录绑定的user_id获取用户信息

                //成功登录前检查是否有禁止登录等内容
                $checkArr = AccountCommon::getBeforeLoginErrorCheck($userData);
                if ($checkArr !== false) {
                    return $checkArr;
                }
                $userId = $userData['id'];
                $tokenArr = AccountCommon::getUserLoginToken($userId, $userData['type'], $appId);
                //更新用户设备信息
                $deviceId = $this->saveUserLoginDevice($userId, $tokenArr['token'], $deviceArr, $appId);

                $this->insertUserLoginLog($userId, $bindData['id'], $deviceId, AccountCommon::LOGIN_TYPE_MOBILE, $deviceArr, $appId);//写入登录日志

                return ComBase::getReturnArray(['user_type'=>intval($userData['type']),'token' => $tokenArr['jwt_token']]);

            }
        }

        return ComBase::getParamsErrorReturnArray('手机号或验证码错误');
    }


    /**
     * 获取游客token(游客token没有过期时间,没有短token)
     * 查询device_code+user_id=0 + app_id 无记录则添加,获取id后生成token返回前端
     * 游客token仅用作转化统计的记录与未登录没有区别
     * @param $params
     */
    public function getVisitorToken($params)
    {
        $deviceArr = $this->getDeviceInfo($params);//获取并判断设备信息,后续保存用户设备信息使用
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $deviceArr;
        }

        $deviceCode = $deviceArr['data']['device_code'] ?? '';//设备唯一号
        if (empty($deviceCode)) {
            return ComBase::getParamsErrorReturnArray();
        }
        $deviceType = $deviceArr['data']['device_type'] ?? 0;//设备类型
        $deviceSystem = $deviceArr['data']['system'] ?? '';//设备系统
        $deviceModel = $deviceArr['data']['model'] ?? '';//设备型号
        $deviceDesc = $deviceArr['data']['device_desc'] ?? '';//设备描述
        $ipStr = Yii::$app->request->getRemoteIP();
        $appId = AppCommon::getAppId($params);

        $sql = 'select id,user_id,app_id from {{%device_visitor}} force index(device_code) where device_code=:device_code and user_id=0 and app_id=:app_id limit 1';
        $deviceUserId = 0;
        $visitorData = Yii::$app->db->createCommand($sql, [':device_code' => $deviceCode, ':app_id' => $appId])->queryOne();
        if (empty($visitorData)) {
            //设备游客用户不存在直接新建
            $insertSql = 'INSERT INTO {{%device_visitor}} (`device_code`, `app_id`, `type`, `system`, `model`,`device_desc`,`ip`,`add_time`) VALUES (:device_code,:app_id,:type,:system,:model,:device_desc,:ip,:add_time);';
            $inserArr = [
                ':device_code' => $deviceCode,
                ':app_id' => $appId,
                ':type' => $deviceType,
                ':system' => $deviceSystem,
                ':model' => $deviceModel,
                ':device_desc' => $deviceDesc,
                ':ip' => $ipStr,
                ':add_time' => time()
            ];
            Yii::$app->db->createCommand($insertSql, $inserArr)->execute();
            $deviceUserId = Yii::$app->db->getLastInsertID();
        } else {
            $deviceUserId = $visitorData['id'];
        }

        $tokenArr = AccountCommon::getUserLoginToken($deviceUserId, UserCommon::TYPE_DEVICE_VISITOR, $appId);

        return ComBase::getReturnArray(['user_type'=>UserCommon::TYPE_DEVICE_VISITOR,'token' => $tokenArr['jwt_token']]);
    }

}