<?php
namespace api\controllers;

use common\ComBase;
use common\controllers\ApiCommonContoller;
use Yii;
use yii\helpers\Json;


/**
 * index 首页
 */
class IndexController extends ApiCommonContoller
{
	public $enableCsrfValidation = false;
    public $excludeAccessLog = ['index','404'];
    public $excludeVisitorVer = ['index','404'];

    /**
     * index 默认首页
     * @return string
     */
    public function actionIndex(){
        return Json::encode(ComBase::getReturnArray());
    }

    /**
     * 404未找到返回
     * @return string
     */
    public function action404(){
        return Json::encode(ComBase::getReturnArray([],ComBase::CODE_SERVER_NO_FIND_ERROR,ComBase::MESSAGE_SERVER_NO_FIND_ERROR));
    }

}
