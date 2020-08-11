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
    'defaultRoute' => 'index',
    'language'=>'zh-CN',
    'sourceLanguage'=>'en-US',
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'feedback' => ['class'=>'api\modules\feedback\Module'],//反馈模块,模块化模板
    ],
    'components' => [
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
