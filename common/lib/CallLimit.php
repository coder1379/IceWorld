<?php


namespace common\lib;

use common\base\BaseCache;
use common\ComBase;

/**
 * 使用场景：主要用在一些防止批量调用得地方，目前主要用于短信调用限制
 * 设计规则：前端第一次直接调用接口注意t参数不传或者为0,随后判断返回t除1,2,9外均结束并提示成功,129均按预定进行处理 type=1(加密前后约定k),2(数字验证码,1010=验证码5分钟过期,重新获取并提交,type=2时就需要显示验证码(调用captchas/captcha获取,注意点击可以刷新)与输入验证码区域，输入验证码点击确定后进入第二步),9(替换)
 * 第一步 前端直接调用接口 返回：t=n,e=time(),m=md5(md5(time+'_key0+'_'+'$keyword'))替换24位位l为I,time最后两位为l位置(位置%24+2)
 * 第二步 根据第一步返回不同处理并回传对应需要参数结束流程
 *       t=1 回传t=9外+m5=m5m5(e+_+key1+_+m)
 *       t=2 回传t=9外+m5=m5m5(e+_+key2+_+m+'_'+c),c=c(图形验证码)
 *       t=9 回传t=t,e=e,m=m(t最后两位为l位置(位置%24+2),替换为I回传）
 *结束
 *ip地址，mac地址，手机号等内容可自行扩充
 * Class CallLimit
 * @package common\lib
 */
class CallLimit
{
    public $imageCodeCachePre = 'calllimit_img_cache_';

    private $captchaTimeoutCode = 1010; // 验证码失效返回代号

    /**
     * 检查
     * @param $params array 前端传递参数数组
     * @param $keyword string 关键字 例如手机号
     * @param $keys array 密钥数组
     * @param $nextNum int 下一步对应返回t数字 默认为9，外部自行实现数量控制
     * @return mixed 如果返回值！==true直接返回给前端 为true向下进行
     */
    public function verifyRequest($params,$keyword,$keys,$nextNum=9){
        $key0 = $keys['key0']; // 后端使用用于在低一步进行md5加密
        $key1 = $keys['key1']; // 前端对应type1
        $key2 = $keys['key2']; // 前端对应type2
        if(empty($key0) || empty($key1) || empty($key2) ){
            throw new \Exception('callLimit 调用限制keys参数缺失 需要3个key');
        }
        $nowTime = time();
        $nowMicTime = TimeLib::getMicrotimeInt();
        $t = ComBase::getIntVal('t',$params); // 类别

        if(empty($t)){
            // 第一步返回
            $m = $this->getMd5ReplaceLStr($this->md5md5($nowMicTime,$key0,$keyword),$nowMicTime);
            return ComBase::getReturnArray(['t'=>9,'e' => $nowMicTime, 'm' => $m]);
        }else{
            $eReq = ComBase::getStrVal('e', $params); // 时间
            $mReq = ComBase::getStrVal('m', $params); // 加密替换后的值
            $eTime = ceil(intval($eReq) / 1000);

            $mOld = $this->getMd5ReplaceLStr($this->md5md5($eReq,$key0,$keyword),$eReq); // 重新生成的md5加密值

            if($this->checkParamsStatus($mReq,$mOld,$eReq)){
                if($t===9){
                    if(($eTime+10)>$nowTime){
                        // 时间未过期还有效,替换字符串模式有效时间不能超过10秒
                        return true;
                    }
                }else if($t===1){
                    //字符串加密模式
                    if(($eTime+10)>$nowTime){
                        // 时间未过期还有效,字符串加密模式有效时间不能超过10秒
                        $m5 = ComBase::getStrVal('m5', $params);
                        $m5New = $this->md5md5($eReq, $key1, $mReq);
                        if(!empty($m5) && strlen($m5)>30 && strlen($m5)<100){
                            if($m5 === $m5New){
                                // 验证通过返回true
                                return true;
                            }
                        }
                    }
                }else if($t===2){
                    //图形验证码模式
                    if(($eTime+300)>$nowTime){
                        // 时间未过期还有效,验证码模式验证码有效时间不能超过5分钟
                        // 先比较校验码
                        $m5 = ComBase::getStrVal('m5', $params);
                        $dataC = ComBase::getStrVal('c', $params);

                        $mReq = $mReq . '_' . $dataC; // 验证码模式将验证码连下划线添加到m后面
                        $m5New = $this->md5md5($eReq, $key2, $mReq); // 注意这里和前端都是key2
                        if(!empty($m5) && strlen($m5)>30 && strlen($m5)<100){
                            if($m5 === $m5New){

                                // 验证码为空或者不符合规则返回提示
                                if(empty($dataC) || strlen($dataC)<4 || strlen($dataC)>8){
                                    return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,'验证码格式错误');
                                }

                                // md5校验通过进行验证码校验
                                $saveCacheCode = strtolower(strval($this->getImageCode($keyword)));
                                if(empty($saveCacheCode)){
                                    return ComBase::getReturnArray([],$this->captchaTimeoutCode,'验证码已失效'); // 验证码失效告知用户，用户手动或自动刷新验证码，然后重新输入
                                }
                                if(strtolower(strval($dataC)) === $saveCacheCode){
                                    // 验证码相同通过,注意与前端匹配 目前不区分大小写
                                    return true;
                                }else{
                                    return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,'验证码错误');
                                }
                            }
                        }
                    }else{
                        return ComBase::getReturnArray([],$this->captchaTimeoutCode,'验证码已失效'); // 验证码失效告知用户，用户手动或自动刷新验证码，然后重新输入
                    }
                }
            }
        }

        return ComBase::getReturnArray([],ComBase::CODE_RUN_SUCCESS,'短信已发送');
    }

    /**
     * 获取替换位置的值
     * @param $micTime
     * @return int
     */
    public function getReplacePositionStr($micTime){
        return intval(substr($micTime, -2, 2))%24+2;
    }

    /**
     * 获取md5替换为l的字符串，根据毫秒时间戳后三位
     * @param $md5Str
     * @param $micTime
     * @return string
     */
    public function getMd5ReplaceLStr($md5Str,$micTime){
        $micTime = strval($micTime);
        $retainPosition = rand(2,30); // 保留l的位子 倒数第三第二
        $replacePosition = $this->getReplacePositionStr($micTime); // 被替换为l得位子
            if($replacePosition<16){
                $retainPosition = $replacePosition + mt_rand(5,14);
            }else{
                $retainPosition = $replacePosition - mt_rand(5,14);
            }
        //echo $micTime.'_re:' . $retainPosition . '_pl:' . $replacePosition.PHP_EOL;
        return substr_replace(substr_replace($md5Str,'l',$retainPosition,1),'l',$replacePosition,1);
    }

    /**
     * 检查字符串规则是否通过
     * @param $mReqStr string 获取的前端回传值
     * @param $mOldStr string 后端重新加密的值
     * @param $eReqStr string 前端传入的毫秒时间戳
     * @return bool
     */
    public function checkParamsStatus($mReqStr,$mOldStr,$eReqStr){
        if(!empty($mReqStr) && !empty($mOldStr) && strlen($mReqStr)>30 && strlen($mReqStr)<100 && strlen($mOldStr)>30){
            $replacePosition = $this->getReplacePositionStr($eReqStr); // 被替换为l得位子
            $positionVal = substr($mReqStr, $replacePosition, 1);
            $newMd5Str = substr_replace($mReqStr, 'l', $replacePosition, 1);
            if($positionVal==='I' && $newMd5Str === $mOldStr){
                return true;
            }
        }
        return false;
    }

    /**
     * 生成md5
     * @param $time
     * @param $key
     * @param $keyword
     * @return string
     */
    public function md5md5($time,$key,$keyword){
        return md5(md5($time . '_' . $key . '_' . $keyword));
    }

    /**
     * 获取缓存中的图片验证码,注意保存和Captchas/captcha里面的参数一致
     * @param $keyword
     * @return mixed
     */
    public function getImageCode($keyword){
       return BaseCache::getVal('calllimit_img_captcha_'.$keyword);
    }

}