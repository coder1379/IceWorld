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
            'charset' => 'utf8',
        ],
    ],
];
