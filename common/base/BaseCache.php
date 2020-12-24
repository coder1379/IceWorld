<?php

namespace common\base;

use Yii;

/**
 * 基础缓存封装
 * @package common\base
 */
class BaseCache
{

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

        //使用redis 与cache 2选一 start
        /*if (empty($db)) {
            $db = Yii::$app->redis;
        }
        return $db->setex($key, $expireTime, $value);*/
        //使用redis 与cache 2选一 end


        //使用文件cache保存 start 与上注释redis 2选一 默认使用的文件
        if (empty($db)) {
            $db = Yii::$app->cache;
        }
        return $db->set($key, $value, $expireTime);
        //使用文件cache end
    }

    /**
     * 获取缓存值
     * @param $key
     * @param object $db 存储数据主体(默认redis或cache),保存到不同存储区时使用
     * @return mixed
     */
    public static function getVal($key, $db = null)
    {
        $key = strval($key);//设置项目id前缀避免重复覆盖

        //使用redis 与cache 2选一 start
        /*if (empty($db)) {
            $db = Yii::$app->redis;
        }
        return $db->get($key);*/
        //使用redis 与cache 2选一 end


        //使用文件cache start 与上注释redis 2选一 默认使用的文件
        if (empty($db)) {
            $db = Yii::$app->cache;
        }
        return $db->get($key);
        //使用文件cache end
    }

    /**
     * 删除缓存值
     * @param $key
     * @param object $db 存储数据主体(默认redis或cache),保存到不同存储区时使用
     * @return mixed
     */
    public static function deleteVal($key, $db = null)
    {
        $key = strval($key);//设置项目id前缀避免重复覆盖

        //使用redis 与cache 2选一 start
        /*if (empty($db)) {
            $db = Yii::$app->redis;
        }
        return $db->del($key);*/
        //使用redis 与cache 2选一 end


        //使用文件cache start 与上注释redis 2选一 默认使用的文件
        if (empty($db)) {
            $db = Yii::$app->cache;
        }
        return $db->delete($key);
        //使用文件cache end
    }
}
