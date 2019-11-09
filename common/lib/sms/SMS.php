<?php

namespace common\lib\sms;

use Yii;
use common\lib\sms\aliyun\AliSms;
use common\services\sms\SmsLogic;

/**
 * 短信发送模块
 * Class SMS
 * @package common\lib\sms
 * Author: liuzhe
 */
class SMS
{
    private $mobile;

    private $cache;

    /**
     * SMS constructor.
     * @param $mobile
     * Author: liuzhe
     */
    public function __construct($mobile)
    {
        $this->cache = Yii::$app->cache;
        $this->mobile = $mobile;
    }

    /**
     * 短信发送
     * @param $templateCode
     * @param $templateParam
     * @return bool|mixed|\stdClass
     * Author: liuzhe
     */
    private function sendSms($templateCode, $templateParam)
    {
        $smsConfig = Yii::$app->params['sms'];
        $config = [
            'app_key' => $smsConfig['app_key'],
            'app_secret' => $smsConfig['app_secret'],
            'PhoneNumbers' => $this->mobile,
            'SignName' => $smsConfig['sign_name'],
            'TemplateCode' => $templateCode,
            'TemplateParam' => $templateParam,
        ];

        $client = new AliSms();
        if (!Yii::$app->params['sendsms']) {
            return json_decode('{"Code":"OK"}');
        }
        $result = $client->sendSms($config);
        return $result;
    }

    /**
     * 发送手机验证码
     * @param $code     待发送的验证码
     * @return array
     * Author: liuzhe
     */
    public function sendCode($code)
    {
        $cacheCodeTime = $this->cache->get($this->mobile . '_smsCode_codeTime');
        if ((time() - $cacheCodeTime) < 60) {
            return Json::getFormatData([], 411, '每次发送短信请间隔60秒以上！');
        }
        //加载配置项
        $smsLogic = new SmsLogic();
        $smsConfig = Yii::$app->params['sms'];
        $smsMessage = str_replace('&&sign_name&&', $smsConfig['sign_name'], $smsConfig['message']);
        $smsMessage = str_replace('&&code&&', $code, $smsMessage);
        $sendTime = time();
        //开始发送短信
        $result = $this->sendSms($smsConfig['template'], ['code' => $code]);
        $smsArray = [
            'mobile' => $this->mobile,
            'template' => $smsConfig['template'],
            'send_num' => 1,
            'add_time' => $sendTime,
            'last_send_time' => $sendTime,
            'user_ip' => Yii::$app->request->getUserIP(),
            'message' => $smsMessage
        ];
        if (!empty($result) && $result->Code == 'OK') {
            //短信发送成功 记录进数据库
            $smsArray['status'] = 1;
            $smsLogic->createSms($smsArray);
            $this->cache->set($this->mobile . '_smsCode_code', $code);
            $this->cache->set($this->mobile . '_smsCode_codeTime', $sendTime);
            return Json::getFormatData(['code' => $code]);
        } else {
            //短信发送失败 记录进数据库
            $errorMessage = $result->Message ?? '';
            $smsArray['status'] = 2;
            $smsArray['returnmessage'] = $errorMessage;
            $smsLogic->createSms($smsArray);
            return Json::getFormatData([], 412, $errorMessage);
        }
    }

    /**
     * 校验手机验证码
     * @param $code
     * @return array
     * Author: liuzhe
     */
    public function checkCode($code)
    {
        $cacheCode = $this->cache->get($this->mobile . '_smsCode_code');
        $cacheCodeTime = $this->cache->get($this->mobile . '_smsCode_codeTime');
        $cacheCodeErrorNum = $this->cache->get($this->mobile . '_smsCode_codeErrorNum');
        //验证码10*60秒（十分钟）内有效
        if ((time() - $cacheCodeTime) >= 600) {
            $this->cacheFlush();
            return Json::getFormatData([], 411, '验证码已过期，请重新发送！');
        }
        //验证码连续五次校验错误则失效
        if ($cacheCodeErrorNum >= 5) {
            $this->cacheFlush();
            return Json::getFormatData([], 412, '验证码错误次数太多，请重新发送！');
        }
        //缓存里面存储的验证码不为空 且和传进来的用户验证码不同，判定为错误
        if (!empty((int)$cacheCode) && ($code != $cacheCode)) {
            $this->cache->set($this->mobile . '_smsCode_codeErrorNum', $cacheCodeErrorNum + 1);
            return Json::getFormatData([], 413, '验证码错误');
        }
        //验证通过
        $this->cacheFlush();
        return Json::getFormatData([]);
    }

    /**
     * 清理短信缓存
     * Author: liuzhe
     */
    private function cacheFlush()
    {
        $this->cache->delete($this->mobile . '_smsCode_code');
        $this->cache->delete($this->mobile . '_smsCode_codeTime');
        $this->cache->delete($this->mobile . '_smsCode_codeErrorNum');
    }
}