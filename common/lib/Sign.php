<?php


namespace common\lib;


/**
 * 创建签名
 */
class Sign
{
    private $salt = ''; //签名加密盐

    public function __construct($salt)
    {
        $this->salt = $salt;
    }

    /**
     * 获取加密md5
     * @param $salt
     * @param $params
     */
    public function getMd5Sign($params){
        ksort($params);
        $string = $this->ToUrlParams($params);
        $string = $string . "&sign_salt=" . $this->salt;
        $string = md5($string);
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 将参数拼接为url: key=value&key=value
     * @param   $params
     * @return  string
     */
    public function ToUrlParams($params)
    {
        $string = '';
        if (!empty($params)) {
            $array = array();
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }

    /**
     * 示例
     * $signObj = new Sign($salt);
     * $md5Sign = $signObj->getMd5Sign($params);
     */

}