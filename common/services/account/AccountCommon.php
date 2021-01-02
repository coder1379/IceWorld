<?php


namespace common\services\account;

use common\services\user\UserCommon;
use Yii;
use common\ComBase;
use Firebase\JWT\JWT;

/**
 * 公共账号处理
 */
class AccountCommon
{

    const LOGIN_TYPE_USERNAME = 1;//用户登录类型为用户名密码
    const LOGIN_TYPE_MOBILE = 2;//用户登录类型为手机号
    const LOGIN_TYPE_EMAIL = 3;//用户登录类型为邮箱
    const LOGIN_TYPE_WECHAT_APP = 11;//用户登录类型为微信app
    const LOGIN_TYPE_WECHAT_WEB = 12;//用户登录类型为微信网页
    const LOGIN_TYPE_QQ_APP = 21;//用户登录类型为qqapp
    const LOGIN_TYPE_QQ_WEB = 22;//用户登录类型为qq网页
    const LOGIN_TYPE_WB_APP = 31;//用户登录类型为微博app
    const LOGIN_TYPE_APPLE_APP = 41;//用户登录类型为苹果app


    const DEVICE_TYPE_APP = 1;//用户设备类型 移动端
    const DEVICE_TYPE_PC = 2;//用户设备类型 PC电脑端
    const DEVICE_TYPE_WEB = 3;//用户设备类型 浏览器
    const DEVICE_ARR = [self::DEVICE_TYPE_APP, self::DEVICE_TYPE_PC, self::DEVICE_TYPE_WEB];//用户设备数组便于判断

    /**
     * 获取账号用户登录设备通过user_id + 短token + app_id
     * @param $userId
     * @param $shortToken
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getAccountDeviceByUserIdToken($userId, $shortToken, $appId = 0)
    {
        return Yii::$app->db->createCommand('select id,user_id,device_code,type,token from {{%user_login_device}} where user_id=:user_id and token=:token and app_id=:app_id limit 1 ', [':user_id' => $userId, ':token' => $shortToken, ':app_id' => $appId])->queryOne();
    }

    /**
     * jwt解码
     * @param $token
     * @return object|null
     */
    public static function decodeUserLoginToken($token)
    {
        $jwtData = null;
        try {
            $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9' . '.' . $token;
            $jwtData = JWT::decode($jwtToken, Yii::$app->params['jwt']['jwt_md5_key'], array('HS256'));
        } catch (\Exception $exception) {
            Yii::error('Jwt json 解码错误,有人使用非正常jwt');
        }
        return $jwtData;
    }

    /**
     * 获取用户登录token
     * @param $userId
     * @param null $userType
     * @param int $appId 应用id 默认0
     * @return array
     * @throws \Exception
     */
    public static function getUserLoginToken($userId, $userType = null, $appId = 0)
    {
        if (empty($userId) || $userType == null) {
            throw new \Exception('user_id或type不能为空');
        }
        $outTime = Yii::$app->params['jwt']['jwt_out_time'] ?? 0;
        if (!empty($outTime)) {
            $outTime = time() + $outTime;
        }
        $userId = intval($userId);
        $userType = intval($userType);
        $mainArr = [
            'u_i' => $userId,//user_id
            'u_t' => $userType,//user_type
            'o_t' => $outTime,//过期时间
            'r_s' => mt_rand(1000, 9999),//写入4位随机码生成不同token
            'a_i' => $appId, //写入应用id到jwt,根据业务判断使用
        ];

        $jwtKey = Yii::$app->params['jwt']['jwt_md5_key'];
        if(empty($jwtKey)){
            throw new \Exception('jwt_key不能为空');
        }

        $jwtStr = JWT::encode($mainArr, $jwtKey);
        $jwtArr = explode('.', $jwtStr);
        if (empty($jwtStr) || count($jwtArr) != 3) {
            throw new \Exception('jwt token生成错误');
        }
        $jwtToken = $jwtArr[1] . '.' . $jwtArr[2];
        return ['jwt_token' => $jwtToken, 'token' => end($jwtArr)];
    }

    /**
     * 获取用户登录的md5密码
     * @param string $password
     * @return string
     * @throws \Exception
     */
    public static function getUserLoginMd5Password($password = '')
    {
        if (empty($password)) {
            throw new \Exception('密码不能为空');
        }

        return md5(md5(md5($password . Yii::$app->params['md5_forever_key'])));
    }

    /**
     * 获取用户第三方登录绑定信息通过type and key  *****注意这里获取的是第三方登录表****
     * @param $type
     * @param $key
     * @param $appId
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getUserLoginBindThirdByTypeKey($type, $key, $appId = 0)
    {
        $sql = 'select id,user_id,type,bind_key,bind_unionid,bind_num,bind_nickname,bind_avatar,bind_sex,bind_district from {{%user_login_bind_third}} where bind_key=:bind_key and type=:type and app_id=:app_id limit 1';
        return Yii::$app->db->createCommand($sql, [':bind_key' => $key, ':type' => $type, ':app_id' => $appId])->queryOne();
    }

    /**
     * 获取用户登录绑定信息通过需要密码登录的类型   ******注意这里获取的是密码登录表*****
     * @param $key
     * @param int $appId 应用id 默认0
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getUserLoginBindWithPwdTypes($key, $appId = 0)
    {
        //注意返回类型都为字符串问题
        $allSearchType = [self::LOGIN_TYPE_USERNAME, self::LOGIN_TYPE_MOBILE, self::LOGIN_TYPE_EMAIL];//用户名,手机号,邮箱归为密码登录3个不能重复，所以查询为3个一起查询
        $sql = 'select id,user_id,type,bind_key from {{%user_login_bind}} where bind_key=:bind_key and type in (' . implode(",", $allSearchType) . ') and app_id=:app_id limit 1';
        return Yii::$app->db->createCommand($sql, [':bind_key' => $key, ':app_id' => $appId])->queryOne();
    }

    /**
     * 获取用户绑定登录信息通过key+type+appid
     * @param $key
     * @param $type
     * @param int $appId 应用id 默认0
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getUserLoginBindWithKeyType($key, $type, $appId = 0)
    {
        //注意返回类型都为字符串问题
        $sql = 'select id,user_id,type,bind_key from {{%user_login_bind}} where bind_key=:bind_key and type=:type and app_id=:app_id limit 1';
        return Yii::$app->db->createCommand($sql, [':bind_key' => $key, ':type' => $type, ':app_id' => $appId])->queryOne();
    }

    /**
     * 检查手机号格式并返回错误信息 false=格式验证通过，非false表示有错直接返回前端
     * @param string $mobile
     * @param int $areaCode 手机区号空,0,86为大陆
     * @return array|bool
     */
    public static function getMobileFormatReturnError($mobile, $areaCode = null)
    {
        //检查手机号格式
        if (empty($areaCode) || $areaCode === 86) {
            if (preg_match('/^1[0-9]{10}$/', $mobile)) {
                return false;
            }
        } else {
            $mobileLength = strlen($mobile);
            if (is_numeric($mobile) && $mobileLength >= 5 && $mobileLength <= 11) {
                //此次统一将国际手机号只按手机号数字与位数判断,可自行扩展验证规则
                return false;
            }
        }

        return ComBase::getParamsFormatErrorReturnArray('手机号格式错误');
    }

    /**
     * 获取保存手机号，支持海外手机号时将数字区号通过-与手机号连接 0,86大陆手机号默认用11位不拼接
     * @param $mobile
     * @param null $areaCode
     * @return string
     */
    public static function getSaveMobile($mobile, $areaCode = null)
    {
        if (empty($areaCode) || $areaCode === 86) {
            return $mobile;
        }
        return $areaCode . '-' . $mobile;
    }

    /**
     * 获取登录成功前的错误校验,例如禁止登录等 false 表示无错误可以成功登录
     * @param $user
     */
    public static function getBeforeLoginErrorCheck($user)
    {
        if (empty($user)) {
            return ComBase::getNoAuthReturnArray(ComBase::MESSAGE_NO_AUTH_ERROR);
        }

        $status = intval($user['status']);
        if ($status === UserCommon::STATUS_YES) {
            //状态正常
            return false;
        } else {
            return ComBase::getNoAuthReturnArray('用户状态异常');
        }
    }

}