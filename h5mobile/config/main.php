<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'h5mobile',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'home',
    'controllerNamespace' => 'h5mobile\controllers',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'home/404',
        ],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'suffix'=>'.html',
		],
    ],
    'params' => $params,
];
