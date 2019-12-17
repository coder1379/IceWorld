<?php

namespace common\logics;

use Yii;
use common\ComBase;
use yii\data\Pagination;

/**
 * logic 基础类,封装了基本的curd,如果无法满足可以在继承的子类中自行实现
 * Class BaseLogic
 * @package common\logics
 */
class BaseLogic
{
    /**
     * 创建
     * @param object $model 调用方new的实体对象
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组前缀
     * @return array
     */
    public function baseCreate($model, $data, $scenario, $formName = '')
    {
        if (empty($model)) {
            throw new \Exception('model cant null');
        }

        if (empty($scenario)) {
            throw new \Exception('scenario cant null');
        }

        if (!empty($data)) {
            $model->scenario = $scenario;
            if ($model->load($data, $formName) == true && $model->validate() == true) {
                $result = $model->save(false);
                if ($result) {
                    //创建成功
                    return ComBase::getReturnArray(['id' => Yii::$app->db->getLastInsertID()], ComBase::CODE_RUN_SUCCESS, ComBase::MESSAGE_CREATE_SUCCESS);
                } else {
                    //创建失败
                    return ComBase::getReturnArray([], ComBase::CODE_SERVER_ERROR, ComBase::MESSAGE_SERVER_ERROR);
                }
            } else {
                //获取并返回错误内容数组
                return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
            }
        }

        return ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 更新
     * @param object $model 调用方需要修改数据实体并已经执行查询的返回model
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组前缀
     * @return array
     */
    public function baseUpdate($model, $data, $scenario, $formName = '')
    {
        if (empty($scenario)) {
            throw new \Exception('scenario cant null');
        }

        if (empty($model)) {
            return ComBase::getReturnArray([], ComBase::CODE_NO_FIND_ERROR, ComBase::MESSAGE_NO_FIND_ERROR);//返回 没有找到指定数据
        }

        if (!empty($data)) {
            $model->scenario = $scenario;
            if ($model->load($data, $formName) == true && $model->validate() == true) {
                $result = $model->save(false);
                if ($result) {
                    //修改成功
                    return ComBase::getReturnArray([], ComBase::CODE_RUN_SUCCESS, ComBase::MESSAGE_UPDATE_SUCCESS);
                } else {
                    //修改失败
                    return ComBase::getReturnArray([], ComBase::CODE_SERVER_ERROR, ComBase::MESSAGE_SERVER_ERROR);
                }
            } else {
                //获取并返回错误内容数组
                return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
            }
        }

        return ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 标记删除
     * @param object $model 调用方需要修改数据实体并已经执行查询的返回model
     * @param array $data sql数据
     * @param string $scenario 场景
     * @param string $formName 表单名称
     * @return array
     */
    public function baseDelete($model, $data, $scenario = 'delete', $formName = '')
    {
        if (empty($scenario)) {
            throw new \Exception('scenario cant null');
        }

        if (empty($model)) {
            return ComBase::getReturnArray([], ComBase::CODE_NO_FIND_ERROR, ComBase::MESSAGE_NO_FIND_ERROR);//返回 没有找到指定数据
        }

        if (!empty($data)) {
            $model->scenario = $scenario;
            if ($model->load($data, $formName) == true && $model->validate() == true) {
                $result = $model->save(false);
                if ($result) {
                    //删除成功
                    return ComBase::getReturnArray([], ComBase::CODE_RUN_SUCCESS, ComBase::MESSAGE_DELETE_SUCCESS);
                } else {
                    //删除失败
                    return ComBase::getReturnArray([], ComBase::CODE_SERVER_ERROR, ComBase::MESSAGE_SERVER_ERROR);
                }
            } else {
                //获取并返回错误内容数组
                return ComBase::getFormatErrorsArray(ComBase::getModelErrorsToArray($model->getErrors()));
            }
        }

        return ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 物理删除,可以设置是否同时记录一份删除的内容到warning中
     * @param object $model 调用方需要删除数据实体并已经执行查询的返回model
     * @param bool $backup 是否备份删除数据到warning 默认false
     * @return array
     */
    public function basePhysieDelete($model, $backUp = false)
    {
        if (empty($model)) {
            return ComBase::getReturnArray([], ComBase::CODE_NO_FIND_ERROR, ComBase::MESSAGE_NO_FIND_ERROR);//返回 没有找到指定数据
        }

        if ($backUp == true) {
            $deleteData = $model->getOldAttributes();
            Yii::warning($deleteData, 'delete_backup|' . $model::className());
        }

        $result = $model->delete();
        if ($result) {
            //删除成功
            return ComBase::getReturnArray([], ComBase::CODE_RUN_SUCCESS, ComBase::MESSAGE_DELETE_SUCCESS);
        } else {
            //删除失败
            return ComBase::getReturnArray([], ComBase::CODE_SERVER_ERROR, ComBase::MESSAGE_SERVER_ERROR);
        }
    }

    /**
     * 获取详情并根据参数附带关联数据
     * @param object $detailQuery 获取model的query对象，已经包含了所有的查询条件
     * @param array $printFields 输出字段数组
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude
     * @return array | model
     */
    public function baseDetail($detailQuery, $printFields, $include = [])
    {
        if (empty($detailQuery)) {
            throw new \Exception('detailQuery cant null');
        }

        if (empty($printFields)) {
            throw new \Exception('printFields cant null');
        }
        $model = $detailQuery->one(); //获取一条数据
        if (empty($model)) {
            return ComBase::getReturnArray([], ComBase::CODE_NO_FIND_ERROR, ComBase::MESSAGE_NO_FIND_ERROR);//返回 没有找到指定数据
        }

        //直接获取数组数据并返回，可以将关联数据在此加入或者将预定义状态等返回
        $modelArray = $model->getAttributes($printFields);
        if (!empty($include)) {
            $includeList = ComBase::getLogicInclude($model, $include);
            if (!empty($includeList)) {
                foreach ($includeList as $inc) {
                    $modelArray[$inc['name']] = $inc['data'];
                }
            }
        }
        return ComBase::getReturnArray($modelArray, ComBase::CODE_RUN_SUCCESS);
    }

    /**
     * 获取列表 并根据参数附带关联数据
     * @param object $searchDataQuery 获取model list的query对象，已经包含了除分页外所有的查询条件及排序
     * @param array $printFields 输出字段数组
     * @param array $paginationParams 格式化后的分页参数 通过调用 baseLogic->getPaginationParams 获取
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude
     * @param string $formName 表单名称
     * @return array
     */
    public function baseList($searchDataQuery, $printFields, $paginationParams, $include = [])
    {
        if (empty($searchDataQuery)) {
            throw new \Exception('searchDataQuery cant null');
        }

        if (empty($printFields)) {
            throw new \Exception('printFields cant null');
        }

        if (empty($paginationParams)) {
            throw new \Exception('paginationParams cant null');
        }

        $countQuery = clone $searchDataQuery;
        $count = $countQuery->count();
        $pagination = new Pagination([
            'totalCount' => $count,
        ]);
        $pagination->setPage($paginationParams['page']);//设置获取数据页码
        $pagination->setPageSize($paginationParams['pageSize']);//设置每页行数
        $searchDataQuery->offset($pagination->offset)->limit($pagination->limit);

        //获取数据列表
        $listObjects = $searchDataQuery->all(); //获取model list
        $list = [];

        if (!empty($listObjects)) {
            //循环获取对象的数组值并根据需要将关联数据加入到返回数组中
            foreach ($listObjects as $model) {
                $modelArray = $model->getAttributes($printFields);
                if (!empty($include)) {
                    $includeList = ComBase::getLogicInclude($model, $include);
                    if (!empty($includeList)) {
                        foreach ($includeList as $inc) {
                            $modelArray[$inc['name']] = $inc['data'];
                        }
                    }
                }
                $list[] = $modelArray;
            }
        }

        //填充返回分页数据
        $pagination = [
            'page_size' => $pagination->getPageSize(),
            'total_page' => $pagination->getPageCount(),
            'page' => $pagination->getPage() + 1,
            'total_count' => $pagination->totalCount,
        ];
        $listArray = [
            'list' => $list,
            'pagination' => $pagination,
        ];

        return ComBase::getReturnArray($listArray, ComBase::CODE_RUN_SUCCESS);
    }

    /**
     * 解析post内的分页参数并返回统一结果值判定
     * @param $params 前端输入参数
     * @param int $pageSize 每页显示数量
     * @param string $pageParamName 默认key名
     * @param string $pageSizeParamName 默认key名
     * @return array
     */
    public function getPaginationParams($params, $pageSize = 10, $pageParamName = 'page', $pageSizeParamName = 'page_size')
    {
        $pagination = [];
        $page = 0;
        if (!empty($params[$pageSizeParamName]) && intval($params[$pageSizeParamName]) > 0) {
            $pageSizeParam = intval($params[$pageSizeParamName]);
            if ($pageSizeParam <= 100) { //控制返回行数不超过100个，如有特殊要求自行覆盖
                $pageSize = $pageSizeParam;
            }
        }
        $pagination['pageSize'] = $pageSize;
        if (!empty($params[$pageParamName])) {
            $pageParam = intval($params[$pageParamName]);
            if ($pageParam > 0) {
                $page = $pageParam - 1;
            }
        }
        $pagination['page'] = $page;
        return $pagination;
    }

    /**
     * 统一获取参数错误返回,可在子类中自行覆盖
     * @return array
     */
    public function getParamsErrorReturnArray(){
        return ComBase::getReturnArray([], ComBase::CODE_PARAM_ERROR, ComBase::MESSAGE_PARAM_ERROR);
    }

    /**
     * 统一获取服务器异常返回,可在子类中自行覆盖
     * @return array
     */
    public function getServerErrorReturnArray(){
        return ComBase::getReturnArray([], ComBase::CODE_SERVER_ERROR, ComBase::MESSAGE_SERVER_ERROR);
    }

}
