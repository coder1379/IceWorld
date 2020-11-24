<?php
//上线正式使用代码
/*return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];*/

//上线观测阶段加入debug
$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

// configuration adjustments for 'dev' environment
function checkDebugAccessAuthShowList($action = null)
{
    $modelId = $action->controller->module->id ?? '';
    $modelId = strtolower($modelId);
    $adminRoleId = Yii::$app->session["admin.roleid"] ?? 0;
    $adminRoleId = intval($adminRoleId);
    $allowRole = Yii::$app->params['debug_access_role'] ?? [];
    if ($modelId === 'debug') {
        if (in_array($adminRoleId, $allowRole, true)) {
            return true;
        } else {
            return false;
        }
    } else {
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

return $config;
