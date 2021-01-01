<?php

namespace common\services\sms;

use Yii;
use common\ComBase;

/**
 * Sms 公共类 配置好相关参数，发送时根据参数选择:queues/SendMobileSmsJobs(手机验证码),queues/SendEmailSmsJobs(邮箱验证码)
 * 内容列表中 使用 ${}表示展位费及参数名
 * @package common\services\sms
 */
class SmsCommon
{
    const CODE_SCENE_REGISTER = 1;//验证码场景 注册
    const CODE_SCENE_LOGIN = 2;//验证码场景 登录
    const CODE_SCENE_BIND = 3;//验证码场景 绑定
    const CODE_SCENE_FORGET_PASSWORD = 4;//验证码场景 忘记密码

    //信息验证码场景合集便于判断与维护
    const CODE_SCENE_LIST = [
        self::CODE_SCENE_REGISTER,
        self::CODE_SCENE_LOGIN,
        self::CODE_SCENE_BIND,
        self::CODE_SCENE_FORGET_PASSWORD
    ];

    //短信场景对应字符串描述列表
    const SCENE_STR_LIST = [
        self::CODE_SCENE_REGISTER => '注册',
        self::CODE_SCENE_LOGIN => '登录',
        self::CODE_SCENE_BIND => '绑定手机号',
        self::CODE_SCENE_FORGET_PASSWORD => '忘记密码'
    ];

    //发送场景文本预定义，需要创建模板的地方也从此处复制 例如阿里云模板
    const MOBILE_CAPTCHA_SENT_TEMPLATE = 'SMS_123456';//验证码模板号 例如阿里云等
    const MOBILE_CAPTCHA_SENT_STR = '您的验证码是${code}，该验证码15分钟内有效，切勿泄露他人。';//统一验证码，注册登录需要区分可自行添加并使用

    //短信发送场景模板 例如阿里云需要指定发送模板


    const TYPE_CAPTCHA = 1;//类型验证码
    const TYPE_NOTICE = 2;//类型通知
    const TYPE_MESSAGE = 3;//类型消息

    const SEND_TYPE_NO = 0;//发送类型 未指定
    const SEND_TYPE_USER = 1;//发送类型 用户发起
    const SEND_TYPE_ADMIN = 2;//发送类型 管理员发起
    const SEND_TYPE_TASK = 3;//发送类型 任务发起

    const MOBILE_TYPE_AUTO = 0;//短信类型 发送时根据系统选择并回写表
    const MOBILE_TYPE_ALI = 1;//短信类型 阿里
    const MOBILE_TYPE_TENCENT = 2;//短信类型 腾讯
    const MOBILE_TYPE_HUYI = 6;//短信类型 互，亿
    const MOBILE_TYPE_OTHER = 9;//短信类型 其他

    const STATUS_NO_SEND = 0;//发送状态 不发生
    const STATUS_SUCCESS_SEND = 1;//发送状态 发送成功
    const STATUS_WAIT_SEND = 2;//发送状态 待发送
    const STATUS_FAIL_SEND = 3;//发送状态 发送失败


    /**
     * 统一处理区号，控制是否开启或者验证区号有效性
     * @param $areaCode
     * @return int
     */
    public static function getMobileAreaCode($areaCode){
        //根据需求开启
        /*if(!empty($areaCode)){
            $areaCode = str_replace('+', '', $areaCode);
            $areaCode = intval($areaCode);
            return $areaCode;
        }*/

        return 0;
    }


}
