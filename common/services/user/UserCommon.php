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
    const TYPE_REGISTER = 1;//用户类型注册用户

    const STATUS_DEL = -1;//用户状态删除
    const STATUS_YES = 1;//用户状态正常

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