<?php

namespace common\services\application;

use Yii;
use common\ComBase;

/**
 * 应用公共类 多应用情况下进行扩展
 * @package common\services\application
 */
class AppCommon
{

    /**
     * 从参数中获取appid,自行扩展appid有效性验证
     * @param $postParams
     * @return int
     */
    public static function getAppId($postParams = null){
        #$appId = ComBase::getIntVal('_app_id', $params);//采用_开头防止冲突
        $appId = 1;//使用1默认系统app,对于app表第一个ID=1
        return $appId;
    }


}
