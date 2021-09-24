<?php


namespace common\lib;


use yii\data\ArrayDataProvider;

/**
 * 重写数组数据集方法，覆盖数据获取使其能够支持自定义分页
 * Class ArrayDataProviderPagination
 * @package common\lib
 */
class ArrayDataProviderPagination extends ArrayDataProvider
{

    protected function prepareModels()
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
            $models = $this->sortModels($models, $sort);
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();

            if ($pagination->getPageSize() > 0) {
                // 移除对当前数组的分页处理
                // $models = array_slice($models, $pagination->getOffset(), $pagination->getLimit(), true);
            }
        }

        return $models;
    }

}