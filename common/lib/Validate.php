<?php
/**
 * create by: majie
 * datetime: 2017-10-19 14:55
 * desc：验证封装
 */

namespace common\lib;


class Validate
{

    /**
     * 判断是否为金额
     * @param $money
     * @return false|int
     */
    public function isMoney($money){
        return preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $money);
    }

    /** 判断是否是有效的手机号
     * @param $mobile 手机号
     * @return int
     */
    public function isPhone($mobile) {
        return preg_match("/^1[1|2|3|4|5|6|7|8|9|0]{1}[0-9]{9}$/", $mobile);
    }

    /**
     * 判定是否为身份证
     * @param $idcard 身份证号
     * @return bool true 是
     */
    function isIdcard($idcard)
    {
        if(preg_match("/^(?:\d{15}|\d{18})$/",$idcard))
        {

            return $this->isCreditNo($idcard);
            return true;
        }
        else if(strlen($idcard)==18){
            $tempstr=substr($idcard,0,17);
            if(preg_match("/^(?:\d{15}|\d{17})$/",$tempstr))
            {
                return $this->isCreditNo($idcard);
                return true;
            }else{return false;}
        }else{return false;}
    }

    /**
     * 判定是否为邮件
     * @param $mail 邮件
     * @return int
     */
    function isMail($mail) {
        return preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $mail);
    }

    /**
     * 身份证验证
     * @param $vStr 身份证号码
     * @return bool
     */
    function isCreditNo($vStr)
    {
        $vCity = array(
            '11','12','13','14','15','21','22',
            '23','31','32','33','34','35','36',
            '37','41','42','43','44','45','46',
            '50','51','52','53','54','61','62',
            '63','64','65','71','81','82','91'
        );

        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;

        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;

        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);

        if ($vLength == 18)
        {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }

        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18)
        {
            $vSum = 0;

            for ($i = 17 ; $i >= 0 ; $i--)
            {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
            }

            if($vSum % 11 != 1) return false;
        }

        return true;
    }

    /**
     * 判定是否为手机访问
     * @return bool
     */
    public function isMobileAccess()
    {
        $is_iPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
        if($is_iPad==true){
            return false;
        }

        if (strpos($_SERVER['HTTP_USER_AGENT'],"MicroMessenger") !== false ) {
            return true;
        }

        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }

    
}