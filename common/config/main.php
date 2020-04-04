<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'timeZone' => 'Asia/Shanghai',
    'bootstrap'=> [
        'queue',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}', // 表名
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class,
            'ttr' => 5, // Max time for anything job handling
            'attempts' => 3, // Max number of attempts
            'as log'=> \yii\queue\LogBehavior::class,//日志
        ],
    ],
];
