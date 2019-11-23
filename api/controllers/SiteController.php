<?php

namespace api\controllers;

use Yii;
use common\services\site\SiteApiModel;
use common\services\site\SiteLogic;
use common\controllers\ApiCommonAuthContoller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * SiteController implements the CRUD actions for SiteApiModel model.
 */
class SiteController extends ApiCommonAuthContoller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionList()
    {
        $logic = new SiteLogic();
        $result = $logic->list($this->post());
        return Json::encode($result);
    }

    public function actionDetail()
    {
        $logic = new SiteLogic();
        $result = $logic->detail($this->post());
        return Json::encode($result);
    }

    public function actionCreate()
    {
        $logic = new SiteLogic();
        $result = $logic->create($this->post());
        return Json::encode($result);
    }

    public function actionUpdate()
    {
        $logic = new SiteLogic();
        $result = $logic->update($this->post());
        return Json::encode($result);
    }

    public function actionDelete()
    {
        $logic = new SiteLogic();
        $result = $logic->delete($this->post());
        return Json::encode($result);
    }

}
