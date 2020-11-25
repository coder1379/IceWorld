<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：基础公共类
 */

namespace common\base;

use Yii;
class BaseCommon
{

    /**
     * 封装YII2 设置cookie
     * @param $cookies
     * @param string $name
     * @param string $value
     * @param int $time
     */
    public function setCookie($cookies,$name='',$value='',$time=0){
        if(empty($name)!=true){
            $cookies->add(new \yii\web\Cookie([
                'name' => $name,
                'value' => $value,
                'expire'=>$time,
            ]));
        }
    }

    /**
     * 获取用户访问IP
     * @return mixed|null|string
     */
    public function getIp(){
        return Yii::$app->request->userIP;
    }

    /**
     * 获取访问页面的URL路径及参数，主要用于未登录后的跳转
     * @return string
     */
    public function getAccessUrl()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }


}