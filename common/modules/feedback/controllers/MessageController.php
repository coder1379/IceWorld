<?php

namespace common\modules\feedback\controllers;

use common\controllers\ApiCommonContoller;
use yii\helpers\Json;

/**
 * Message controller for the `test` module
 */
class MessageController extends ApiCommonContoller
{

    public $enableCsrfValidation = false; //取消CSRF验证

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCreate()
    {
        return Json::encode(['test module']);
    }
}
