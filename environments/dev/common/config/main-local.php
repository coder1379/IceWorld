<?php
return [
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@app/runtime/logs/info/' . date("d-m-Y") . '.log',
                    'maxFileSize' => 1024 * 10,
                    'maxLogFiles' => 100,
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=name_db;port=3306',
            'tablePrefix' => 'm_',
            'username' => 'root',
            'password' => 'password',
            'charset' => 'utf8mb4', // 注意不要覆盖为utf8 要不容易出现特殊字符数据库不支持
        ],
        //reids根据需要开启，测试默认不使用
        /*'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'password' => 'password',
            'database' => 9,
            'retries' => 1,
        ],*/
    ],
];
