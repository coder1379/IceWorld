<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：基础静态公共类 封装快速开发函数 尽可能减少这个类的大小 并不引用过多其他包
 */

namespace common;

class ComBase
{

    const MESSAGE_CREATE_SUCCESS = '创建成功';
    const MESSAGE_UPDATE_SUCCESS = '修改成功';
    const MESSAGE_DELETE_SUCCESS = '删除成功';

    const STATUS_COMMON_DELETE = -1;//状态通用的删除值,不同自行定义
    const STATUS_COMMON_YES = 1;//状态通用的YES状态值,不同自行定义

    //成功
    const CODE_RUN_SUCCESS = 200; //执行成功code = 200,大于200的返回值都视为发生错误,小于200的数值也为成功可做特殊使用
    const MESSAGE_RUN_SUCCESS = 'success';

    //请求无效
    const CODE_REQUEST_INVALID = 400;
    const MESSAGE_REQUEST_INVALID = '请求无效';

    //尚未登录
    const CODE_NO_LOGIN_ERROR = 401;
    const MESSAGE_NO_LOGIN_ERROR = '尚未登录';

    //登录过期需要续签
    const CODE_LOGIN_EXPIRE = 402;
    const MESSAGE_LOGIN_EXPIRE = '登录过期';

    //没有权限
    const CODE_NO_AUTH_ERROR = 403;
    const MESSAGE_NO_AUTH_ERROR = '没有权限';

    //服务未找到
    const CODE_SERVER_NO_FIND_ERROR = 404;
    const MESSAGE_SERVER_NO_FIND_ERROR = '服务未找到，请稍后重试';

    //获取游客token后重试,当开启了游客模式后游客jwt校验失败或权限验证允许游客访问action校验失败后返回便于前端获取游客token后重试
    const CODE_GET_VISITOR_TOKEN_RETRY = 422;
    const MESSAGE_GET_VISITOR_TOKEN_RETRY = '请重试';

    //参数错误码
    const CODE_PARAM_ERROR = 431;
    const MESSAGE_PARAM_ERROR = '参数错误';

    //数据未找到
    const CODE_NO_FIND_ERROR = 440;
    const MESSAGE_NO_FIND_ERROR = '数据未找到';

    //参数格式校验失败
    const CODE_PARAM_FORMAT_ERROR = 445;
    const MESSAGE_PARAM_FORMAT_ERROR = '参数格式错误';

    //服务端错误码
    const CODE_SERVER_ERROR = 500;
    const MESSAGE_SERVER_ERROR = '服务处理失败，请重试';

    //服务器繁忙
    const CODE_SERVER_BUSY = 503;
    const MESSAGE_SERVER_BUSY = '服务繁忙，请稍后再试';

    const MESSAGE_PARAMS_LOST = '参数丢失，请关闭后重试'; // 当游客或其他参数丢失时一般使用此描述让用户重新打开获取新的游客token等

    /**
     * 返回数据格式数组
     * 注意：返回值可以添加更多字段与修改msg字段，但code与data字段结构不能修改，除前端外其他服务端也使用了该规则，可额外在封装一层自行转换
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时,422 获取游客身份后重试，429请求次数过多，500服务不可用，503服务器繁忙，1000自定义错误代码开始值
     * @param string $msg 消息
     * @return array 格式数组
     */
    public static function getReturnArray($data = null, $code = null, $msg = null)
    {
        if (empty($data)) {
            $data = new \StdClass();//将空数组赋值空对象便于前端兼容处理.
        }
        if ($code === null) {
            $code = ComBase::CODE_RUN_SUCCESS;
        }

        if ($msg === null) {
            $msg = ComBase::MESSAGE_RUN_SUCCESS;
        }
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 获取预设数组内的值,api 生成 search中有使用 例如 $params=['a'=>1,'b'=>2,'c'=>3],$keyList = ['a','c'],返回:['a'=>1,'c'=>3]
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
     * 获取json_decode array返回值
     * @param string $name 参数名称
     * @param array $params 参数合集
     * @param int $defaultValue 未设置默认值
     * @return array|mixed|null
     */
    public static function getJsonDecodeArrayVal($name, $params, $defaultValue = [])
    {
        $tempArr = $params[$name] ?? null;
        $arr = null;
        if (is_array($tempArr)) {
            $arr = $tempArr;
        } else {
            try {
                $arr = json_decode(strval($tempArr), true);
            } catch (\Exception $exc) {
                //json格式错误
            }
            if (!is_array($arr)) {
                $arr = $defaultValue;
            }
        }
        return $arr;
    }

    /**
     * 获取int的数字数组列表，一般为id列表使用,避免非int注入并过滤非0数据
     * @param $arr
     * @return array
     */
    public static function getIntIdsArray($arr)
    {
        $intArr = [];
        if (!empty($arr)) {
            foreach ($arr as $val) {
                $tempV = intval($val);
                if ($tempV > 0) {
                    $intArr[] = $tempV;
                }
            }
        }
        return $intArr;
    }

    /**
     * 获取获取一维数组防止异常数据例如前端一维ids数据库保存被串改为2维度
     * @param $array
     */
    public static function getOneArrayByString($array){
        $retArr = [];
        if(!empty($array)){
            foreach ($array as $a) {
                if(is_string($a)){
                    $retArr[] = $a;
                }
            }
        }
        return $retArr;
    }

    /**
     * 参数错误指定格式返回快捷调用
     * @param null $msg 自定义错误描述为null使用默认
     * @param array $data 自定义数据 为null使用默认空
     * @return array
     */
    public static function getParamsErrorReturnArray($msg = null, $data = null)
    {
        if (empty($msg)) {
            $msg = self::MESSAGE_PARAM_ERROR;
        }
        return self::getReturnArray($data, self::CODE_PARAM_ERROR, $msg);
    }

    /**
     * 参数格式错误指定格式返回快捷调用
     * @param null $msg 自定义错误描述为null使用默认
     * @param array $data 自定义数据 为null使用默认空
     * @return array
     */
    public static function getParamsFormatErrorReturnArray($msg = null, $data = null)
    {
        if (empty($msg)) {
            $msg = self::MESSAGE_PARAM_FORMAT_ERROR;
        }
        return self::getReturnArray($data, self::CODE_PARAM_FORMAT_ERROR, $msg);
    }


    /**
     * 服务器繁忙指定格式返回快捷调用
     * @param null $msg 自定义错误描述为null使用默认
     * @param array $data 自定义数据 为null使用默认空
     * @return array
     */
    public static function getServerBusyReturnArray($msg = null, $data = null)
    {
        if (empty($msg)) {
            $msg = self::MESSAGE_SERVER_BUSY;
        }
        return self::getReturnArray($data, self::CODE_SERVER_BUSY, $msg);
    }

    /**
     * 数据未找到指定格式返回快捷调用
     * @param null $msg 自定义错误描述为null使用默认
     * @param array $data 自定义数据 为null使用默认空
     * @return array
     */
    public static function getNoFindReturnArray($msg = null, $data = null)
    {
        if (empty($msg)) {
            $msg = self::MESSAGE_NO_FIND_ERROR;
        }
        return self::getReturnArray($data, self::CODE_NO_FIND_ERROR, $msg);
    }

    /**
     * 没有权限指定格式返回快捷调用
     * @param null $msg 自定义错误描述为null使用默认
     * @param array $data 自定义数据 为null使用默认空
     * @return array
     */
    public static function getNoAuthReturnArray($msg = null, $data = null)
    {
        if (empty($msg)) {
            $msg = self::MESSAGE_NO_AUTH_ERROR;
        }
        return self::getReturnArray($data, self::CODE_NO_AUTH_ERROR, $msg);
    }

    /**
     * 尚未登录指定格式返回快捷调用
     * @param null $msg 自定义错误描述为null使用默认
     * @param array $data 自定义数据 为null使用默认空
     * @return array
     */
    public static function getNoLoginReturnArray($msg = null, $data = null)
    {
        if (empty($msg)) {
            $msg = self::MESSAGE_NO_LOGIN_ERROR;
        }
        return self::getReturnArray($data, self::CODE_NO_LOGIN_ERROR, $msg);
    }

}