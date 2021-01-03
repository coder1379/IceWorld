<?php

namespace common\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


/**
 * Class BaseContoller 控制器基类 常用的控制器操作方法可以加入到此文件中
 * @package common\controllers
 */
class BaseContoller extends Controller
{
    /**
     * 获取POST参数
     * @param null $name 值为null 将获取所有post参数
     * @param null $defaultValue
     * @return array|mixed
     */
    public function post($name = null, $defaultValue = null)
    {
        return Yii::$app->request->post($name, $defaultValue);
    }

    /**
     * 获取GET参数
     * @param null $name
     * @param null $defaultValue
     * @return array|mixed
     */
    public function get($name = null, $defaultValue = null)
    {
        return Yii::$app->request->get($name, $defaultValue);
    }

    /**
     * 获取post+get的参数合并数组
     * @throws \yii\base\InvalidConfigException
     */
    public function getRequestAll()
    {
        $request = Yii::$app->request;

        return ArrayHelper::merge($request->getQueryParams(), $request->getBodyParams());
    }

}
