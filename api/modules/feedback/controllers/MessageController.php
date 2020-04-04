<?php

namespace api\modules\feedback\controllers;

use common\controllers\BaseContoller;
use yii\helpers\Json;

/**
 * Message controller for the `abc` module
 */
class MessageController extends BaseContoller
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
