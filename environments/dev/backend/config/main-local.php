<?php

$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=name_db;port=3306',
            'tablePrefix' => 'm_',
            'username' => 'root',
            'password' => 'password',
            'charset' => 'utf8',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'apiCrud' => [ //生成器名称
                'class' => 'backend\giitemplate\apiCrud\Generator',
                'templates' => [ //设置我们自己的模板
                    //模板名 => 模板路径
                    'apiCrud' => '@app/giitemplate/apiCrud/default',
                ]
            ],
            'apiModel' => [ //生成器名称
                'class' => 'backend\giitemplate\apiModel\Generator',
                'templates' => [ //设置我们自己的模板
                    //模板名 => 模板路径
                    'apiModel' => '@app/giitemplate/apiModel/default',
                ]
            ],
            'crud' => [ //生成器名称
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [ //设置我们自己的模板
                    //模板名 => 模板路径
                    'BackendCrud' => '@app/giitemplate/crud/default',
                ]
            ],
            'model' => [ //生成器名称
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [ //设置我们自己的模板
                    //模板名 => 模板路径
                    'BackendModel' => '@app/giitemplate/model/default',
                ]
            ]
        ],
    ];
}

return $config;
