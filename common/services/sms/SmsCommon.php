<?php

namespace common\services\sms;

use Yii;
use common\ComBase;

/**
 * Sms 公共类
 * @package common\services\sms
 */
class SmsCommon
{
    const SMS_CODE_SCENE_REGISTER = 1;//验证码信息注册场景表示
    const SMS_CODE_SCENE_LOGIN = 2;//验证码信息登录场景表示
    const SMS_CODE_SCENE_FORGET_PASSWORD = 3;//验证码信息忘记密码场景表示

    //信息验证码场景合集便于判断与维护
    const SMS_CODE_SCENE_LIST = [self::SMS_CODE_SCENE_REGISTER,self::SMS_CODE_SCENE_LOGIN, self::SMS_CODE_SCENE_FORGET_PASSWORD];

}
