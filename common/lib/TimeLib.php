<?php

namespace common\lib;

/**
 * 时间相关包
 * @package common\lib
 */
class TimeLib{
    /**
     * 获取当前毫秒值 13位
     * @return int
     */
    public static function getMicrotimeInt()
    {
        $microtime = microtime(true);
        $microtime = $microtime * 1000;
        return ceil($microtime);
    }
    
    
}