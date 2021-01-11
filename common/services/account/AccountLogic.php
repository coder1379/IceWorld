<?php


namespace common\services\account;

use common\lib\StringHandle;
use common\queues\SendMobileSmsJobs;
use common\services\application\AppCommon;
use common\services\captcha\CaptchaLogic;
use common\services\sms\SmsCommon;
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

    /**
     * 写入用户第三方登录绑定表:微信，qq，微博，苹果等
     * @param $userId
     * @param $type
     * @param $key
     * @param $thirdUserData 第三方用户信息,字段同数据库
     * @param int $appId 应用id
     * @return bool
     * @throws \yii\db\Exception
     */
    private function insertUserLoginBindWithThird($userId, $type, $key, $thirdUserData, $appId = 0)
    {
        if (empty($userId) || empty($type) || empty($key) || empty($thirdUserData)) {
            $allArgs = func_get_args();
            throw new \Exception('insertUserLoginBindWithThird 写入用户第三方登录绑定表参数不能为空:' . json_encode($allArgs));
        }

        $bindUnionid = $thirdUserData['bind_unionid'] ?? '';
        $bindNum = $thirdUserData['bind_num'] ?? '';
        $bindNickname = $thirdUserData['bind_nickname'] ?? '';
        $bindAvatar = $thirdUserData['bind_avatar'] ?? '';
        $bindSex = intval($thirdUserData['bind_sex'] ?? 0);// 1男 2女，0未知
        $bindBirthday = $thirdUserData['bind_birthday'] ?? 0;//生日未时间戳
        $bindDistrict = $thirdUserData['bind_district'] ?? '';


        $saveArr = [
            ':user_id' => $userId,
            ':type' => $type,
            ':bind_key' => $key,
            ':app_id' => $appId,
            ':bind_unionid' => $bindUnionid,
            ':bind_num' => $bindNum,
            ':bind_nickname' => $bindNickname,
            ':bind_avatar' => $bindAvatar,
            ':bind_sex' => $bindSex,
            ':bind_birthday' => $bindBirthday,
            ':bind_district' => $bindDistrict,
        ];

        $insertSql = 'INSERT INTO {{%user_login_bind_third}} (`user_id`, `type`, `bind_key`,`app_id`,`bind_unionid`,`bind_num`,`bind_nickname`,`bind_avatar`,`bind_sex`,`bind_birthday`,`bind_district`) VALUES (:user_id,:type,:bind_key,:app_id,:bind_unionid,:bind_num,:bind_nickname,:bind_avatar,:bind_sex,:bind_birthday,:bind_district);';
        Yii::$app->db->createCommand($insertSql, $saveArr)->execute();
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
     * 设备token续签,正式用户jwt返回重新登录,
     * @param $params
     * @return array
     */
    public function deviceTokenRenewal($params)
    {
        $userId = 0; //续签的用户id不会串过来所以重新获取
        $shortToken = null;
        $oldToken = Yii::$app->request->post('token', '');
        $oldToken = strval($oldToken);
        $appId = AppCommon::getAppId($params);

        $deviceArr = $this->getDeviceInfo($params); //优先验证参数防止后续直接返回游客token无法生成对应数据
        if ($deviceArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return ComBase::getParamsErrorReturnArray($deviceArr['msg']);
        }

        if (!empty($oldToken) && strlen($oldToken) < 500) {
            $jwtUser = AccountCommon::decodeUserLoginToken($oldToken);
            if (!empty($jwtUser)) {
                $nowTime = time();
                $userId = $jwtUser->u_i ?? 0;
                $userId = intval($userId);
                $jwtTime = $jwtUser->o_t ?? 0;
                $jwtTime = intval($jwtTime);
                $exMaxTime = $nowTime - $jwtTime;
                $jwtUserType = $jwtUser->u_t ?? null;

                if (!empty($userId) && $jwtUserType != null) { //userId,userType无效直接结束
                    $jwtUserType = intval($jwtUserType);
                    $lastExTime = $jwtTime - $nowTime;
                    if (!empty($jwtTime)) {//$jwtTime = 0为永久有效，但在续签的业务中如果调用了续签还是生成新的jwt-token防止用户状态不刷新
                        if ($lastExTime > Yii::$app->params['jwt']['jwt_refresh_min_time']) {
                            return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat($jwtUserType, $oldToken, ['id' => $userId], 1));//防止无意义刷新,将yuan
                        } else if ($exMaxTime > Yii::$app->params['jwt']['jwt_refresh_max_time']) { //防止超长时间过期刷新
                            if ($jwtUserType !== UserCommon::TYPE_DEVICE_VISITOR) { //非游客续签直接根据配置返回
                                return $this->getRenewalFailReturnArray($params);
                            }
                        }
                    }

                    if ($jwtUserType === UserCommon::TYPE_DEVICE_VISITOR) { //设备游客续签，直接调用获取游客token结束流程
                        return $this->getVisitorToken($params);
                    }

                    $tempTokenArr = explode('.', $oldToken);
                    $shortToken = end($tempTokenArr);
                    if (!empty($shortToken)) {

                        try {
                            $deviceData = AccountCommon::getAccountDeviceByUserIdToken($userId, $shortToken, $appId);//获取设备信息
                            $userData = UserCommon::getUserByid($userId);//获取用户信息判断是否续签，防止问题用户无限续签
                            //成功续签前检查是否有禁止登录等内容
                            $checkArr = AccountCommon::getBeforeLoginErrorCheck($userData);
                            if ($checkArr !== false) {
                                return $this->getRenewalFailReturnArray($params);//如果状态有异常则根据游客配置返回不同结果
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
                                            return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat($userType, $tokenArr['jwt_token'], $userData));//返回新的jwt_token及其他格式化数据
                                        }
                                    } else {

                                        if ($deviceType === AccountCommon::DEVICE_TYPE_WEB) {
                                            $deviceDataWeb = $this->getUserLoginDeviceByDeviceCode($userId, $deviceCode, $appId);//获取设备信息
                                            if (!empty($deviceDataWeb)) {
                                                $webDeviceType = intval($deviceDataWeb['type'] ?? 0);
                                                if ($webDeviceType === AccountCommon::DEVICE_TYPE_WEB) {
                                                    $updateFlg = $this->updateUserLoginDevice($deviceDataWeb['id'], $tokenArr['token']);//浏览器设备号相同视为同一个浏览器直接更新旧token
                                                    if (!empty($updateFlg)) {
                                                        return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat($userType, $tokenArr['jwt_token'], $userData));//返回新的jwt_token及其他格式化数据
                                                    }
                                                }

                                            } else {
                                                //浏览器类型可以直接续签新浏览器主要用于app内打开网页场景
                                                $deviceId = $this->insertUserLoginDevice($userId, $deviceArr, $tokenArr['token'], $appId);
                                                if (!empty($deviceId)) {
                                                    return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat($userType, $tokenArr['jwt_token'], $userData));//返回新的jwt_token及其他格式化数据
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
        }

        return $this->getRenewalFailReturnArray($params);
    }

    /**
     * 获取续签失败的返回值，只要判断是否开启游客模式
     */
    private function getRenewalFailReturnArray($params)
    {
        if (Yii::$app->params['jwt']['jwt_device_visitor_verification'] === true) { //开启了游客模式不返回重新登录而是直接返回一个有效的新游客token，在用这个游客token去访问具体业务的时候返回是否需要重新登录
            return $this->getVisitorToken($params);
        }
        return ComBase::getNoLoginReturnArray();
    }

    /**
     * 账号密码登录(根据正则判断用户名类型是username,mobile,email)
     * @param $params
     * @param $visitorId 游客id
     * @return array
     */
    public function loginByAccountPwd($params,$visitorId=0)
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

                $this->loginSuccessCall($userId); //登录成功扩展预留

                return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat($userData['type'], $tokenArr['jwt_token'], $userData));
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
     * @param int $visitorId 游客id
     * @return array
     */
    public function registerByUsername($params,$visitorId=0)
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
            'source_channel_id' => intval(ComBase::getIntVal('source_channel_id', $params)),//来源渠道ID,如果是传递字符串自行实现ID查询对应 m_source_channel table
        ];

        //统一调用用户注册事务便于维护返回类事接口返回数组
        $saveReturnArr = $this->saveRegisterUserDataTransaction($appId, AccountCommon::LOGIN_TYPE_USERNAME, $userName, $userData, $deviceArr, $visitorId);
        if ($saveReturnArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
            return $saveReturnArr;
        } else {
            $this->insertUserLoginLog($saveReturnArr['data']['user_id'], $saveReturnArr['data']['bind_id'], $saveReturnArr['data']['device_id'], AccountCommon::LOGIN_TYPE_USERNAME, $deviceArr, $appId);//注册默认登录并写入登录日志

            $userData['id'] = $saveReturnArr['data']['user_id'];
            $this->registerSuccessCall($userData['id'],$visitorId);//注册成功扩展调用

            return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat(UserCommon::TYPE_REGISTER, $saveReturnArr['data']['jwt_token'], $userData));//注册成功即表示登录了，同时返回数据
        }
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
     * @param int $visitorId 游客id
     * @return array
     */
    public function sendMobileCode($params,$visitorId=0)
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
     * @param int $visitorId 游客id
     * @return array
     */
    public function registerByMobile($params, $visitorId)
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
                'source_channel_id' => intval(ComBase::getIntVal('source_channel_id', $params)),//来源渠道ID,如果是传递字符串自行实现ID查询对应 m_source_channel table
            ];

            //统一调用用户注册事务便于维护返回类事接口返回数组
            $saveReturnArr = $this->saveRegisterUserDataTransaction($appId, AccountCommon::LOGIN_TYPE_MOBILE, $saveMobile, $userData, $deviceArr, $visitorId);
            if ($saveReturnArr['code'] !== ComBase::CODE_RUN_SUCCESS) {
                return $saveReturnArr;
            } else {
                $this->insertUserLoginLog($saveReturnArr['data']['user_id'], $saveReturnArr['data']['bind_id'], $saveReturnArr['data']['device_id'], AccountCommon::LOGIN_TYPE_MOBILE, $deviceArr, $appId);//注册默认登录并写入登录日志

                $capLog->deleteSendCodeCache($key);//删除当前验证码
                $userData['id'] = $saveReturnArr['data']['user_id'];
                $this->registerSuccessCall($userData['id'],$visitorId);//注册成功扩展调用

                return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat(UserCommon::TYPE_REGISTER, $saveReturnArr['data']['jwt_token'], $userData));//注册成功即表示登录了，同时返回数据
            }

        }

        return ComBase::getParamsFormatErrorReturnArray('验证码错误');

    }


    /**
     * 注册成功调用扩展
     * @param int $userId
     * @param int $visitorId
     * @return bool
     */
    private function registerSuccessCall($userId=0,$visitorId=0){
        //注册成功后的预留扩展，例如后续注册成功与渠道相关关系等,自行维护

        return true;
    }

    /**
     * 登录成功调用扩展
     * @param int $userId
     * @return bool
     */
    private function loginSuccessCall($userId=0){
        //登录成功后的预留扩展，例如后续登录成功与积分相关功能等

        return true;
    }

    /**
     * 统一封装用户注册事务便于维护与扩展,返回http参数规则
     * @param $appId 应用id 传入，必须
     * @param $registerType 注册类型必须如:AccountCommon::LOGIN_TYPE_MOBILE,LOGIN_TYPE_USERNAME,LOGIN_TYPE_WECHAT等
     * @param $bindSaveKey 保存类型对应key,用户名,手机号，微信唯一key，QQ唯一key等
     * @param $userData 保存的用户主表数据 外部初始化完成传入
     * @param $deviceArr 设备相关参数外部校验完成传入
     * @param int $visitorId 游客id，可为0
     * @param array $thirdUserData 第三方用户信息字段同user_login_bind_third,第三方登录注册使用
     * @return array
     */
    private function saveRegisterUserDataTransaction($appId, $registerType, $bindSaveKey, $userData, $deviceArr, $visitorId = 0, $thirdUserData = null)
    {

        if (empty($registerType) || empty($bindSaveKey) || empty($userData) || empty($deviceArr)) {
            //理论不会进入,如果发生记录错误日志并返回前端提示
            $allArgs = func_get_args();
            Yii::error('saveRegisterUserDataTransaction 注册用户参数错误:' . json_encode($allArgs));
            return ComBase::getParamsErrorReturnArray('注册参数校验异常，请联系客服');
        }

        if (!in_array($registerType, AccountCommon::USER_LOGIN_BIND_LIST, true) && !in_array($registerType, AccountCommon::USER_LOGIN_BIND_THIRD_LIST, true)) {
            //理论不会进入,如果发生记录错误日志并返回前端提示
            $allArgs = func_get_args();
            Yii::error('saveRegisterUserDataTransaction 注册用户类型参数错误:' . json_encode($allArgs));
            return ComBase::getParamsErrorReturnArray('不支持的注册类型，请联系客服');
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert('{{%user}}', $userData)->execute(); //创建用户主表
            $userId = $db->getLastInsertID();//获取用户主表id

            $tokenArr = AccountCommon::getUserLoginToken($userId, UserCommon::TYPE_REGISTER, $appId);//获取用户登录token

            //用户注册更多相关内容扩展区域，例如维护一个用户扩展表是要一同初始化等


            //此处根据用户注册类型自动判断是放入用户名手机号绑定表还是第三方绑定表
            $bindId = 0;
            if (in_array($registerType, AccountCommon::USER_LOGIN_BIND_LIST, true)) {
                //普通登录绑定表
                $bindId = $this->insertUserLoginBindWithPwd($userId, $registerType, $bindSaveKey, $appId);
            } else if (in_array($registerType, AccountCommon::USER_LOGIN_BIND_THIRD_LIST, true)) {
                //第三方登录绑定表
                $bindId = $this->insertUserLoginBindWithThird($userId, $registerType, $bindSaveKey, $thirdUserData, $appId);
            }

            $deviceId = $this->saveUserLoginDevice($userId, $tokenArr['token'], $deviceArr, $appId);//更新用户登录设备信息
            $transaction->commit();

            if(!empty($visitorId)){//游客id不为空注册成功后更新游客表，便于统计转化率
                Yii::$app->db->createCommand('update {{%device_visitor}} set user_id=:user_id,convert_time=:convert_time where id=:id and user_id=0', [':user_id' => $userId,':convert_time'=>time(), ':id' => $visitorId])->execute();
            }

            //事务完成返回数组
            return ComBase::getReturnArray(['jwt_token' => $tokenArr['jwt_token'], 'user_id' => $userId, 'device_id' => $deviceId, 'bind_id' => $bindId]);

        } catch (\Exception $e) {
            $transaction->rollBack();
            $allArgs = func_get_args();
            Yii::error('saveRegisterUserDataTransaction 用户注册回滚' . 'msg:' . $e->getMessage() . '___params:' . json_encode($allArgs));
            return ComBase::getServerBusyReturnArray();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $allArgs = func_get_args();
            Yii::error('saveRegisterUserDataTransaction 用户注册回滚' . 'msg:' . $e->getMessage() . '___params:' . json_encode($allArgs));
            return ComBase::getServerBusyReturnArray();
        }

    }

    /**
     * 手机号验证码登录
     * @param $params
     * @param $visitorId 游客id
     * @return array
     */
    public function loginByMobileCode($params,$visitorId=0)
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

                $this->loginSuccessCall($userId); //登录成功扩展预留

                return ComBase::getReturnArray(['user_type' => intval($userData['type']), 'token' => $tokenArr['jwt_token']]);

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

        if (Yii::$app->params['jwt']['jwt_device_visitor_verification'] === false) {
            //如果关闭了游客模式为了兼容前端的功能还是会返回token值，但仅为当前时间戳
            return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat(UserCommon::TYPE_DEVICE_VISITOR, time(), ['id' => 0]));//统一获取token返回值
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
        return ComBase::getReturnArray(AccountCommon::getReturnTokenDataFormat(UserCommon::TYPE_DEVICE_VISITOR, $tokenArr['jwt_token'], ['id' => $deviceUserId]));//统一获取token返回值
    }

}