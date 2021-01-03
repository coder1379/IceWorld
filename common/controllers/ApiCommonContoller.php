<?php

namespace common\controllers;

use common\ComBase;
use common\lib\StringHandle;
use common\services\account\AccountCommon;
use common\services\user\UserCommon;
use Yii;
use yii\helpers\Json;


/**
 * 接口无需用户登录及权限控制器基类,根据配置判断是否进行游客验证
 */
class ApiCommonContoller extends BaseContoller
{
    public $enableCsrfValidation = false;
    public $excludeAccessLog = null; //访问日志不记录

    public $excludeVisitorVer = ['visitortoken']; //排除游客验证,由于是基础类大部分可以游客访问所以采用排除,游客校验会受到全局配置影响,首先排除获取游客token自身

    public $userId = 0;//控制器userid
    public $visitorUserId = 0;//控制器游客id

    public $userType = 0;//控制器usertype 主要判断是否为正式用户游客等 默认为0表示游客用户在游客表中查询
    public $shortToken = null; //jwt.后的短token值 用于进行数据库比较

    public function beforeAction($action)
    {
        $verifyCode = $this->setUser();
        $actionId = $action->id;
        $this->visitorVerification($verifyCode, $actionId);//游客权限验证,校验会受到全局是否开启游客校验配置影响
        return true;
    }

    public function afterAction($action, $result)
    {
        $runResult = parent::afterAction($action, $result);
        //根据配置判断是否需要记录访问日志写入accesslog start
        try {
            $aciontId = $action->id;
            if (empty($this->excludeAccessLog) || !in_array($aciontId, $this->excludeAccessLog, true)) {
                $saveAccessLog = Yii::$app->params['save_access_log'] ?? false;
                if ($saveAccessLog) {
                    $route = strtolower($this->module->id . '/' . $this->id . '/' . $this->action->id);
                    $startTime = intval(YII_BEGIN_TIME * 10000);
                    $endTime = intval(microtime(true) * 10000);
                    $useTime = ceil(($endTime - $startTime) / 10);
                    $allParams = [];
                    $requestList = $this->getRequestAll();
                    if (!empty($requestList)) {
                        foreach ($requestList as $key => $item) {
                            if (is_array($item)) {
                                $tepStr = json_encode($item);
                                if (mb_strlen($tepStr) > 100) {
                                    $tepStr = mb_substr($tepStr, 0, 100) . '...';
                                }
                                $allParams[$key] = $tepStr;

                            } else {
                                $item = strval($item);
                                if (stripos($key, 'token') !== false || stripos($key, 'password') !== false || stripos($key, 'pwd') !== false || stripos($key, 'mobile') !== false || stripos($key, 'phone') !== false || stripos($key, 'auth') !== false) {
                                    $item = StringHandle::getStarsString($item);
                                }

                                if (mb_strlen($item) < 50) {
                                    $allParams[$key] = $item;
                                } else {
                                    $allParams[$key] = mb_substr($item, 0, 50) . '...';
                                }

                            }
                        }
                    }
                    if (!empty($_FILES)) {
                        $allParams = array_merge($allParams, $_FILES);
                    }
                    $allParams = json_encode($allParams);
                    $userTempId = $this->userId;
                    if ($this->userType === UserCommon::TYPE_DEVICE_VISITOR) {
                        $userTempId = $this->visitorUserId;
                    }

                    $saveData = [
                        ':user_id' => $userTempId,
                        ':user_type' => $this->userType,
                        ':route' => $route,
                        ':ip' => Yii::$app->request->getRemoteIP(),
                        ':add_time' => time(),
                        ':run_time' => $useTime,
                        ':all_params' => $allParams,
                    ];
                    $insertSql = 'insert into {{%access_log}} (user_id,user_type,route,ip,add_time,run_time,all_params) value (:user_id,:user_type,:route,:ip,:add_time,:run_time,CAST(:all_params AS JSON));';
                    Yii::$app->db->createCommand($insertSql, $saveData)->execute();
                }
            }
        } catch (\Exception $exc) {
            Yii::error('访问日志写入错误:' . $exc->getMessage());
        }
        //写入accesslog end 根据需要改造或删除

        return $runResult;
    }


    /**
     * 根据jwt token获取用户id和type
     * @throws \yii\db\Exception
     */
    public function setUser()
    {
        $token = $this->post('token', '');
        if (!empty($token) && strlen($token) < 500) {
            //获取user_id和user_type
            $jwtUser = AccountCommon::decodeUserLoginToken($token);
            if (!empty($jwtUser)) {
                $nowTime = time();
                $jwtTime = intval($jwtUser->o_t ?? 0);
                //检查过期时间如果过期则返回标记
                if (empty($jwtTime) || $nowTime < $jwtTime) {
                    $tokenArr = explode('.', $token);
                    $tempUserId = intval($jwtUser->u_i ?? 0);
                    $tempUserType = $jwtUser->u_t ?? null;

                    if (!empty($tempUserId) && $tempUserType != null) { //userId,userType无效直接返回jwt验证失败
                        $this->userType = intval($tempUserType);
                        if ($this->userType === UserCommon::TYPE_DEVICE_VISITOR) {//用户类型为设备游客
                            $this->visitorUserId = $tempUserId;
                        } else {
                            $this->userId = $tempUserId;
                        }
                        $this->shortToken = end($tokenArr);
                        return ComBase::CODE_RUN_SUCCESS; //返回验证成功，后续逻辑处理
                    }

                } else {
                    return ComBase::CODE_LOGIN_EXPIRE;//返回登录过期，后续进行逻辑处理
                }
            }
        }
        return ComBase::CODE_NO_AUTH_ERROR; //返回没有通过jwt验证，后续进行逻辑处理
    }

    /**
     * 获取当前contoller 用户id
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * 游客权限验证根据配置判断,仅当权限为游客且无效时直接返回，非游客均通过
     * @param $verifyCode
     * @param $actionId
     */
    public function visitorVerification($verifyCode, $actionId)
    {
        if ($this->userType === UserCommon::TYPE_DEVICE_VISITOR && Yii::$app->params['jwt']['jwt_device_visitor_verification'] === true && $verifyCode !== ComBase::CODE_RUN_SUCCESS) {//是游客用户 & 开启jwt游客验证 & 验证不通过

            if (in_array($actionId, $this->excludeVisitorVer, true)) {
                //如果为游客验证排除数据则不进行验证
                return true;
            }

            if ($verifyCode === ComBase::CODE_LOGIN_EXPIRE) {
                //jwt过期直接输出json 前端续签
                echo Json::encode(ComBase::getReturnArray([], ComBase::CODE_LOGIN_EXPIRE, ComBase::MESSAGE_LOGIN_EXPIRE));
                exit();//结束后面所有流程
            }

            //无效jwt，由于游客没有登录状态，所以直接返回重新获取游客token进行重试,便于前端判断状态
            echo Json::encode(ComBase::getReturnArray([], ComBase::CODE_GET_VISITOR_TOKEN_RETRY, ComBase::MESSAGE_GET_VISITOR_TOKEN_RETRY));
            exit();//结束后面所有流程
        }
        return true;
    }

}
