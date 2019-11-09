<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;


/**
 * Class BaseContoller 控制器基类 常用的控制器操作方法可以加入到此文件中
 * @package common\controllers
 */
class BaseContoller extends Controller
{

    /**
     * 获取POST参数
     * @param null $name
     * @param null $defaultValue
     * @return array|mixed
     */
    public function post($name=null,$defaultValue = null){
       return Yii::$app->request->post($name,$defaultValue);
    }

    /**
     * 获取GET参数
     * @param null $name
     * @param null $defaultValue
     * @return array|mixed
     */
    public function get($name=null,$defaultValue = null){
        return Yii::$app->request->get($name,$defaultValue);
    }

    /**
     * 返回JSON数据格式
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     * @return array 格式数组
     */
    public function getJsonArray($data=[],$code=200, $msg='success'){
        if(empty($data)==true){
            $data=new \StdClass();//将空数组赋值空对象便于前端兼容处理.
        }
        return ['code'=>$code,'msg'=>$msg,'data'=>$data];
    }

    public function getJsonString($data=[],$code=200, $msg='success'){
        return json_encode($this->getJsonArray($data,$code,$msg));
    }

    /**
     * 直接输出json数据到前端
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     */
    public function echoJson($data=[],$code=200,$msg='success'){
        echo json_encode($this->getJsonArray($data,$code,$msg));
        exit();
    }

    /**
     * 格式化返回数据 为json格式 并直接输出运行结果
     * @param array $arrData 格式数组
     */
    public function echoJsonWithArray($arrData=[]){
        echo json_encode($arrData);
        exit();
    }


}
