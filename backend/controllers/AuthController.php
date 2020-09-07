<?php
namespace backend\controllers;


use Yii;
use common\controllers\BaseContoller;
use common\BackendCommon;

class AuthController extends BaseContoller
{
    public $noLoginAccess = [];//设置允许不登陆进行访问的页面
    public $allowLoginAccess = [];//设置登录就能访问的页面
    protected $adminMainRoleJson = null;
    protected $adminOtherRoleArray = null; //辅助权限控制器全局变量
    public function beforeAction($action)
    {
        $backendCommon = new BackendCommon();
        $authList  = $backendCommon->getAuthList();

        $authJson = json_decode(empty($authList['auth_list'])==true?'':$authList['auth_list']);//获取主权限json
        $this->adminMainRoleJson = $authJson; //将主权限付给变量

        //辅权限设置
        $otherAuthArray = explode(',',empty($authList['other_auth_list'])==true?'':$authList['other_auth_list']);
        $this->adminOtherRoleArray = $otherAuthArray; //设置辅助权限控制器全局变量

        ////////////////检查是否属于无需权限验证的操作
        if(in_array($action->id,$this->noLoginAccess,true)!==true){
            ////需要进行权限验证
            if($backendCommon->checkLogin()===true){
                if(empty($authList)!=true){
                    
                    if(empty($authList['is_admin'])!=true && $authList['is_admin']===1){
                        return true;
                    }
                    
                    $controllerId=$action->controller->id;
                    $actionId=$action->id;

                    $authLevel = Yii::$app->params['authLevel'];
                    if($authLevel==1){
                        if(empty($authJson->$controllerId)!=true){
                            return true;
                        }
                    }else if($authLevel==2){
                        if(empty($authJson->$controllerId->$actionId)!=true){
                            return true;
                        }
                    }

                    if(in_array($controllerId.'/'.$actionId,$otherAuthArray,true)==true){
                        //主权验证不通过验证辅权限
                        return true;
                    }
                }
                
                ///////////////没有操作权限 start
                if(Yii::$app->request->isAjax==true){
                    $this->echoJson([],403,'您没有当前操作的权限!');
                }else{
                    echo "您没有当前操作的权限!";
                    exit();
                }
                ///////////////没有操作权限 end
            }else{
                ///////////////登录已超时 start
                if (Yii::$app->request->isAjax == true) {
                    $this->echoJson([],403,'登录已超时，请重新登录！');
                    } else {
                        echo "登录已超时，请重新登录！";
                        exit();
                    }
                ///////////////登录已超时 end
            }
        }
        return true;
    }

    public function getAdminId(){
        $backendCommon = new BackendCommon();
        return $backendCommon->getAdminId();
    }
}
