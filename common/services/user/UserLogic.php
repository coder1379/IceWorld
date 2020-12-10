<?php

namespace common\services\user;

use Yii;
use common\ComBase;
use common\base\BaseLogic;

class UserLogic
{
    /**
     * 自动获取POST内容新增数据
     * @param array $params 前端传递post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     * @throws \Exception
     */
    public function create($params, $currentUserId, $scenario = 'create', $formName = '')
    {
        if (empty($params)) {
            return ComBase::getParamsErrorReturnArray();
        }

        if (empty($currentUserId)) {
            //判断user_id是否为空
            return ComBase::getNoLoginReturnArray();
        }

        $model = new UserApiModel();

        $allAttributeLabels = $model->attributeLabels();
        if (!empty($allAttributeLabels['add_time']) && empty($model->add_time)) {
            $model->add_time = time();
        }

        

        return BaseLogic::baseCreate($model, $params, $scenario, $formName);
    }

    /**
     * 自动获取POST内容进行更新
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     * @throws \Exception
     */
    public function update($params, $currentUserId, $scenario = 'update', $formName = '')
    {
        if (empty($params)) {
            return ComBase::getParamsErrorReturnArray();
        }

        if (empty($currentUserId)) {
            //判断user_id是否为空
            return ComBase::getNoLoginReturnArray();
        }

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }


        $queryModel = UserApiModel::find()->andWhere(['id'=>$id]);
        
        $queryModel->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL]);//自动加入删除过滤
        $model = $queryModel->limit(1)->one();

        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
        }

        $allAttributeLabels = $model->attributeLabels();
        if (!empty($allAttributeLabels['update_time'])) {
            //默认修改更新时间，不需要自行移除
            $model->update_time = time();
        }

        return BaseLogic::baseUpdate($model, $params, $scenario, $formName);
    }

    /**
     * 标记软删除
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     * @throws \Exception
     */
    public function delete($params, $currentUserId, $scenario = 'delete', $formName = '')
    {
        if (empty($params)) {
            return ComBase::getParamsErrorReturnArray();
        }

        if (empty($currentUserId)) {
            //判断user_id是否为空
            return ComBase::getNoLoginReturnArray();
        }

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }

        //设置status=-1 标记删除
        $params['status'] = ComBase::DB_IS_DELETE_VAL;
        $queryModel = UserApiModel::find()->andWhere(['id'=>$id])->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL]);
        
        $model = $queryModel->limit(1)->one();

        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
        }

        return BaseLogic::baseDelete($model, $params, $scenario, $formName);
    }

    /**
     * 物理删除 **默认优先使用标记删除 delete
     * @param array $params 前端post数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param bool $backUp 是否备份删除数据到warning日志 默认false
     * @return array
     * @throws \Exception
     */
    public function physieDelete($params, $currentUserId, $backUp = false)
    {
        if (empty($params)) {
            return ComBase::getParamsErrorReturnArray();
        }

        if (empty($currentUserId)) {
            //判断user_id是否为空
            return ComBase::getNoLoginReturnArray();
        }

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }

        $where = ['id' => $id];
        
        $model = UserApiModel::findOne($where);

        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
        }

        return BaseLogic::basePhysieDelete($model, $backUp);
    }

    /**
     * 获取详情
     * @param array $params 前端传入数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $fieldScenarios 场景默认 detail
     * @return array
     * @throws \Exception
     */
    public function detail($params, $currentUserId, $fieldScenarios = 'detail')
    {
        if (empty($currentUserId)) {
            //判断user_id是否为空
            return ComBase::getNoLoginReturnArray();
        }

        $id = ComBase::getIntVal('id', $params);
        if (empty($id)) {
            return ComBase::getParamsErrorReturnArray();
        }

        $detailModel = new UserApiModel();
        $detailQuery = $detailModel::find();
        $where = ['id' => $id];
        
        $detailQuery->where($where);
        $detailQuery->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL]);//自动加入删除过滤
        //获取输出字段
        $printFields = $detailModel->fieldsScenarios()[$fieldScenarios];

        $include = null;//[ [ 'name'=>'xxxRecord', 'fields'=>'api_detail' ] ];//支持关联数据获取

        return BaseLogic::baseDetail($detailQuery, $printFields, $include);
    }

    /**
     * 获取列表
     * @param array $params 前端传入数据
     * @param int $currentUserId 当前用户id未登录为0，只能通过参数传递不从params中获取
     * @param string $fieldScenarios 场景默认 list
     * @return array
     * @throws \Exception
     */
    public function list($params, $currentUserId, $fieldScenarios = 'list')
    {
        if (empty($currentUserId)) {
            //判断user_id是否为空
            return ComBase::getNoLoginReturnArray();
        }

        //创建查询对象
        $searchModel = new UserApiModel();
        $searchDataQuery = $searchModel::find();
        $where = [];//添加过滤条件，注意默认是无条件的
        
        $searchDataQuery->where($where);
        $searchDataQuery->andWhere(['>','status',ComBase::DB_IS_DELETE_VAL]);//默认添加标记删除标识
        $searchDataQuery->orderBy('id desc');//添加默认排序规则

        //获取输出字段
        $printFields = $searchModel->fieldsScenarios()[$fieldScenarios];

        //获取post内的分页数据并格式化
        $paginationParams = BaseLogic::getPaginationParams($params);

        $include = null; //[ [ 'name'=>'xxxRecord', 'fields'=>'api_detail' ] ];//支持关联数据获取

        return BaseLogic::baseList($searchDataQuery, $printFields, $paginationParams, $include);
    }
}
