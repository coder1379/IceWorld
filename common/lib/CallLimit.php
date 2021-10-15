<?php


namespace common\lib;

use common\base\BaseCache;
use common\ComBase;
use Yii;

/**
 * 使用场景：主要用在一些防止批量调用得地方，目前主要用于短信调用限制
 * 设计规则：前端第一次直接调用接口注意t参数不传或者为0,随后判断返回t除1,2,9外均结束并提示成功,129均按预定进行处理 type=1(加密前后约定k),2(数字验证码,1010=验证码5分钟过期,重新获取并提交,type=2时就需要显示验证码(调用captchas/imagecodecaptcha获取,注意点击可以刷新)与输入验证码区域，输入验证码点击确定后进入第二步),9(替换)
 * 第一步 前端直接调用接口 返回：t=n,e=time(),m=md5(md5(time+'_key0+'_'+'$keyword'))替换24位位l为I,time最后两位为l位置(位置%24+2)
 * 第二步 根据第一步返回不同处理并回传对应需要参数结束流程
 *       t=1 回传t=9外+m5=m5m5(e+_+key1+_+m)
 *       t=2 回传t=9外+m5=m5m5(e+_+key2+_+m+'_'+c),c=c(图形验证码)
 *       t=9 回传t=t,e=e,m=m(t最后两位为l位置(位置%24+2),替换为I回传）
 *结束
 *ip地址，mac地址，手机号等内容可自行扩充
 * Class CallLimit
 *
 * 前端使用方式如上。
 * 后端使用方式：***
 * 调用的地方创建calllimt对象，传入初始化参数对象或在params相关配置中配置然后初始化时传入。
 * 调用完成相关方法后在调用 setStatisticsLevel 设置统计数及等级即可
 *
 * @package common\lib
 */
class CallLimit
{
    const LEVEL_MD5 = 1; // md5加密枚举
    const LEVEL_IMG_CODE = 2; // 图像验证码加密枚举
    const LEVEL_REPLACE = 9; // 替换枚举

    public $imageCodeCachePre = 'calllimit_img_captcha_'; // 缓存的图片验证码前缀
    public $statisticsSavePre = 'calllimit_cache_'; // 限速缓存的关键字 默认值
    public $imgCodeTimeout = 300; // 图像验证码过期时间 默认5分钟 可自行覆盖
    public $md5Keys = null; // md5加密串
    public $level1DayMax = 100; // 等级1每日最大值 可配置或传入覆盖
    public $level1HourMax = 50; // 等级1 每小时最大值 可配置或传入覆盖
    public $level2DayMax = 500; // 等级2每日最大值 可配置或传入覆盖
    public $level2HourMax = 200; // 等级2每小时最大值 可配置或传入覆盖

    private $captchaTimeoutCode = 1010; // 验证码失效返回代号


    public function __construct($configs)
    {
        if(empty($configs['keywords_pre_name']) || empty($configs['statistics_pre_name'])){
            throw new \Exception('callLimit 调用限制缺少keywords_pre_name,statistics_pre_name默认值');
        }
        $this->imageCodeCachePre = $configs['keywords_pre_name'];
        $this->statisticsSavePre = $configs['statistics_pre_name'];
        if(!empty($configs['img_code_timeout'])){
            $this->imgCodeTimeout = $configs['img_code_timeout'];
        }
        if(!empty($configs['level_1_day_max'])){
            $this->level1DayMax = $configs['level_1_day_max'];
        }

        if(!empty($configs['level_1_hour_max'])){
            $this->level1HourMax = $configs['level_1_hour_max'];
        }

        if(!empty($configs['level_2_day_max'])){
            $this->level2DayMax = $configs['level_2_day_max'];
        }

        if(!empty($configs['level_2_hour_max'])){
            $this->level2HourMax = $configs['level_2_hour_max'];
        }

        $this->md5Keys = Yii::$app->params['call_limit']['md5_keys'];
    }

    /**
     * 检查
     * @param $params array 前端传递参数数组
     * @param $keyword string 关键字 例如手机号
     * @return mixed 如果返回值！==true直接返回给前端 为true向下进行
     */
    public function verifyRequest($params,$keyword){
        $key0 = $this->md5Keys['key0'] ?? null; // 后端使用用于在低一步进行md5加密
        $key1 = $this->md5Keys['key1'] ?? null; // 前端对应type1
        $key2 = $this->md5Keys['key2'] ?? null; // 前端对应type2
        if(empty($key0) || empty($key1) || empty($key2) ){
            throw new \Exception('callLimit 调用限制keys参数缺失 需要3个key');
        }
        $nowTime = time();
        $nowMicTime = TimeLib::getMicrotimeInt();
        $t = ComBase::getIntVal('t',$params); // 类别

        if(empty($t)){
            // 第一步返回
            $m = $this->getMd5ReplaceLStr($this->md5md5($nowMicTime,$key0,$keyword),$nowMicTime);
            return ComBase::getReturnArray(['t'=>$this->getCurrentLimitLevel(),'e' => $nowMicTime, 'm' => $m]);
        }else{
            $eReq = ComBase::getStrVal('e', $params); // 时间
            $mReq = ComBase::getStrVal('m', $params); // 加密替换后的值
            $eTime = ceil(intval($eReq) / 1000);

            $mOld = $this->getMd5ReplaceLStr($this->md5md5($eReq,$key0,$keyword),$eReq); // 重新生成的md5加密值

            if($this->checkParamsStatus($mReq,$mOld,$eReq)){
                if($t===self::LEVEL_REPLACE){
                    if(($eTime+10)>$nowTime){
                        // 时间未过期还有效,替换字符串模式有效时间不能超过10秒
                        return true;
                    }
                }else if($t===self::LEVEL_MD5){
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
                }else if($t===self::LEVEL_IMG_CODE){
                    //图形验证码模式
                    if(($eTime+$this->imgCodeTimeout)>$nowTime){
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
       return BaseCache::getVal($this->imageCodeCachePre.$keyword);
    }

    /**
     * 设置发送量及计算等级，可自行扩展风控去设置 $this->statisticsSavePre.'cur_level'的值,此处仅简单通过数量处理
     */
    public function setStatisticsLevel(){
        // 缓存的keyname
        $nowTime = time();
        $currentDayPre = $this->statisticsSavePre.date('Ymd', $nowTime).'_';
        $currentHourPre = $this->statisticsSavePre.date('YmdH', $nowTime).'_';

        $level1DayKey = $currentDayPre.'1day';
        $level1HourKey = $currentHourPre.'1hour';
        $level2DayKey = $currentDayPre.'2day';
        $level2HourKey = $currentHourPre.'2hour';

        $daySec = 86400; // 按天缓存过期时间
        $hourSec = 3600; // 按小时缓存过期时间

        //自增对应缓存的值并获取返回值
        $level1DayKeyVal = BaseCache::getIncrValAndLt3SetEx($level1DayKey,$daySec);
        $level1HourKeyVal = BaseCache::getIncrValAndLt3SetEx($level1HourKey,$hourSec);
        $level2DayKeyVal = BaseCache::getIncrValAndLt3SetEx($level2DayKey,$daySec);
        $level2HourKeyVal = BaseCache::getIncrValAndLt3SetEx($level2HourKey,$hourSec);

        $currentLevelVarName = $this->getTodayCurrentLevelVarName();

        if($level2DayKeyVal>$this->level2DayMax || $level2HourKeyVal>$this->level2HourMax){

            $currentLevelVal = $this->getCurrentLimitLevel();
            if($currentLevelVal===self::LEVEL_IMG_CODE){ // 如果当前等级已经等于2则不在修改设置
                // 大于类型2图像验证码设置之后的访问需要验证码模式，当前等级过期时间为当天,可以考虑按小时进行重置
                $retVal = BaseCache::setExVal($currentLevelVarName, self::LEVEL_IMG_CODE,$daySec);
                if(empty($retVal)){
                    // 等级切换失败,记录错误并提醒
                    Yii::error('等级切换失败：calllimt限制等级切换为'.self::LEVEL_IMG_CODE.'不成功,注意排查问题,分类key：'.$this->statisticsSavePre);
                }else{
                    Yii::error('发生calllimt限制等级切换为'.self::LEVEL_IMG_CODE.',分类key：'.$this->statisticsSavePre);
                }
            }
        }else if($level1DayKeyVal>$this->level1DayMax || $level1HourKeyVal>$this->level1HourMax){
            // md5 类型范围内
            $currentLevelVal = $this->getCurrentLimitLevel();
            if($currentLevelVal===self::LEVEL_MD5){ // 如果当前等级已经等于1则不在修改设置
                // 大于类型1 md5 设置之后的访问需要验证码模式，当前等级过期时间为当天
                $retVal = BaseCache::setExVal($currentLevelVarName, self::LEVEL_MD5,$daySec);
                if(empty($retVal)){
                    // 等级切换失败,记录错误并提醒
                    Yii::error('等级切换失败：calllimt限制等级切换为'.self::LEVEL_MD5.'不成功,注意排查问题,分类key：'.$this->statisticsSavePre);
                }else{
                    Yii::error('发生calllimt限制等级切换为'.self::LEVEL_MD5.',分类key：'.$this->statisticsSavePre);
                }
            }
        }
    }

    /**
     * 获取今天当前等级的变量名
     */
    public function getTodayCurrentLevelVarName(){
        return $this->statisticsSavePre . date('Ymd') . '_cur_level';
    }

    /**
     * 获取当前限制等级
     */
    public function getCurrentLimitLevel(){
        $currentLimitLevel = BaseCache::getVal($this->getTodayCurrentLevelVarName());
        if(empty($currentLimitLevel)){
            $currentLimitLevel = self::LEVEL_REPLACE;
        }
        return intval($currentLimitLevel);
    }

}