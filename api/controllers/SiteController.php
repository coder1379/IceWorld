<?php

namespace api\controllers;

use Yii;
use common\services\site\SiteApiModel;
use common\services\site\SiteLogic;
use common\controllers\ApiCommonAuthContoller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\ComBase;

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
        $params = [];
        $include = [ [ 'name'=>'userRecord', 'fields'=>['id','name','mobile'] ] ];//支持关联数据获取
        $result = $logic->list($this->post(),'list',$include);
        return Json::encode($result);
    }

    public function actionDetail()
    {
        $logic = new SiteLogic();
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $params = ['id'=>$id];
        $include = [ [ 'name'=>'userRecord', 'fields'=>['id','name','mobile'] ] ];//支持关联数据获取
        $result = $logic->detail($params,'detail',$include);
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
        $id = intval($this->post('id',0));
        if(empty($id)){
            return Json::encode(ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR));
        }
        $params = ['id'=>$id];
        $result = $logic->delete($params);
        return Json::encode($result);
    }

}
