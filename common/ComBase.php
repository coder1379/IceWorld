<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：基础静态公共类
 */

namespace common;

use Yii;
use yii\helpers\Json;

class ComBase
{

    const MESSAGE_CREATE_SUCCESS = '创建成功';
    const MESSAGE_UPDATE_SUCCESS = '修改成功';
    const MESSAGE_DELETE_SUCCESS = '删除成功';


    //执行成功
    const CODE_RUN_SUCCESS = 200;
    const MESSAGE_RUN_SUCCESS = '操作成功';

    //请求无效
    const CODE_REQUEST_INVALID = 400;
    const MESSAGE_REQUEST_INVALID = '请求无效';

    //尚未登录
    const CODE_NO_LOGIN_ERROR = 401;
    const MESSAGE_NO_LOGIN_ERROR = '尚未登录';

    //没有权限
    const CODE_NO_AUTH_ERROR = 403;
    const MESSAGE_NO_AUTH_ERROR = '没有权限';

    //参数错误码
    const CODE_PARAM_ERROR = 431;
    const MESSAGE_PARAM_ERROR = '参数错误';

    //数据未找到
    const CODE_NO_FIND_ERROR = 440;
    const MESSAGE_NO_FIND_ERROR = '数据未找到';

    //参数格式校验失败
    const CODE_PARAM_FORMAT_ERROR = 445;
    const MESSAGE_PARAM_FORMAT_ERROR = '参数格式校验失败';

    //服务端错误码
    const CODE_SERVER_ERROR = 500;
    const MESSAGE_SERVER_ERROR = '服务端处理失败,请重试';

    //服务器繁忙
    const CODE_SERVER_BUSY = 503;
    const MESSAGE_SERVER_BUSY = '服务端繁忙,请稍后再试';

    /**
     * 返回数据格式数组
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，500服务不可用，10000自定义错误代码开始值
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
        return Json::encode(self::getReturnArray($data, $code, $msg));
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
        return self::getReturnArray($returnData, self::CODE_PARAM_FORMAT_ERROR, $errors['first']['k'] . ':' . $errors['first']['v']);
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
     * [
     * 'name'=>'userRecord', //对面model里 get+name的function
     * 'fields'=>['id','name','mobile']|'fields'=>'list|detail等', //Model->getAttributes($fields);,在获取非model数据时fields可以不传,同时可以string，fieldsScenarios的key值，将自动获取对应数组,这里需要注意apiModel与model之间的继承关系，可能hasOne里的父类model没有fieldsScenarios 需要将hasOne或many里面的model指向XxxApiModel
     * 'include'=>[ //是否递归子包含
     * ]
     * ],
     * [
     * 'name'=>'inviterUserRecordList',
     * 'fields'=>['id','name','mobile'],
     * 'include'=>[
     * ]
     * ],
     * ];
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
     * 获取预设数组内的值
     * @param array $params
     * @param array $keyList
     * @return array
     */
    public static function getReserveArray($params = [], $keyList = [])
    {
        $returnList = [];
        if (!empty($params) && !empty($keyList)) {
            foreach ($keyList as $name) {
                if (isset($params[$name])) {
                    $returnList[$name] = $params[$name];
                }
            }
        }

        return $returnList;
    }

    /**
     * 获取int返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public static function getIntVal($name, $params, $defaultValue = 0)
    {
        $temp = $params[$name] ?? null;
        if (isset($temp)) {
            $temp = intval($temp);
        } else {
            $temp = $defaultValue;
        }
        return $temp;
    }

    /**
     * 获取string返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public static function getStrVal($name, $params, $defaultValue = '')
    {
        $temp = $params[$name] ?? null;
        if (isset($temp)) {
            $temp = strval($temp);
        } else {
            $temp = $defaultValue;
        }
        return $temp;
    }

    /**
     * 获取float返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return int|mixed|null
     */
    public static function getFloatVal($name, $params, $defaultValue = 0)
    {
        $temp = $params[$name] ?? null;
        if (isset($temp)) {
            $temp = floatval($temp);
        } else {
            $temp = $defaultValue;
        }
        return $temp;
    }

    /**
     * 获取json返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return array|mixed|null
     */
    public static function getJsonVal($name, $params, $defaultValue = [])
    {
        $tempArr = $params[$name] ?? null;
        $arr = null;
        if (is_array($tempArr)) {
            $arr = $tempArr;
        } else {
            $arr = json_decode(strval($tempArr), true);
            if (!is_array($arr)) {
                $arr = $defaultValue;
            }
        }
        return $arr;
    }

}