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
        //对应表为m_app
        #$appId = ComBase::getIntVal('_app_id', $params);//采用_开头防止冲突
        $appId = 1;//使用1默认系统app,对于app表第一个ID=1
        //当启用应用维度时可在此根据应用的id进行应用状态判断是否有效等
        return $appId;
    }

    /**
     * 从参数中获取渠道id,自行扩展渠道id有效性验证
     * @param $postParams
     * @return int
     */
    public static function getSourceChannelId($postParams = null){
        //对应表为 m_source_channel
        $sourceChannelId = ComBase::getIntVal('source_channel_id', $postParams);
        //当启用渠道维度时可在此根据应用的id进行应用状态判断是否有效等

        return $sourceChannelId;
    }

}
