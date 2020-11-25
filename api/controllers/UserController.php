<?php

namespace api\controllers;

use Yii;
//use common\services\user\UserApiModel;
use common\services\user\UserLogic;
use common\controllers\ApiCommonContoller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\ComBase;

/**
 * 用户
 * UserController implements the CRUD actions for UserApiModel model.
 */
class UserController extends ApiCommonContoller
{
    public $enableCsrfValidation = false;

    /**
     * 获取用户列表
     * @notes
     * @param int $page 页数 0 0
     * @param int $page_size 每页数量 0 10
     * @return json yes {"data":{"list":[{"@model":"common\services\user\UserApiModel","@fields":"list"}],@pagination}}
     */
    public function actionList()
    {
        $logic = new UserLogic();
        $result = $logic->list($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 获取用户详情
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":{"@model":"common\services\user\UserApiModel","@fields":"detail"}}
     */
    public function actionDetail()
    {
        $logic = new UserLogic();
        $result = $logic->detail($this->post(), 10);
        return Json::encode($result);
    }

    /**
     * 创建用户
     * @notes
     * @param @model common\services\user\UserApiModel create
     * @return json yes {"data":{"id":"[number] ID"}}
     */
    public function actionCreate()
    {
        $logic = new UserLogic();
        $result = $logic->create($this->post(), 10);
        return Json::encode($result);
    }

    /**
     * 修改用户
     * @notes
     * @param int $id ID 1
     * @param @model common\services\user\UserApiModel update
     * @return json yes {"data":null}
     */
    public function actionUpdate()
    {
        $logic = new UserLogic();
        $result = $logic->update($this->post(), 10);
        return Json::encode($result);
    }

    /**
     * 删除用户
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":null}
     */
    public function actionDelete()
    {
        $logic = new UserLogic();
        $result = $logic->delete($this->post(), 10);
        return Json::encode($result);
    }

    /**
     * 物理删除默认屏蔽，需要自行打开用户
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":null}
     */
    public function actionPhysiedelete()
    {
        $logic = new UserLogic();
        $result = $logic->physieDelete($this->post(), 10);
        return Json::encode($result);
    }

}
