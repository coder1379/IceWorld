<?php

namespace common\controllers;

use common\ComBase;
use common\lib\StringHandle;
use Yii;
use common\base\UserCommon;


/**
 * 接口无需用户登录及权限控制器基类
 */
class ApiCommonContoller extends BaseContoller
{
    public $enableCsrfValidation = false;
    public $excludeAccessLog = [];
    public $user = null;//user非空表示已经进行数据库查询
    public $userId = 0;//控制器userid
    public $userType = 0;//控制器usertype 主要判断是否为正式用户游客等
    public $shortToken = null; //jwt.后的短token值 用于进行数据库比较

    public function beforeAction($action)
    {
        $this->setUser();
        return true;
    }

    public function afterAction($action, $result)
    {
        $runResult = parent::afterAction($action, $result);
        //根据配置判断是否需要记录访问日志写入accesslog start
        try {
            $aciontId = strtolower($this->action->id);
            if (!in_array($aciontId, $this->excludeAccessLog, true)) {
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
                                if(mb_strlen($tepStr)>100){
                                    $tepStr = mb_substr($tepStr, 0, 100).'...';
                                }
                                $allParams[$key] = $tepStr;

                            } else {
                                $item = strval($item);
                                if(stripos($key,'token')!==false || stripos($key,'password')!==false || stripos($key,'pwd')!==false || stripos($key,'mobile')!==false || stripos($key,'phone')!==false || stripos($key,'auth')!==false){
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
                    if(!empty($_FILES)){
                        $allParams = array_merge($allParams, $_FILES);
                    }
                    $saveData = [
                        'user_id' => $this->userId,
                        'user_type' => $this->userType,
                        'route' => $route,
                        'ip' => Yii::$app->request->getRemoteIP(),
                        'add_time' => time(),
                        'run_time' => $useTime,
                        'all_params' => $allParams,
                    ];
                    Yii::$app->db->createCommand()->insert('{{%access_log}}', $saveData)->execute();
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
            //只填充user_id和user_type,user通过实际需要的时候查询数据库获得
            $jwtUser = UserCommon::decodeUserLoginToken($token);
            if(!empty($jwtUser)){
                $nowTime = time();
                $jwtTime =  intval($jwtUser->o_t??0);
                if(empty($jwtTime) || $nowTime<$jwtTime){
                    $tokenArr = explode('.');
                    $this->userId  = intval($jwtUser->u_i??0);
                    $this->userType = intval($jwtUser->u_t??0);
                    $this->shortToken = end($tokenArr);
                }else{
                    return ComBase::CODE_LOGIN_EXPIRE;
                }
            }
        }
        return ComBase::CODE_RUN_SUCCESS;
    }

    /**
     * 获取当前contoller 用户id
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

}
