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

    private $requestParams = null; //get+post参数的合并

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
     * 检查名称是否正常
     * @param $name
     * @throws \Exception
     */
    private function checkRequestName($name)
    {
        if (empty($name)) {
            throw new \Exception('postInt name is null');
        }
    }

    /**
     * 获取整形参数
     * @param null $name post参数名
     * @param int $defaultValue 默认值为0
     * @throws \Exception
     */
    public function postToInt($name = null, $defaultValue = 0)
    {
        $this->checkRequestName($name);
        return intval(Yii::$app->request->post($name, $defaultValue));
    }

    /**
     * 获取字符串post参数
     * @param null $name 参数名
     * @param string $defaultValue 默认为''
     * @return string
     * @throws \Exception
     */
    public function postToStr($name = null, $defaultValue = '')
    {
        $this->checkRequestName($name);
        return strval(Yii::$app->request->post($name, $defaultValue));
    }

    /**
     * 获取浮点post参数
     * @param null $name
     * @param int $defaultValue
     * @return float
     * @throws \Exception
     */
    public function postToFloat($name = null, $defaultValue = 0)
    {
        $this->checkRequestName($name);
        return floatval(Yii::$app->request->post($name, $defaultValue));
    }

    /**
     * 获取数组post 参数
     * @param null $name 参数名称
     * @param array $defaultValue
     * @return array|mixed|null
     * @throws \Exception
     */
    public function postToJson($name = null, $defaultValue = [])
    {
        $this->checkRequestName($name);
        $tempArr = Yii::$app->request->post($name);
        $arr = null;
        if (is_array($tempArr)) {
            $arr = $tempArr;
        } else {
            $arr = json_decode(strval($tempArr), true);
            if(!is_array($arr)){
                $arr = $defaultValue;
            }
        }
        return $arr;
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
     * 获取整形参数
     * @param null $name get参数名
     * @param int $defaultValue 默认值为0
     * @throws \Exception
     */
    public function getToInt($name = null, $defaultValue = 0)
    {
        $this->checkRequestName($name);
        return intval(Yii::$app->request->get($name, $defaultValue));
    }

    /**
     * 获取字符串get参数
     * @param null $name 参数名
     * @param string $defaultValue 默认为''
     * @return string
     * @throws \Exception
     */
    public function getToStr($name = null, $defaultValue = '')
    {
        $this->checkRequestName($name);
        return strval(Yii::$app->request->get($name, $defaultValue));
    }

    /**
     * 获取浮点get参数
     * @param null $name
     * @param int $defaultValue
     * @return float
     * @throws \Exception
     */
    public function getToFloat($name = null, $defaultValue = 0)
    {
        $this->checkRequestName($name);
        return floatval(Yii::$app->request->get($name, $defaultValue));
    }

    /**
     * 获取数组get 参数
     * @param null $name 参数名称
     * @param array $defaultValue
     * @return array|mixed|null
     * @throws \Exception
     */
    public function getToJson($name = null, $defaultValue = [])
    {
        $this->checkRequestName($name);
        $tempArr = Yii::$app->request->get($name);
        $arr = null;
        if (is_array($tempArr)) {
            $arr = $tempArr;
        } else {
            $arr = json_decode(strval($tempArr), true);
            if(!is_array($arr)){
                $arr = $defaultValue;
            }
        }
        return $arr;
    }

    /**
     * 获取post+get的参数合并数组
     * @throws \yii\base\InvalidConfigException
     */
    public function getPostGetArray()
    {
        $request = Yii::$app->request;
        return ArrayHelper::merge($request->getQueryParams(), $request->getBodyParams());
    }

    /**
     * 设置post+get合并参数到当前对象降低多次获取的性能消耗
     * @throws \yii\base\InvalidConfigException
     */
    private function setRequestParams()
    {
        if ($this->requestParams === null) {
            $this->requestParams = $this->getPostGetArray();
        }
    }

    /**
     * 获取整形参数
     * @param null $name get参数名
     * @param int $defaultValue 默认值为0
     * @throws \Exception
     */
    public function requestToInt($name = null, $defaultValue = 0)
    {
        $this->checkRequestName($name);
        $this->setRequestParams();
        $temp = $this->requestParams[$name]??null;
        if(isset($temp)){
            $temp = intval($temp);
        }else{
            $temp = $defaultValue;
        }
        return $temp;
    }

    /**
     * 获取字符串get参数
     * @param null $name 参数名
     * @param string $defaultValue 默认为''
     * @return string
     * @throws \Exception
     */
    public function requestToStr($name = null, $defaultValue = '')
    {
        $this->checkRequestName($name);
        $this->setRequestParams();
        $temp = $this->requestParams[$name]??null;
        if(isset($temp)){
            $temp = strval($temp);
        }else{
            $temp = $defaultValue;
        }
        return $temp;
    }

    /**
     * 获取浮点get参数
     * @param null $name
     * @param int $defaultValue
     * @return float
     * @throws \Exception
     */
    public function requestToFloat($name = null, $defaultValue = 0)
    {
        $this->checkRequestName($name);
        $this->setRequestParams();
        $temp = $this->requestParams[$name]??null;
        if(isset($temp)){
            $temp = floatval($temp);
        }else{
            $temp = $defaultValue;
        }
        return $temp;
    }

    /**
     * 获取数组get 参数
     * @param null $name 参数名称
     * @param array $defaultValue
     * @return array|mixed|null
     * @throws \Exception
     */
    public function requestToJson($name = null, $defaultValue = [])
    {
        $this->checkRequestName($name);
        $this->setRequestParams();
        $tempArr = $this->requestParams[$name]??null;
        $arr = null;
        if (is_array($tempArr)) {
            $arr = $tempArr;
        } else {
            $arr = json_decode(strval($tempArr), true);
            if(!is_array($arr)){
                $arr = $defaultValue;
            }
        }
        return $arr;
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
        return json_encode($this->getJsonArray($data, $code, $msg));
    }

    /**
     * 直接输出json数据到前端
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     */
    public function echoJson($data = [], $code = 200, $msg = 'success')
    {
        echo json_encode($this->getJsonArray($data, $code, $msg));
        exit();
    }

    /**
     * 格式化返回数据 为json格式 并直接输出运行结果
     * @param array $arrData 格式数组
     */
    public function echoJsonWithArray($arrData = [])
    {
        echo json_encode($arrData);
        exit();
    }


}
