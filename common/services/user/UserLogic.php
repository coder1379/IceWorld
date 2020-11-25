<?php

namespace common\services\user;

use Yii;
use common\ComBase;
use common\base\BaseLogic;

class UserLogic
{
    /**
     * 自动获取POST内容新增,复杂逻辑建议额外添加
     * @param array $params 前端传递post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function create($params, $currentUserId, $scenario = 'create', $formName = '')
    {

        $logic = new BaseLogic();
        $model = new UserApiModel();

        $allAttributeLabels = $model->attributeLabels();
        if (!empty($allAttributeLabels['add_time']) && empty($model->add_time)) {
            $model->add_time = time();
        }

        if (!empty($params)) {
            
            if (empty($currentUserId)) {
                return ComBase::getNoLoginReturnArray();
            }
            $params['user_id'] = $currentUserId;//***默认加入了user_id过滤
        }
        return $logic->baseCreate($model, $params, $scenario, $formName);
    }

    /**
     * 自动获取POST内容功能,复杂逻辑建议新建
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function update($params, $currentUserId, $scenario = 'update', $formName = '')
    {

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }

        $logic = new BaseLogic();
        $where = ['id' => $id];
        
        if (empty($currentUserId)) {
            return ComBase::getNoLoginReturnArray();
        }

        $where['user_id'] = $currentUserId;//***默认加入了user_id过滤
        if (!empty($params)) {
            $where['is_delete'] = 0;//有is_delete表默认加入软删除过滤
            $model = UserApiModel::findOne($where);

            $allAttributeLabels = $model->attributeLabels();
            if (!empty($allAttributeLabels['update_time'])) {
                $model->update_time = time();
            }

            return $logic->baseUpdate($model, $params, $scenario, $formName);
        }
        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 标记删除 优先使用标记删除
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function delete($params, $currentUserId, $scenario = 'delete', $formName = '')
    {

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }

        $logic = new BaseLogic();
        $where = ['id' => $id];
        $where['user_id'] = $currentUserId;//***默认加入了user_id过滤
        //默认可以传入删除时的data,自动加入is_delete标记
        if (empty($params['is_delete'])) {
            $params['is_delete'] = 1;
        }

        if (!empty($params)) {
            $where['is_delete'] = 0;//有is_delete表默认加入软删除过滤
            $model = UserApiModel::findOne($where);
            return $logic->baseDelete($model, $params, $scenario, $formName);
        }
        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 物理删除 **默认优先使用标记删除 delete
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param bool $backUp 是否备份删除数据到warning日志 默认false
     * @return array
     */
    public function physieDelete($params, $currentUserId, $backUp = false)
    {

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }

        $logic = new BaseLogic();
        $where = ['id' => $id];
        $where['user_id'] = $currentUserId;//***默认加入了user_id过滤
        $model = UserApiModel::findOne($where);
        return $logic->basePhysieDelete($model, $backUp);
    }

    /**
     * 基础获取详情,复杂逻辑建议新建查询
     * @param array $params 前端传入数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $fieldScenarios 场景默认 detail
     * @return array
     */
    public function detail($params, $currentUserId, $fieldScenarios = 'detail')
    {

        $logic = new BaseLogic();
        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }
        $where = ['id' => $id];
        $where['user_id'] = $currentUserId;//***默认加入了user_id过滤
        $detailModel = new UserApiModel();
        $detailQuery = $detailModel::find();
        $detailQuery->where($where);
        $detailQuery->andWhere(['is_delete' => 0]);//有is_delete表默认加入软删除过滤
        //获取输出字段
        $printFields = $detailModel->fieldsScenarios()[$fieldScenarios];

        $include = null;//[ [ 'name'=>'xxxRecord', 'fields'=>'api_detail' ] ];//支持关联数据获取

        return $logic->baseDetail($detailQuery, $printFields, $include);
    }

    /**
     * 基础获取列表,复杂逻辑建议新建查询
     * @param array $params 前端传入数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $fieldScenarios 场景默认 list
     * @return array
     */
    public function list($params, $currentUserId, $fieldScenarios = 'list')
    {
        $logic = new BaseLogic();

        //创建查询对象
        $searchModel = new UserApiModel();
        $searchDataQuery = $searchModel::find();
        $where = [];//添加过滤条件，注意默认是无条件的
        $where['user_id'] = $currentUserId;//***默认加入了user_id过滤
        $searchDataQuery->where($where);
        $searchDataQuery->orderBy('id desc');//添加默认排序规则

        $searchDataQuery->andWhere(['is_delete' => 0]);//默认添加标记删除标识
        //获取输出字段
        $printFields = $searchModel->fieldsScenarios()[$fieldScenarios];

        //获取post内的分页数据并格式化
        $paginationParams = $logic->getPaginationParams($params);

        $include = null; //[ [ 'name'=>'xxxRecord', 'fields'=>'api_detail' ] ];//支持关联数据获取

        return $logic->baseList($searchDataQuery, $printFields, $paginationParams, $include);
    }
}
