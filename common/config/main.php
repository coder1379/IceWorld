<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'timeZone' => 'Asia/Shanghai',
    'bootstrap'=> [
        'queue',
        'log',
    ],
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                    ],
                ],
            ],
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
