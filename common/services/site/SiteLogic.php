<?php
namespace common\services\site;

use Yii;
use common\ComBase;
use common\base\BaseLogic;

class SiteLogic extends BaseLogic
{
    /**
     * 创建
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function create($data = [], $scenario = 'create' , $formName = '' ){
        $model = new SiteApiModel();
        $model->add_time = date('Y-m-d H:i:s',time());
        return $this->baseCreate($model, $data, $scenario, $formName);
    }

    /**
     * 修改
     * @param array $where 更新数据的查询条件 $where 不能直接使用前端推送过来的数组,直接获取赋值传入
     * @param array $data 数据
     * @param string $scenario 场景
     * @param string $formName 表单数组name
     * @return array
     */
    public function update($where, $data = [], $scenario = 'update', $formName = ''){
        if(!empty($where) && !empty($data)){
            $where['is_delete'] = 0;//有is_delete表默认加入软删除过滤
            $model = SiteApiModel::findOne($where);
            return $this->baseUpdate($model, $data, $scenario, $formName);
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
    * 标记删除 默认使用标记删除
    * @param array $where 更新数据的查询条件 $where 不能直接使用前端推送过来的数组,直接获取赋值传入
    * @param array $data 数据
    * @param string $scenario 场景
    * @param string $formName 表单数组name
    * @return array
    */
    public function delete($where, $data = [], $scenario = 'delete', $formName = ''){
        //默认可以传入删除时的data,自动加入is_delete标记
        if(empty($data)){
            $data = ['is_delete' => 1];
        }

        //默认可以传入删除时的data,自动加入is_delete标记
        if(empty($data['is_delete'])){
            $data['is_delete'] = 1;
        }

        if(!empty($where) && !empty($data)){
            $where['is_delete'] = 0;//有is_delete表默认加入软删除过滤
            $model = SiteApiModel::findOne($where);
            return $this->baseDelete($model, $data, $scenario, $formName);
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
    * 物理删除 **默认优先使用标记删除 delete
    * @param array $where 更新数据的查询条件 $where 不能直接使用前端推送过来的数组,直接获取赋值传入
    * @param boll $backUp 是否备份删除数据到warning日志 默认false
    * @return array
    */
    public function physieDelete($where, $backUp = false){
        if(!empty($where)){
            $model = SiteApiModel::findOne($where);
            return $this->basePhysieDelete($model, $backUp);
        }
        return ComBase::getReturnArray([],ComBase::CODE_PARAM_ERROR,ComBase::MESSAGE_PARAM_ERROR);//返回参数错误
    }

    /**
     * 获取详情并根据参数附带关联数据
     * @param object $detailQuery 包含查询条件的query
     * @param string $printFields 输出字段数组,可自定义而不使用场景字段
     * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude
     * @return array
     */
    public function detail($detailQuery, $printFields, $include = []){
        $detailQuery->andWhere(['is_delete'=>0]);//有is_delete表默认加入软删除过滤
        return $this->baseDetail($detailQuery, $printFields, $include);
    }

    /**
    * 通用获取列表 通过include获取关联model的值
    * @param object $searchDataQuery 已经加入了数据过滤条件和排序的model::find();
    * @param array $printFields 输出字段数组,可自定义而不使用场景字段
    * @param array $paginationParams 格式化后的分页数据 包含page和pageSize
    * @param array $include 包含数据，结合Model 的 hasOne,hasMany,getFunction 包含进来关系数据 详见 ComBase::getLogicInclude 注意：由于循环关联数据可能存在多次查询数据库，所以建议当使用此参数时 在控制器中的query 加入 ->with('userRecord') 此类的及时加载关联可以有效提升效率，详情参考yii2:及时加载与延迟加载区别
    * @return array
    */
    public function list($searchDataQuery, $printFields, $paginationParams, $include = []){

        $searchDataQuery->andwhere(['is_delete' => 0 ]);//默认添加标记删除标识
        return $this->baseList($searchDataQuery, $printFields, $paginationParams, $include);
    }
}
