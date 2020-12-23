<?php


namespace common\services\user;

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

    const USER_STATUS_DEL = -1;//用户状态删除
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

}