<?php
/**
 * create by: majie
 * datetime: 2018-02-13 0:05
 * desc：
 */

namespace common\lib;

class StringHandle{
    /**
     * 获取随机数字字符
     * @param int $length
     * @return string
     */
    public function getRandomNumber($length=6,$chars='')
    {
        $chars=trim($chars);
        if(empty($chars)){
            $chars = '0123456789';
        }
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++)	{
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 获取随机字符
     * @param int $length 长度
     * @param string $searchStr 被选字符 为空默认a-zA-Z0-9
     * @return string
     */
    public function getRandomString($length=4,$searchStr='')
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        if(!empty($searchStr)){
            $chars=trim($searchStr);
        }

        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++)	{
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
    
    /**
     * 字符串指定位置加*
     * @param string $needpstr 原字符串
     * @param int $positionflag *所在位置 方位 1 前，2中，3后
     * @param int $length 需要添加的*的长度 0 为自动判断
     * @return mixed|string
     */
    public function getStarsString($needpstr="",$positionflag=2,$length=0)
    {
        $returnpsstr="";
        if($needpstr!=""){
            $strlengh=strlen($needpstr);
            $plusstartnum=$length;
            if($length==0 || $strlengh<$length){
                $plusstartnum=ceil($strlengh/4);
            }
            if($plusstartnum>0){
                $replarcestr=str_repeat("*",$plusstartnum);
                if($positionflag==1){
                    $returnpsstr=substr_replace($needpstr,$replarcestr,0,$plusstartnum);
                }else if($positionflag==3){
                    $returnpsstr=substr_replace($needpstr,$replarcestr,0-$plusstartnum,$plusstartnum);
                }else if($positionflag==2){
                    if($plusstartnum>ceil($strlengh/2)){
                        $returnpsstr=substr_replace($needpstr,$replarcestr,0-$plusstartnum-2,$plusstartnum);
                    }else{
                        $returnpsstr=substr_replace($needpstr,$replarcestr,floor($strlengh/2),$plusstartnum);
                    }
                }
            }
        }

        return $returnpsstr;
    }


    /**
     * 截取指定长度的字符串，超出添加指定字符
     * @param $str 原字符串
     * @param int $len 截取长度
     * @param string $etc 超出文本
     * @return string 返回
     */
    function getSubString($str, $len=10, $etc='...')
    {
        $restr = '';
        $i = 0;
        $n = 0.0;

        //字符串的字节数
        $strlen = strlen($str);
        while(($n < $len) and ($i < $strlen))
        {
            $temp_str = substr($str, $i, 1);

            //得到字符串中第$i位字符的ASCII码
            $ascnum = ord($temp_str);

            //如果ASCII位高与252
            if($ascnum >= 252)
            {
                //根据UTF-8编码规范，将6个连续的字符计为单个字符
                $restr = $restr.substr($str, $i, 6);
                //实际Byte计为6
                $i = $i + 6;
                //字串长度计1
                $n++;
            }
            elseif($ascnum >= 248)
            {
                $restr = $restr.substr($str, $i, 5);
                $i = $i + 5;
                $n++;
            }
            elseif($ascnum >= 240)
            {
                $restr = $restr.substr($str, $i, 4);
                $i = $i + 4;
                $n++;
            }
            elseif($ascnum >= 224)
            {
                $restr = $restr.substr($str, $i, 3);
                $i = $i + 3 ;
                $n++;
            }
            elseif ($ascnum >= 192)
            {
                $restr = $restr.substr($str, $i, 2);
                $i = $i + 2;
                $n++;
            }

            //如果是大写字母 I除外
            elseif($ascnum>=65 and $ascnum<=90 and $ascnum!=73)
            {
                $restr = $restr.substr($str, $i, 1);
                //实际的Byte数仍计1个
                $i = $i + 1;
                //但考虑整体美观，大写字母计成一个高位字符
                $n++;
            }

            //%,&,@,m,w 字符按1个字符宽
            elseif(!(array_search($ascnum, array(37, 38, 64, 109 ,119)) === FALSE))
            {
                $restr = $restr.substr($str, $i, 1);
                //实际的Byte数仍计1个
                $i = $i + 1;
                //但考虑整体美观，这些字条计成一个高位字符
                $n++;
            }

            //其他情况下，包括小写字母和半角标点符号
            else
            {
                $restr = $restr.substr($str, $i, 1);
                //实际的Byte数计1个
                $i = $i + 1;
                //其余的小写字母和半角标点等与半个高位字符宽
                $n = $n + 0.5;
            }
        }

        //超过长度时在尾处加上省略号
        if($i < $strlen)
        {
            $restr = $restr.$etc;
        }

        return $restr;
    }


    /**
     * 获取指定编码
     * @param $data 数据
     * @param $to 需要返回的编码
     * @return string
     */
    function getEncoding($data, $to)
    {
        $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
        $encoded = mb_detect_encoding($data, $encode_arr);
        $data = mb_convert_encoding($data,$to,$encoded);
        return $data;
    }

    /**
     * 将 xml 文本数据转换为数组格式
     * @param $xml
     * @return array
     */
    public 	function xml_to_array($xml){
        $arr=array();
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    /**
     * 生成唯一md5值
     * @return string
     */
    function createMd5(){
        //生成一个不会重复的字符串
        $str = md5(uniqid(md5(microtime(true)),true).'_'.$this->getRandomString(5));
        return $str;
    }

    /**
     * 生成唯一token 用于保存到数据库中
     * @return string
     */
    function createToken(){
        //生成一个不会重复的字符串
        $str = md5(uniqid(md5(microtime(true)),true).'_'.$this->getRandomString(10));
        //加密
        $str = sha1($str);
        return $str;
    }
    
    
}