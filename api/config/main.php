<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'index',
    'language'=>'zh-CN',
    'sourceLanguage'=>'en-US',
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'log' => [
				'traceLevel' => YII_DEBUG ? 3 : 0,
				'targets' => [
					[
						'class' => 'yii\log\FileTarget',
						'levels' => ['error'],
						'logFile' => '@app/runtime/logs/error/error'.date("Y-m-d").'.log',
						'maxFileSize' => 1024 * 10,
						'maxLogFiles' => 100,
					],
					[
						'class' => 'yii\log\FileTarget',
						'levels' => ['warning'],
						'logFile' => '@app/runtime/logs/warning/warning'.date("Y-m-d").'.log',
						'maxFileSize' => 1024 * 10,
						'maxLogFiles' => 100,
					],
					[
						'class' => 'yii\log\FileTarget',
						'levels' => ['info'],
						'logFile' => '@app/runtime/logs/info/info'.date("Y-m-d").'.log',
						'maxFileSize' => 1024 * 10,
						'maxLogFiles' => 100,
					],
				],
			],
        'errorHandler' => [
            'errorAction' => 'index/404',
        ],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'suffix'=>'',
		],
    ],
    'params' => $params,
];
