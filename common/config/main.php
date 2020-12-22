<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'timeZone' => 'Asia/Shanghai',
    'bootstrap'=> [
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
        'queue' => [//队列服务器
            //使用db最为数据存储 与 redis二选一 db主要用于不方便使用redis时```
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',//db对象id
            'tableName' => '{{%queue}}', // 表名
            'mutex' => \yii\mutex\MysqlMutex::class,
            //db end```

            //使用redis作为数据存储 与db二选一，优先redis性能更好````
            //'class' => \yii\queue\redis\Queue::class,
            //'redis' => 'redis',//redis对象id
            //reids end````

            'channel' => 'ice_world_yii_queue', // 前缀名多项目共用redis需配置不同前缀
            'ttr' => 5, // Max time for anything job handling
            'attempts' => 3, // Max number of attempts
            'as log'=> \yii\queue\LogBehavior::class,//日志
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
