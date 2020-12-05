<?php

namespace common\base;

use Yii;
use common\ComBase;
use yii\data\Pagination;

/**
 * logic 基础类,封装了基本的curd,通过model处理，与业务代码完全解耦。
 * 复杂业务在具体logic中直接实现
 * Class BaseLogic
 * @package common\base
 */
class BaseLogic
{

    /*
     * 根据配置的关系数组自动获取数据,如果此方法无法满足使用则在XxxLogic中自行根据业务获取数据
     * 由于model主要是以实例对象的形式使用，所有当判断返回的为数组时只可能是model list和返回数据为array
     * @param $model 模型实例
     * @param array $include 需要包含的数据关系 例如
      $include = [
                    [
                        'name'=>'userRecord',
                        //对应model里 get+name的function

                        'fields'=>'list|detail等', //也可使用 'fields'=>['id','name','mobile']，直接指定要获取哪些字段但此方式不便于多地方调用时的维护
                        //list,detail为model里面fieldsScenarios的场景可显示字段数组的key，推荐使用此方式便于后续增减字段 在Model->getAttributes($fields)时调用
                        //在获取非model数据时fields可以不传,同时可以string，fieldsScenarios的key值，将自动获取对应数组,这里需要注意apiModel与model之间的继承关系，可能hasOne里的父类model没有fieldsScenarios 需要将hasOne或many里面的model指向XxxApiModel

                        'include'=>[ //是否递归子包含],//递归包含的子项，参数同上，在多表递归关联的时候推荐使用
                    ],

                    [
                        'name'=>'inviterUserRecordList','fields'=>['id','name','mobile'],'include'=>[ ],//包含的第二个数据，可以同时引入多个规则同上
                    ],
                ];
     * @return array
     * @throws \Exception
     */
    public static function getLogicInclude($model, $include = [])
    {
        if (!empty($include)) {
            $returnList = [];
            foreach ($include as $obj) {
                $recordName = $obj['name'];
                $fields = $obj['fields']; //在获取非model数据时fields可以不传
                $thisInclude = $obj['include'] ?? null;
                $thisModel = $model->$recordName;
                $modelList = null;
                $modelListFlag = 0;
                if (is_array($thisModel) && is_object(current($thisModel))) {
                    $modelList = $thisModel;
                    $modelListFlag = 1;
                } else {
                    $modelList[] = $thisModel;
                }

                $dataArray = [];
                if (!empty($modelList)) {
                    foreach ($modelList as $nextModel) {
                        $thisArray = null;
                        if (is_object($nextModel)) {
                            if (is_array($fields)) {
                                $thisArray = $nextModel->getAttributes($fields);
                            } else if (is_string($fields)) {
                                if (empty($nextModel->fieldsScenarios()) || empty($nextModel->fieldsScenarios()[$fields])) {
                                    throw new \Exception('fieldsScenarios is null or fieldsScenarios[' . $fields . '] is null');
                                } else {
                                    $printFields = $nextModel->fieldsScenarios()[$fields];
                                    $thisArray = $nextModel->getAttributes($printFields);
                                }

                            }

                        } else {
                            $thisArray = $nextModel;
                        }

                        if (!empty($thisInclude) && !empty($nextModel)) {
                            $includeArray = self::getLogicInclude($nextModel, $thisInclude);
                            if (!empty($includeArray)) {
                                foreach ($includeArray as $inc) {
                                    $thisArray[$inc['name']] = $inc['data'];
                                }
                            }
                        }
                        if ($modelListFlag == 1) {
                            $dataArray[] = $thisArray;
                        } else {
                            $dataArray = $thisArray;
                        }
                    }
                }
                $returnList[] = ['name' => $recordName, 'data' => $dataArray];
            }
        }
        return $returnList;
    }

    /**
     * 获取模型的错误数组模式，并将第一个错误格式化到返回错误中
     * @param $errors
     * @return array
     */
    public static function getModelErrorsToArray($errors)
    {
        $returnErrors = ['all' => [], 'first' => ['k' => 0, 'v' => '']];
        if (!empty($errors)) {
            $firstFlag = true;
            foreach ($errors as $k => $v) {
                $returnErrors['all'][] = [$k => $v[0]];
                if ($firstFlag == true) {
                    $returnErrors['first']['k'] = $k;
                    $returnErrors['first']['v'] = $v[0];
                    $firstFlag = false;
                }
            }
        }
        return $returnErrors;
    }

    /**
     *  将 getModelErrorsToArray 格式化后的数据再次格式化为直接能返回前端的数据,根据params设置的returnAllErrors 控制是否输出全部错误
     * @param $errors
     * @return array
     */
    public static function getFormatErrorsArray($errors)
    {
        $returnData = null;//['firstKey' => $errors['first']['k']];//仅返回错误描述，不返回错误字段需要自行开启
        if (Yii::$app->params['returnAllErrors'] == true) {
            $returnData['allErrors'] = $errors['all'];
        }
        return ComBase::getReturnArray($returnData, ComBase::CODE_PARAM_FORMAT_ERROR, $errors['first']['v']);
    }

    /**
     * 创建
     * @param object $model 调用方new的实体对象
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组前缀
     * @return array
     * @throws \Exception
     */
    public static function baseCreate($model, $data, $scenario, $formName = '')
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
                    return ComBase::getServerBusyReturnArray();
                }
            } else {
                //获取并返回错误内容数组
                return self::getFormatErrorsArray(self::getModelErrorsToArray($model->getErrors()));
            }
        }

        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 更新
     * @param object $model 调用方需要修改数据实体并已经执行查询的返回model
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组前缀
     * @return array
     * @throws \Exception
     */
    public static function baseUpdate($model, $data, $scenario, $formName = '')
    {
        if (empty($scenario)) {
            throw new \Exception('scenario cant null');
        }

        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
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
                    return ComBase::getServerBusyReturnArray();
                }
            } else {
                //获取并返回错误内容数组
                return self::getFormatErrorsArray(self::getModelErrorsToArray($model->getErrors()));
            }
        }

        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 标记删除 默认使用标记删除
     * @param object $model 调用方需要修改数据实体并已经执行查询的返回model
     * @param array $data sql数据
     * @param string $scenario 场景
     * @param string $formName 表单名称
     * @return array
     * @throws \Exception
     */
    public static function baseDelete($model, $data, $scenario = 'delete', $formName = '')
    {
        if (empty($scenario)) {
            throw new \Exception('scenario cant null');
        }

        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
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
                    return ComBase::getServerBusyReturnArray();
                }
            } else {
                //获取并返回错误内容数组
                return self::getFormatErrorsArray(self::getModelErrorsToArray($model->getErrors()));
            }
        }

        return ComBase::getParamsErrorReturnArray();
    }

    /**
     * 物理删除,可以设置是否同时记录一份删除的内容到warning中,**默认优先使用标记删除 delete
     * @param object $model 调用方需要删除数据实体并已经执行查询的返回model
     * @param bool $backUp 是否备份删除数据到warning 默认false
     * @return array
     * @throws \Exception
     */
    public static function basePhysieDelete($model, $backUp = false)
    {
        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
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
            return ComBase::getServerBusyReturnArray();
        }
    }

    /**
     * 获取详情并根据参数附带关联数据
     * @param object $detailQuery 获取model的query对象，已经包含了所有的查询条件
     * @param array $printFields 输出字段数组对应 apiModel里面的fieldsScenarios对应字段,可自定义每一个字段而不使用场景字段
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude 使用延迟加载有效提高效率
     * @return array | model
     * @throws \Exception
     */
    public static function baseDetail($detailQuery, $printFields, $include = [])
    {
        if (empty($detailQuery)) {
            throw new \Exception('detailQuery cant null');
        }

        if (empty($printFields)) {
            throw new \Exception('printFields cant null');
        }
        $model = $detailQuery->limit(1)->one(); //获取一条数据
        if (empty($model)) {
            return ComBase::getNoFindReturnArray();
        }

        //直接获取数组数据并返回，可以将关联数据在此加入或者将预定义状态等返回
        $modelArray = $model->getAttributes($printFields);
        if (!empty($include)) {
            $includeList = self::getLogicInclude($model, $include);
            if (!empty($includeList)) {
                foreach ($includeList as $inc) {
                    $modelArray[$inc['name']] = $inc['data'];
                }
            }
        }
        return ComBase::getReturnArray($modelArray);
    }

    /**
     * 获取列表 并根据参数附带关联数据
     * @param object $searchDataQuery 获取model list的query对象，已经包含了除分页外所有的查询条件及排序  参考model::find();
     * @param array $printFields 输出字段数组对应 apiModel里面的fieldsScenarios对应字段,可自定义每一个字段而不使用场景字段
     * @param array $paginationParams 格式化后的分页参数 通过调用 baseLogic->getPaginationParams($params) 获取对应格式化后的值
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude  在query 加入 ->with('userRecord') 可以有效提升效率，详情参考yii2:及时加载与延迟加载区别
     * @return array
     * @throws \Exception
     */
    public static function baseList($searchDataQuery, $printFields, $paginationParams, $include = [])
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
                    $includeList = self::getLogicInclude($model, $include);
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

        return ComBase::getReturnArray($listArray);
    }

    /**
     * 解析post内的分页参数并返回统一结果值判定
     * @param $params 前端输入参数
     * @param int $pageSize 每页显示数量
     * @param string $pageParamName 默认key名
     * @param string $pageSizeParamName 默认key名
     * @return array
     */
    public static function getPaginationParams($params, $pageSize = 10, $pageParamName = 'page', $pageSizeParamName = 'page_size')
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
}
