<?php
namespace backend\controllers;


use common\ComBase;
use Yii;
use common\controllers\BaseContoller;
use common\base\BackendCommon;
use yii\helpers\Json;

class AuthController extends BaseContoller
{
    public $noLoginAccess = [];//设置允许不登陆进行访问的页面
    public $allowLoginAccess = [];//设置登录就能访问的页面
    protected $adminMainRoleJson = null;
    protected $adminOtherRoleArray = null; //辅助权限控制器全局变量

    public function beforeAction($action)
    {
        $backendCommon = new BackendCommon();

        ////////////////检查是否属于无需权限验证的操作
        if(!in_array($action->id,$this->noLoginAccess,true)){
            ////需要进行权限验证
            if($backendCommon->checkLogin()===true){

                //获取并设置权限字段
                $authList  = $backendCommon->getAuthList();
                $authJson = json_decode(empty($authList['auth_list'])==true?'':$authList['auth_list']);//获取主权限json
                $this->adminMainRoleJson = $authJson; //将主权限付给变量
                //辅权限设置
                $otherAuthArray = explode(',',empty($authList['other_auth_list'])==true?'':$authList['other_auth_list']);
                $this->adminOtherRoleArray = $otherAuthArray; //设置辅助权限控制器全局变量


                if(!empty($authList)){
                    
                    if(!empty($authList['is_admin']) && $authList['is_admin']===1){
                        return true;
                    }
                    
                    $controllerId=$action->controller->id;
                    $actionId=$action->id;

                    $authLevel = Yii::$app->params['authLevel'];
                    if($authLevel==1){
                        if(!empty($authJson->$controllerId)){
                            return true;
                        }
                    }else if($authLevel==2){
                        if(!empty($authJson->$controllerId->$actionId)){
                            return true;
                        }
                    }

                    if(in_array($controllerId.'/'.$actionId,$otherAuthArray,true)){
                        //主权验证不通过验证辅权限
                        return true;
                    }
                }
                
                ///////////////没有操作权限 start
                if(Yii::$app->request->isAjax==true){
                    $this->echoJson([],ComBase::CODE_NO_AUTH_ERROR,'您没有当前操作的权限!');
                }else{
                    echo "您没有当前操作的权限!";
                    exit();
                }
                ///////////////没有操作权限 end
            }else{
                ///////////////登录已超时 start
                if (Yii::$app->request->isAjax == true) {
                    $this->echoJson([],ComBase::CODE_NO_AUTH_ERROR,'登录已超时，请重新登录！');
                    } else {
                        echo "登录已超时，请重新登录！";
                        exit();
                    }
                ///////////////登录已超时 end
            }
        }
        return true;
    }

    /**
     * 返回JSON数据格式
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     * @return array 格式数组
     */
    public function getJsonArray($data = null, $code = null, $msg = null)
    {
        if (empty($data)) {
            $data = new \StdClass();//将空数组赋值空对象便于前端判断兼容处理.
        }

        if ($code === null) {
            $code = 200; //后端默认为200与前端分离防止 api与backend 出现返回值不同情况
        }

        if ($msg === null) {
            $msg = ComBase::MESSAGE_RUN_SUCCESS;
        }

        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 直接输出json数据到前端
     * @param array $data 数组数据
     * @param int $code 代码值
     * @param string $msg 消息
     */
    public function echoJson($data = [], $code = 200, $msg = 'success')
    {
        echo Json::encode($this->getJsonArray($data, $code, $msg));
        exit();
    }

    //后台调用封装
    public function getJsonString($data = [], $code = 200, $msg = 'success')
    {
        return Json::encode($this->getJsonArray($data, $code, $msg));
    }

    public function getAdminId(){
        $backendCommon = new BackendCommon();
        return $backendCommon->getAdminId();
    }
}