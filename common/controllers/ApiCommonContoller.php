<?php
namespace common\controllers;

use Yii;
/**
 * 接口无需用户登录及权限控制器基类
 */

class ApiCommonContoller extends BaseContoller
{

    public $enableCsrfValidation = false ;
    public $user = null;

    public function beforeAction($action)
    {
        $this->setUser();
        return true;
    }

    /**
     * 根据token获取用户
     * @throws \yii\db\Exception
     */
    public function setUser(){
        $token = $this->get('token',''); //上线需要改为post
        if(!empty($token) && strlen($token)>30 && strlen($token)<100){
            $this->user = Yii::$app->db->createCommand('select * from {{%user}} where is_delete=0 and token_out_time>:token_out_time and token=:token',[':token'=>$token,':token_out_time'=>date('Y-m-d H:i:s',time())])->queryOne();
        }
    }

}
