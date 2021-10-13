<?php
namespace api\controllers;

use common\base\BaseCache;
use common\ComBase;
use common\controllers\ApiCommonContoller;
use Yii;
use yii\helpers\Json;

/**
 * captchas 验证码类
 */
class CaptchasController extends ApiCommonContoller
{
	public $enableCsrfValidation = false;
    public $excludeAccessLog = ['imagecodecaptcha'];
    public $excludeVisitorVer = ['imagecodecaptcha','getimagecodecaptcha'];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $imageCodeTimeOut = 300;
        $callLimitConfig = Yii::$app->params['call_limit'];
        $imageCodeKeywordsName = null;
        if(!empty($callLimitConfig) && !empty($callLimitConfig['sms']) && !empty($callLimitConfig['sms']['img_code_timeout'])){
            $imageCodeTimeOut = $callLimitConfig['sms']['img_code_timeout'];
            $imageCodeKeywordsName = $callLimitConfig['sms']['keywords_pre_name'];
        }
        if(empty($imageCodeKeywordsName)){
            throw new \Exception('callLimit 调用限制缺少keywords_pre_name,statistics_pre_name默认值');
        }
        return [
            'imagecodecaptcha' =>  [ // 图像验证码
                'class' => 'common\extend\ImageCaptchaAction',
                'timeout' => $imageCodeTimeOut,
                'saveCacheKeywords' => $imageCodeKeywordsName,
                'height' => 40,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 6
            ],
        ];
    }

    //非正式环境获取验证码测试使用
    public function actionGetimagecodecaptcha(){
        if(YII_ENV!='prod'){
            echo BaseCache::getVal(Yii::$app->params['call_limit']['sms']['keywords_pre_name'].Yii::$app->request->get('k'));
            exit();
        }
    }

}
