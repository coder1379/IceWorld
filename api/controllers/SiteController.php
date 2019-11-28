<?php

namespace api\controllers;

use Yii;
use common\services\site\SiteApiModel;
use common\services\site\SiteLogic;
use common\controllers\ApiCommonAuthContoller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\ComBase;

/**
 * SiteController implements the CRUD actions for SiteApiModel model.
 */
class SiteController extends ApiCommonAuthContoller
{
    public $enableCsrfValidation = false;

    /**
     * 获取列表
     * @param int $page 页数 非必填 默认:0
     * @param int $page_size 每页数量 非必填 默认:10
     * @return json
     */
    public function actionList()
    {
        $fieldScenarios = 'list';
        $logic = new SiteLogic();
        $params = $this->post();//获取前端上传的参数

        //创建查询对象
        $searchModel = new SiteApiModel();
        $searchDataQuery = $searchModel::find();
        $where = [];//添加过滤条件，注意默认是无条件的
        //$where['user_id'] = $this->getUserId();//***默认加入了user_id过滤
        $searchDataQuery->where($where)->with('userRecord');
        $searchDataQuery->orderBy('id desc');//添加默认排序规则

        //获取输出字段
        $printFields = $searchModel->fieldsScenarios()[$fieldScenarios];

        //获取post内的分页数据并格式化
        $paginationParams = $logic->getPaginationParams($params);

        $include = [['name' => 'userRecord', 'fields' => ['id', 'name']]];//支持关联数据获取
        $result = $logic->list($searchDataQuery, $printFields, $paginationParams, $include);
        return Json::encode($result);
    }

    /**
     * 获取明细
     * @param int $id 数据ID 必填
     * @return json
     */
    public function actionDetail()
    {
        $logic = new SiteLogic();
        $id = intval($this->post('id', 0));
        if (empty($id)) {
            return Json::encode(ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR));
        }
        $fieldScenarios = 'detail';
        $where = ['id' => $id];
        //$where['user_id'] = $this->getUserId();//***默认加入了user_id过滤
        $detailModel = new SiteApiModel();
        $detailQuery = $detailModel::find();
        $detailQuery->where($where);
        $printFields = $detailModel->fieldsScenarios()[$fieldScenarios];
        $include = [['name' => 'userRecord', 'fields' => ['id', 'name']]];//支持关联数据获取
        $result = $logic->detail($detailQuery, $printFields, $include);
        return Json::encode($result);
    }

    /**
     * 创建
     * @param string $name name 必填
     * @return json
     */
    public function actionCreate()
    {
        $logic = new SiteLogic();
        $params = $this->post();
        $result = $logic->create($params);
        return Json::encode($result);
    }

    /**
     * 修改
     * @param int $id 数据ID 必填
     * @return json
     */
    public function actionUpdate()
    {
        $logic = new SiteLogic();
        $params = $this->post();
        $id = $params['id'] ?? 0;
        $id = intval($id);
        if ($id == 0) {
            return Json::encode(ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR));
        }
        $where = ['id' => $id];
        $where['user_id'] = $this->getUserId();//***默认加入了user_id过滤
        $result = $logic->update($where, $params);
        return Json::encode($result);
    }

    /**
     * 删除
     * @param int $id 数据ID 必填
     * @return json
     */
    public function actionDelete()
    {
        $logic = new SiteLogic();
        $id = intval($this->post('id', 0));
        if (empty($id)) {
            return Json::encode(ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR));
        }
        $where = ['id' => $id];
        $where['user_id'] = $this->getUserId();//***默认加入了user_id过滤
        $result = $logic->delete($where);
        return Json::encode($result);
    }

    /**
     * 物理删除 默认屏蔽，需要自行打开
     * @param int $id 数据ID 必填
     * @return json
     */
    public function actionPhysiedelete()
    {
        $logic = new SiteLogic();
        $id = intval($this->post('id', 0));
        if (empty($id)) {
            return Json::encode(ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR));
        }
        $where = ['id' => $id];
        $where['user_id'] = $this->getUserId();//***默认加入了user_id过滤
        $result = $logic->physieDelete($where);
        return Json::encode($result);
    }

}
