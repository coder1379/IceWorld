<?php
exit();

class Yii
{
    /**
     * @var PhpstormApplication
     */
    public static $app;
}

/**
 * 解决ide无法提示自定义组件问题
 * @property \yii\redis\Connection  $redis
 * @property \yii\queue\redis\Queue  $queue
 * @property \yii\mongodb\Connection  $mongodb
 */
class PhpstormApplication
{
}