<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    function checkDebugAccessAuthShowList($action = null)
    {
        $modelId = $action->controller->module->id??'';
        $modelId = strtolower($modelId);
        $adminRoleId = Yii::$app->session["admin.roleid"]??0;
        $adminRoleId = intval($adminRoleId);
        $allowRole = Yii::$app->params['debug_access_role'] ?? [];
        if($modelId==='debug'){
            if(in_array($adminRoleId,$allowRole,true)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }

    }

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['modules']['debug']['allowedIPs'] = ['127.0.0.1', '*'];
    $config['modules']['debug']['checkAccessCallback'] = "checkDebugAccessAuthShowList";
    $config['modules']['debug']['historySize'] = 200;

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
