<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456789QYIEJNABCD12345',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment

    function checkDebugAccessAuthShowListAPI($action = null)
    {
        $modelId = $action->controller->module->id??'';
        $apiDebugSaveId = Yii::$app->getRequest()->get('tempapidebugseaveid');
        $md5TmpKey = Yii::$app->params['md5_tmp_key'];
        $saveCookieKey = Yii::$app->params['api_debug_access_cookie'];
        if(!empty($saveCookieKey) && strlen($saveCookieKey)>32 && strlen($saveCookieKey)<300 && $apiDebugSaveId === $saveCookieKey){
            $cookies = Yii::$app->response->getCookies();
            $cookies->add(new \yii\web\Cookie([
                'name' => 'api_debug_'.md5($saveCookieKey.'_'.$md5TmpKey),
                'value' => 'api_debug_value_'.md5($saveCookieKey).'_'.$md5TmpKey,
                'expire'=>time()+3600,
            ]));
            return true;
        }

        $tempCookie = Yii::$app->request->cookies->getValue('api_debug_'.md5($saveCookieKey.'_'.$md5TmpKey));
        if($tempCookie === 'api_debug_value_'.md5($saveCookieKey).'_'.$md5TmpKey){
            return true;
        }

        if(empty($action) || strtolower($modelId)!='debug'){
            return true;
        }

        return false;
    }

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']['class'] = 'yii\debug\Module';
    $config['modules']['debug']['allowedIPs'] = ['127.0.0.1', '*']; // 待测试 如果不知道任何ip无法进行访问
    $config['modules']['debug']['checkAccessCallback'] = "checkDebugAccessAuthShowListAPI";
    $config['modules']['debug']['historySize'] = 200;
}

return $config;
