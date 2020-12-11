<?php


namespace common\services\log;

use Yii;
use common\ComBase;

/**
 * 访问日志基础类
 * @package common\services\log
 */
class AccessBaseLogic
{
    public function save($request,$file)
    {
        try {
            //  $this->route; 获取路径如：feedback/message/create

            $startTime = intval(YII_BEGIN_TIME * 10000);
            $endTime = intval(microtime(true) * 10000);
            $useTime = ceil(($endTime - $startTime) / 10);
            echo $useTime;

        } catch (\Exception $exc) {
            Yii::error('访问日志写入错误:' . $exc->getMessage());
        }

    }

}