<?php


namespace common\base;

use common\lib\StringHandle;
use Firebase\JWT\JWT;
use Yii;

/**
 * 用户公共类
 * @package common\base
 */
class UserCommon
{
    const USER_TYPE_REGISTER = 1;//用户类型注册用户

    const USER_STATUS_YES = 1;//用户状态正常

    const USER_LOGIN_TYPE_USERNAME = 1;//用户登录类型为用户名密码
    const USER_LOGIN_TYPE_MOBILE = 2;//用户登录类型为手机号
    const USER_LOGIN_TYPE_EMAIL = 3;//用户登录类型为邮箱
    const USER_LOGIN_TYPE_WECHAT_APP = 11;//用户登录类型为微信app
    const USER_LOGIN_TYPE_WECHAT_WEB = 12;//用户登录类型为微信网页
    const USER_LOGIN_TYPE_QQ_APP = 21;//用户登录类型为qqapp
    const USER_LOGIN_TYPE_QQ_WEB = 22;//用户登录类型为qq网页
    const USER_LOGIN_TYPE_WB_APP = 31;//用户登录类型为微博app
    const USER_LOGIN_TYPE_APPLE_APP = 41;//用户登录类型为苹果app


    const USER_DEVICE_TYPE_APP = 1;//用户设备类型 移动端
    const USER_DEVICE_TYPE_PC = 2;//用户设备类型 PC电脑端
    const USER_DEVICE_TYPE_WEB = 3;//用户设备类型 浏览器
    const USER_DEVICE_ARR = [self::USER_DEVICE_TYPE_APP,self::USER_DEVICE_TYPE_PC,self::USER_DEVICE_TYPE_WEB];//用户设备数组便于判断

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
     * 获取用户登录token
     * @param $userId
     * @param null $userType
     * @return array
     * @throws \Exception
     */
    public static function getUserLoginToken($userId,$userType=null){

        if(empty($userId) || $userType==null){
            throw new \Exception('user_id或type不能为空');
        }
        $outTime = Yii::$app->params['user_token_out_time']??0;
        if(!empty($outTime)){
            $outTime = time() + $outTime;
        }
        $userId = intval($userId);
        $userType = intval($userType);
        $mainArr = [
            'u_i'=>$userId,//user_id
            'u_t' => $userType,//user_type
            'o_t'=>$outTime,//过期时间
            'r_s' => StringHandle::getRandomString(4),//写入4位随机码生成不同token
        ];

        $jwtStr = JWT::encode($mainArr, Yii::$app->params['jwt_md5_key']);
        $jwtArr = explode('.', $jwtStr);
        if(empty($jwtStr) || count($jwtArr)!=3){
            throw new \Exception('jwt token生成错误');
        }
        $jwtToken = $jwtArr[1] . '.' . $jwtArr[2];
        return ['jwt_token'=>$jwtToken,'token'=>end($jwtArr)];
    }

    public static function decodeUserLoginToken($token){
        $jwtData = null;
        try{
            $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9' .'.'. $token;
            $jwtData =  JWT::decode($jwtToken, Yii::$app->params['jwt_md5_key'], array('HS256'));
        }catch (\Exception $exception){
            Yii::error('Jwt json 解码错误,有人使用非正常jwt');
        }
        return $jwtData;
    }

    /**
     * 获取用户第三方登录绑定信息通过type and key  *****注意这里获取的是第三方登录表****
     * @param $type
     * @param $key
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getUserLoginBindThirdByTypeKey($type, $key)
    {
        $sql = 'select id,user_id,type,bind_key,bind_unionid,bind_num,bind_nickname,bind_avatar,bind_sex,bind_district from {{%user_login_bind_third}} where type=:type and bind_key=:bind_key limit 1';
        return Yii::$app->db->createCommand($sql, [':type' => $type, ':bind_key' => $key])->queryOne();
    }

    /**
     * 获取用户登录绑定信息通过需要密码登录的类型   ******注意这里获取的是密码登录表*****
     * @param $key
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getUserLoginBindWithPwdTypes($key)
    {
        //注意返回类型都为字符串问题
        $allSearchType = [self::USER_LOGIN_TYPE_USERNAME, self::USER_LOGIN_TYPE_MOBILE, self::USER_LOGIN_TYPE_EMAIL];//用户名,手机号,邮箱归为密码登录3个不能重复，所以查询为3个一起查询
        $sql = 'select id,user_id,type,bind_key from {{%user_login_bind}} where type in ('.implode(",",$allSearchType).') and bind_key=:bind_key limit 1';
        return Yii::$app->db->createCommand($sql, [':bind_key' => $key])->queryOne();
    }

    /**
     * 通过用户id获取用户基础信息
     * @param $userId
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getUserByid($userId){
        $userId = intval($userId);
        $sql = 'select * from {{%user}} where id=:id limit 1';
        return Yii::$app->db->createCommand($sql, [':id' => $userId])->queryOne();
    }

    /**
     * 获取用户登录设备通过user_id + 短token
     * @param $userId
     * @param $shortToken
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getUserDeviceByUserIdToken($userId,$shortToken){
       return Yii::$app->db->createCommand('select id,user_id,device_code,type,token from {{%user_login_device}} where user_id=:user_id and token=:token limit 1 ', [':user_id' => $userId, ':token' => $shortToken])->queryOne();
    }

}