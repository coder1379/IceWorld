<?php
namespace common\extend;

use common\base\BaseCache;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;
use yii\web\Response;
use Yii;


/**
 * Class ImageCaptcha 图形验证码扩展
 * @package common\extend
 *
 * @property-read string $sessionKey
 */
class ImageCaptchaAction extends CaptchaAction
{
    public $keywords = 'k'; // 前端传递的参数关键字名称，验证码需要与次关键字匹配
    public $saveCacheKeywords = 'image_captcha_'; // 保存在缓存中的名称全名为：$saveCacheKeywords.$keywords,外部可覆盖
    public $timeout = 3600; // 超时时间，可自定义覆盖 默认1小时

    /**
     * Runs the action.
     */
    public function run()
    {
        $this->setHttpHeaders();
        Yii::$app->response->format = Response::FORMAT_RAW;

        return $this->renderImage($this->getVerifyCode());
    }

    /**
     * 直接生成code
     * @param bool $regenerate
     * @return string
     */
    public function getVerifyCode($regenerate = false)
    {
        // 使用自定义的缓存模块
        $code = $this->generateVerifyCode();
        $keyVal = Yii::$app->request->post($this->keywords);

        if(empty($keyVal)){
            $keyVal = Yii::$app->request->get($this->keywords);
        }

        if(!empty($keyVal) && strlen($keyVal)<50){
            // 控制参数长度，超过则不记录
            BaseCache::setExVal($this->saveCacheKeywords.$keyVal,$code,$this->timeout);
        }
        return $code;
    }

    /**
     * @param string $input
     * @param bool $caseSensitive
     * @return bool
     */
    public function validate($input, $caseSensitive)
    {
        return false;
    }

    /**
     * @return string
     */
    protected function getSessionKey()
    {
        return '__captcha/' . $this->getUniqueId();
    }

}