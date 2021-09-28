<?php


namespace common\lib;

use common\ComBase;

/**
 * 调用限制，目前主要用于短信调用限制
 * 设计规则：
 * 第一步 前端直接调用接口 不添加任何其他参数 返回：svtime=time(),m=m5(m5)time最后两位为l位置(*1小62为当前位置,过62取模为位置),type=不存在(直接处理并传回),1(加密前后约定k),2(数字验证码),3(行为验证码),4(结束) 等
 * 第二步递归判断(注意控制最多不超过5个防止异常导致无线循环)type类型并处理=不存在 判断l位置 替换为大i 规则见*1 回传 并添加参数 ff_type=type
 *                     type=1 添加传回参数svtime=第一步返回svtime,第一步m=m,m5=m5(svtime+m+key1)
 *                     type=2 显示图形码 传回带上type=1的 check_m5=m5(svtime+m+key2)
 *                     type=3 行为验证码 传回带上type=1的 check_m5=m5(svtime+m+key3)
 *                     type=4 结束
 * Class CallLimit
 * @package common\lib
 */
class CallLimit
{
    protected $firstKeys = [];
    protected $limit1 = 1000; // 第一集限制

    /**
     * 检查
     * @param $params
     * @return bool 如果返回值！=true直接返回给前端 为true向下进行
     */
    public function check($params,$keys){
        $key0 = $keys['key0']; // 后端使用
        $key1 = $keys['key1']; // 前端对应type1
        $key2 = $keys['key2']; // 前端对应type2
        $key3 = $keys['key3']; // 前端对应type3
        if(empty($key0) || empty($key1) || empty($key2) || empty($key3)){
            throw new \Exception('callLimit 调用限制keys参数缺失 需要4个key');
        }

        $nowTime = time();

        $ffType = ComBase::getIntVal('ff_type',$params);

        if(empty($ffType)){
            // $ffType 不存在为第一步返回数据
            $m = StringHandle::getMd5ReplaceLStr(md5($nowTime . '_' . $key0),$nowTime);
            return ComBase::getReturnArray(['svtime' => $nowTime, 'm' => $m]);
        }else if($ffType===1){
            $svtime = ComBase::getIntVal('svtime', $params);

            if(($svtime+30)>$nowTime){
                // 时间未过期还有效

            }

        }


        return ComBase::getReturnArray(['type'=>4]);
    }

}