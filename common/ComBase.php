<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：基础静态公共类
 */

namespace common;

use Yii;

class ComBase
{
    const MESSAGE_RUN_SUCCESS = '操作成功';
    const MESSAGE_CREATE_SUCCESS = '创建成功';
    const MESSAGE_UPDATE_SUCCESS = '修改成功';
    const MESSAGE_DELETE_SUCCESS = '删除成功';
    const MESSAGE_SERVER_ERROR = '服务端处理失败,请重试';
    const MESSAGE_PARAM_FORMAT_ERROR = '参数格式校验失败';
    const MESSAGE_PARAM_ERROR = '参数错误';
    const CODE_RUN_SUCCESS = 200; //运行成功码
    const CODE_PARAM_ERROR = 10001;//参数错误码
    const CODE_PARAM_FORMAT_ERROR = 10111;//字段参数错误码
    const CODE_SERVER_ERROR = 500;//服务端错误码

    /**
     * 返回数据格式数组
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     * @return array 格式数组
     */
    public static function getReturnArray($data = [], $code = 0, $msg = 'success')
    {
        if (empty($data) == true) {
            $data = new \StdClass();//将空数组赋值空对象便于前端兼容处理.
        }
        if (empty($code)) {
            $code = self::CODE_RUN_SUCCESS;
        }
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 返回json格式的字符串
     * @param array $data
     * @param int $code
     * @param string $msg
     * @return false|string
     */
    public static function getReturnJson($data = [], $code = 0, $msg = 'success')
    {
        return json_encode(self::getReturnArray($data, $code, $msg));
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
        $returnData = ['firstKey' => $errors['first']['k']];
        if (Yii::$app->params['returnAllErrors'] == true) {
            $returnData['allErrors'] = $errors['all'];
        }
        return self::getReturnArray($returnData, self::CODE_PARAM_FORMAT_ERROR, $errors['first']['v']);
    }

    /**
     * 封装YII2 设置cookie
     * @param $cookies
     * @param string $name
     * @param string $value
     * @param int $time
     */
    public static function setCookie($cookies, $name = '', $value = '', $time = 0)
    {
        if (empty($name) != true) {
            $cookies->add(new \yii\web\Cookie([
                'name' => $name,
                'value' => $value,
                'expire' => $time,
            ]));
        }
    }

    /**
     * 获取系统上传文件路径
     */
    public static function getUploadRootPath()
    {
        return Yii::getAlias('@static');
    }

    public static function getMd5Key()
    {
        return Yii::$app->params['md5Key'];
    }

    /**
     * 该数据尽量仅适用一层到2层，层数过多会导致递归缓慢，特别是在获取列表时。
     * 根据配置的关系数组自动获取数据,如果此方法无法满足使用则在XxxLogic中自行根据业务获取数据
     * 由于model主要是以实例对象的形式使用，所有当判断返回的为数组时只可能是model list和返回数据为array
     * @param $model 模型实例
     * @param array $include 需要包含的数据关系 例如
     * $include = [
            [
                'name'=>'userRecord', //对面model里 get+name的function
                'fields'=>['id','name','mobile'], //Model->getAttributes($fields);,在获取非model数据时fields可以不传
                'include'=>[ //是否递归子包含
                ]
            ],
            [
                'name'=>'inviterUserRecordList',
                'fields'=>['id','name','mobile'],
                'include'=>[
                ]
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
                $thisInclude = $obj['include']??null;
                $thisModel = $model->$recordName;
                $modelList = null;
                $modelListFlag = 0;
                if(is_array($thisModel) && is_object(current($thisModel))) {
                    $modelList = $thisModel;
                    $modelListFlag = 1;
                }else{
                    $modelList[] = $thisModel;
                }

                $dataArray = [];
                if(!empty($modelList)){
                    foreach ($modelList as $nextModel){
                        $thisArray = null;
                        if(is_object($nextModel)){
                            $thisArray = $nextModel->getAttributes($fields);
                        }else{
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
                        if($modelListFlag == 1){
                            $dataArray[] = $thisArray;
                        }else{
                            $dataArray = $thisArray;
                        }
                    }
                }
                $returnList[] = ['name' => $recordName, 'data' => $dataArray];
            }
        }
        return $returnList;
    }

}