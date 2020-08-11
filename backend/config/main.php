<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'index',
    'controllerNamespace' => 'backend\controllers',
    'modules' => [
	],
    'language'=>'zh-CN',
    'sourceLanguage'=>'en-US',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'index/404',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>'.html',
            'rules' => [
            ],
        ],
        'assetManager'=>[
            'bundles'=>[
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => []
                ],
            ],

        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
    ],
    'params' => $params,
];
