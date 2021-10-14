<?php

namespace common\base;

use Yii;

/**
 * 基础缓存封装
 * @package common\base
 */
class BaseCache
{

    private static $cacheType = 1;// 1=文件缓存，2=redis 缓存类型 根据业务需要修改

    /**
     * 同步设置缓存值与过期时间
     * @param $key
     * @param $value
     * @param $expireTime
     * @param object $db 存储数据主体(默认redis或cache),保存到不同存储区时使用
     * @return bool
     */
    public static function setExVal($key, $value, $expireTime, $db = null)
    {
        $expireTime = intval($expireTime);
        $key = strval($key);

        if(self::$cacheType===2){
            // redis
            if (empty($db)) {
                $db = Yii::$app->redis;
            }
            return $db->setex($key, $expireTime, $value);
        }else if(self::$cacheType===1){
            // 文件
            if (empty($db)) {
                $db = Yii::$app->cache;
            }
            return $db->set($key, $value, $expireTime);
        }
    }

    /**
     * 获取缓存值
     * @param $key
     * @param object $db 存储数据主体(默认redis或cache),保存到不同存储区时使用
     * @return mixed
     */
    public static function getVal($key, $db = null)
    {
        $key = strval($key);

        if(self::$cacheType===2){
            //redis
            if (empty($db)) {
                $db = Yii::$app->redis;
            }
            return $db->get($key);
        }else if(self::$cacheType===1){
            //file
            if (empty($db)) {
                $db = Yii::$app->cache;
            }
            return $db->get($key);
        }
    }

    /**
     * 删除缓存值
     * @param $key
     * @param object $db 存储数据主体(默认redis或cache),保存到不同存储区时使用
     * @return mixed
     */
    public static function deleteVal($key, $db = null)
    {
        $key = strval($key);

        if(self::$cacheType===2){
            // redis
            if (empty($db)) {
                $db = Yii::$app->redis;
            }
            return $db->del($key);
        }else if(self::$cacheType===1){
            // file
            if (empty($db)) {
                $db = Yii::$app->cache;
            }
            return $db->delete($key);
        }

    }

    /**
     * 获取自增后值，设置key自增如果小于3都将设置过期时间，尽可能防止参数脏数据
     * @param $key
     * @param $expireTime int 多少秒后过期
     * @param object $db 存储数据主体(默认redis或cache),保存到不同存储区时使用
     * @return bool
     */
    public static function getIncrValAndLt3SetEx($key, $expireTime, $db = null)
    {
        $expireTime = intval($expireTime);
        $key = strval($key);

        if(self::$cacheType===2){
            // redis
            if (empty($db)) {
                $db = Yii::$app->redis;
            }

            $incrNum = $db->incr($key);
            if(intval($incrNum)<3){
                $db->expire($key, $expireTime);
            }
            return $incrNum;
        }else if(self::$cacheType===1){
            // 文件
            if (empty($db)) {
                $db = Yii::$app->cache;
            }
            // 文件模式没有自增只能采用
            $cacheIncrNum = $db->get($key);
            $cacheIncrNum = intval($cacheIncrNum);
            if($cacheIncrNum>0){
                $cacheIncrNum = $cacheIncrNum + 1;
                $db->set($key, $cacheIncrNum, $expireTime);
            }else{
                $cacheIncrNum = 1;
                $db->set($key, $cacheIncrNum, $expireTime);
            }

            return $cacheIncrNum;
        }
    }
}
