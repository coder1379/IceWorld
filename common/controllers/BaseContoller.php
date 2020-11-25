<?php

namespace common\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use common\ComBase;


/**
 * Class BaseContoller 控制器基类 常用的控制器操作方法可以加入到此文件中
 * @package common\controllers
 */
class BaseContoller extends Controller
{
    public $params;

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

    public function setParams($data)
    {
        $this->params = $data;
    }

    /**
     * 获取int返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public function getParamsIntVal($name, $params = null, $defaultValue = 0)
    {
        if ($params === null) {
            return ComBase::getIntVal($name, $this->params, $defaultValue);
        } else {
            return ComBase::getIntVal($name, $params, $defaultValue);
        }

    }

    /**
     * 获取string返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param string $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public function getParamsStrVal($name, $params = null, $defaultValue = '')
    {
        if ($params === null) {
            return ComBase::getStrVal($name, $this->params, $defaultValue);
        } else {
            return ComBase::getStrVal($name, $params, $defaultValue);
        }
    }

    /**
     * 获取float返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public function getParamsFloatVal($name, $params = null, $defaultValue = 0)
    {
        if ($params === null) {
            return ComBase::getFloatVal($name, $this->params, $defaultValue);
        } else {
            return ComBase::getFloatVal($name, $params, $defaultValue);
        }
    }

    /**
     * 获取json返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param array $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public function getParamsJsonDecodeArrayVal($name, $params = null, $defaultValue = [])
    {
        if ($params === null) {
            return ComBase::getJsonDecodeArrayVal($name, $this->params, $defaultValue);
        } else {
            return ComBase::getJsonDecodeArrayVal($name, $params, $defaultValue);
        }
    }

    /**
     * 返回JSON数据格式
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     * @return array 格式数组
     */
    public function getJsonArray($data = [], $code = 200, $msg = 'success')
    {
        if (empty($data) == true) {
            $data = new \StdClass();//将空数组赋值空对象便于前端兼容处理.
        }
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    public function getJsonString($data = [], $code = 200, $msg = 'success')
    {
        return Json::encode($this->getJsonArray($data, $code, $msg));
    }

    /**
     * 直接输出json数据到前端
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     */
    public function echoJson($data = [], $code = 200, $msg = 'success')
    {
        echo Json::encode($this->getJsonArray($data, $code, $msg));
        exit();
    }


}
