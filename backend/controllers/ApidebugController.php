<?php

namespace backend\controllers;

use Yii;
use common\lib\Curl;

class ApidebugController extends AuthController
{
    /**
     * api debug 快捷页面
     * @inheritdoc
     */
    public $layout = 'main-iframe';

    /**
     * Lists all WebsiteNewsModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', []);
    }


}
