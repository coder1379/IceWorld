<?php

namespace common\services\captcha;

use common\base\BaseCache;
use Yii;

/**
 * 短信验证码逻辑 注意使用当前类的 setCaptcha,getCaptcha 进行基本操作便于维护验证码的前缀
 * 其余方法自动加入了业务前缀调用只需要传入具体值即可
 * @package common\services\captcha
 */
class CaptchaLogic
{
    private $codeCachePre = 'code_cache_';//验证码前缀
    private $limitPre = 'limit_send_code_';//限速发送验证码前缀
    private $maxLimitTime = 3600; //最大限速时间防止逻辑错误
    public $sendCodeSplitTime = 60;//发送验证码间隔时间60秒
    public $captchaCacheTime = 900;//验证码有效期15分钟

    public function checkSmsCodeStatus($mobile, $scene)
    {
        $status = false;


        return $status;
    }

    /**
     * 设置限制时间例如60秒只能发送一次
     * @param $key
     * @param $value
     * @param $expireTime
     * @return bool
     */
    public function setKeyLimitTime($key, $value, $expireTime)
    {
        $key = $this->limitPre . $key;
        return $this->setCaptcha($key, $value, $expireTime);
    }

    /**
     * 获取key限速剩余时间
     * @param $key
     * @return int
     */
    public function getKeyLimitTime($key)
    {
        $key = $this->limitPre . $key;
        $retTime = 0;
        $sendTime = $this->getCaptcha($key);
        if (!empty($sendTime)) {
            $sendTime = intval($sendTime);
            if ($sendTime > 0) {
                $retTime = time() - $sendTime;
                if ($retTime < $this->sendCodeSplitTime) {
                    $retTime = $this->sendCodeSplitTime - $retTime;
                } else if ($retTime > $this->maxLimitTime) { //超过最大限制时间直接返回0按照没有算并删除key 防止锁死
                    $retTime = 0;
                    $this->deleteCaptcha($key);
                }
            }
        }
        return $retTime;
    }

    /**
     * 删除时间限制key 直接调用deleteCaptcha注意key前缀问题
     * @param $key
     * @return mixed
     */
    public function deleteKeyLimitTime($key){
        $key = $this->limitPre . $key;
        return $this->deleteCaptcha($key);
    }

    /**
     * 获取发送缓存验证码
     * @param $searchKey
     * @return mixed
     */
    public function getSendCodeCache($searchKey)
    {
        $key = $this->codeCachePre . $searchKey;
        return $this->getCaptcha($key);
    }

    /**
     * 设置发送缓存验证码
     * @param $setKey
     * @param $value
     * @param $expireTime
     * @return bool
     */
    public function setSendCodeCache($setKey, $value, $expireTime = null)
    {
        if (empty($expireTime)) {
            $expireTime = $this->captchaCacheTime;
        }
        $key = $this->codeCachePre . $setKey;
        return $this->setCaptcha($key, $value, $expireTime);
    }

    /**
     * 删除缓存的验证码
     * @param $searchKey
     * @return mixed
     */
    public function deleteSendCodeCache($searchKey){
        $key = $this->codeCachePre . $searchKey;
        return $this->deleteCaptcha($key);
    }

    /**
     * 设置缓存值
     * @param $key
     * @param $value
     * @param int $expireTime 单位秒
     * @param object $db 存储区 null为默认
     * @return bool
     */
    private function setCaptcha($key, $value, $expireTime, $db = null)
    {
        $key = 'captcha_' . $key;
        return BaseCache::setExVal($key, $value, $expireTime, $db);
    }

    /**
     * 获取缓存值
     * @param $key
     * @param object $db 存储区 null为默认
     * @return mixed
     */
    private function getCaptcha($key, $db = null)
    {
        $key = 'captcha_' . $key;
        return BaseCache::getVal($key, $db);
    }

    /**
     * 删除缓存值
     * @param $key
     * @param object $db 存储区 null为默认
     * @return mixed
     */
    private function deleteCaptcha($key, $db = null)
    {
        $key = 'captcha_' . $key;
        return BaseCache::deleteVal($key, $db);
    }


}