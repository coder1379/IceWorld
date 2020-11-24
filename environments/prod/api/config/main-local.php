<?php

//正式使用配置
/*return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];*/

//上线前期观测阶段配置
$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456789abcdeABCED012345',
        ],
    ],
];


function checkDebugAccessAuthShowListAPI($action = null)
{
    #$actionId = $action->id??'';
    $modelId = $action->controller->module->id ?? '';
    $remIp = Yii::$app->getRequest()->getHeaders()['remoteip'] ?? '';
    $hostName = Yii::$app->getRequest()->getHeaders()['host'] ?? '';
    $allowIp = Yii::$app->params['debug_access_ip'] ?? '';
    $allowHost = Yii::$app->params['debug_access_host'] ?? '';
    if (!empty($remIp) && !empty($allowIp) && in_array($remIp, $allowIp, true)) {
        return true;
    }

    if (!empty($hostName) && in_array($hostName, $allowHost, true)) {
        //本地代码直接返回true
        return true;
    }

    if (empty($action) || strtolower($modelId) != 'debug') {
        return true;
    }

    return false;
}

$config['bootstrap'][] = 'debug';
$config['modules']['debug']['class'] = 'yii\debug\Module';
$config['modules']['debug']['allowedIPs'] = ['127.0.0.1', '*'];
$config['modules']['debug']['checkAccessCallback'] = "checkDebugAccessAuthShowListAPI";
$config['modules']['debug']['historySize'] = 200;

return $config;