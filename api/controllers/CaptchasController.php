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
    public $excludeAccessLog = ['captcha'];
    public $excludeVisitorVer = ['captcha','getcaptcha'];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' =>  [ // 图像验证码
                'class' => 'common\extend\ImageCaptchaAction',
                'timeout' => 300,
                'saveCacheKeywords' => 'calllimit_img_captcha_',
                'height' => 40,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 6
            ],
        ];
    }

    //非正式环境获取验证码测试使用
    public function actionGetcaptcha(){
        if(YII_ENV!='prod'){
            echo BaseCache::getVal('calllimit_img_captcha_'.Yii::$app->request->get('k'));
            exit();
        }
    }

}
