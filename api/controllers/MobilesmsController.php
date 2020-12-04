<?php

namespace api\controllers;

use Yii;
use common\services\message\MobileSmsLogic;
use common\controllers\ApiCommonContoller;
use yii\helpers\Json;

/**
 * 短信记录
 * MobilesmsController implements the CRUD actions for MobileSmsApiModel model.
 */
class MobilesmsController extends ApiCommonContoller
{
    public $enableCsrfValidation = false;

    /**
     * 获取短信记录列表
     * @notes
     * @param int $page 页数 0 0
     * @param int $page_size 每页数量 0 10
     * @return json yes {"data":{"list":[{"@model":"common\services\message\MobileSmsApiModel","@fields":"list"}],@pagination}}
     */
    public function actionList()
    {
        $logic = new MobileSmsLogic();
        $result = $logic->list($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 获取短信记录详情
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":{"@model":"common\services\message\MobileSmsApiModel","@fields":"detail"}}
     */
    public function actionDetail()
    {
        $logic = new MobileSmsLogic();
        $result = $logic->detail($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 创建短信记录
     * @notes
     * @param @model common\services\message\MobileSmsApiModel create
     * @return json yes {"data":{"id":"[number] ID"}}
     */
    public function actionCreate()
    {
        $logic = new MobileSmsLogic();
        $result = $logic->create($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 修改短信记录
     * @notes
     * @param int $id ID 1
     * @param @model common\services\message\MobileSmsApiModel update
     * @return json yes {"data":null}
     */
    public function actionUpdate()
    {
        $logic = new MobileSmsLogic();
        $result = $logic->update($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 删除短信记录
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":null}
     */
    public function actionDelete()
    {
        $logic = new MobileSmsLogic();
        $result = $logic->delete($this->post(), $this->getUserId());
        return Json::encode($result);
    }

    /**
     * 物理删除默认屏蔽，需要自行打开短信记录
     * @notes
     * @param int $id ID 1
     * @return json yes {"data":null}
     */
    /*public function actionPhysiedelete()
    {
        $logic = new MobileSmsLogic();
        $result = $logic->physieDelete($this->post(), $this->getUserId());
        return Json::encode($result);
    }*/

}
