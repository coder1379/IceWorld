<?php


namespace common\services\account;


use Firebase\JWT\JWT;

/**
 * 公共账号处理
 */
class AccountCommon
{

    /**
     * 获取账号用户登录设备通过user_id + 短token + app_id
     * @param $userId
     * @param $shortToken
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getAccountDeviceByUserIdToken($userId,$shortToken,$appId = 0){
        return Yii::$app->db->createCommand('select id,user_id,device_code,type,token from {{%user_login_device}} where user_id=:user_id and token=:token and app_id=:app_id limit 1 ', [':user_id' => $userId, ':token' => $shortToken,':app_id' => $appId])->queryOne();
    }

    /**
     * jwt解码
     * @param $token
     * @return object|null
     */
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
     * 获取用户登录token
     * @param $userId
     * @param null $userType
     * @param int $appId 应用id 默认0
     * @return array
     * @throws \Exception
     */
    public static function getUserLoginToken($userId,$userType=null,$appId = 0){

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
            'r_s' => mt_rand(1000,9999),//写入4位随机码生成不同token
            'a_i' => $appId, //写入应用id到jwt,根据业务判断使用
        ];

        $jwtStr = JWT::encode($mainArr, Yii::$app->params['jwt_md5_key']);
        $jwtArr = explode('.', $jwtStr);
        if(empty($jwtStr) || count($jwtArr)!=3){
            throw new \Exception('jwt token生成错误');
        }
        $jwtToken = $jwtArr[1] . '.' . $jwtArr[2];
        return ['jwt_token'=>$jwtToken,'token'=>end($jwtArr)];
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
     * @param int $appId 应用id 默认0
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function getUserLoginBindWithPwdTypes($key,$appId=0)
    {
        //注意返回类型都为字符串问题
        $allSearchType = [self::USER_LOGIN_TYPE_USERNAME, self::USER_LOGIN_TYPE_MOBILE, self::USER_LOGIN_TYPE_EMAIL];//用户名,手机号,邮箱归为密码登录3个不能重复，所以查询为3个一起查询
        $sql = 'select id,user_id,type,bind_key from {{%user_login_bind}} where type in ('.implode(",",$allSearchType).') and bind_key=:bind_key and app_id=:app_id limit 1';
        return Yii::$app->db->createCommand($sql, [':bind_key' => $key,':app_id'=>$appId])->queryOne();
    }

}