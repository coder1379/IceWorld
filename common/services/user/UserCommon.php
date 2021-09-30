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
    const TYPE_DEVICE_VISITOR = -1;//设备游客用户类型为-1

    const STATUS_DEL = -1;//用户状态删除
    const STATUS_YES = 1;//用户状态正常 只有状态=1的才能正常使用 对应user status字段固定-1,1,2尽量避免修改防止意外判断问题
    const STATUS_FREEZE = 2;//用户状态冻结 无法正常使用，提示前端用户

    /**
     * 通过用户id获取用户基础信息
     * @param $userId
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getUserByid($userId){
        $userId = intval($userId);
        $sql = 'select * from {{%user}} where id=:id';
        return Yii::$app->db->createCommand($sql, [':id' => $userId])->queryOne();
    }

}