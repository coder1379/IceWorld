<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=name_db;port=3306',
            'tablePrefix' => 'm_',
            'username' => 'root',
            'password' => 'password',
            'charset' => 'utf8mb4',// 注意不要覆盖为utf8 要不容易出现特殊字符数据库不支持
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'password' => 'password',
            'database' => 9,
            'retries' => 1,
        ],
    ],
];
