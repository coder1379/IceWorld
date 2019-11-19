<?php
namespace api\controllers;

use common\controllers\ApiCommonAuthContoller;
use common\services\mobilesms\MobileSmsLogic;
use common\services\user\UserLogic;
use common\services\user\UserModel;
use Yii;
use yii\helpers\Json;
/**
 * api controller
 */
class UserController extends ApiCommonAuthContoller
{
    //测试token:314a0d453fea22228db415080e2c541df584cc15
    //id 32
    public $enableCsrfValidation = false;
    public $allowAccessActions=['create','signin','signout'];
    public function actionList(){
        $logic = new UserLogic();
        $result = $logic->list($this->get());
        return Json::encode($result);
    }

    /**
     * targetDoc->common\services\user\UserLogic->detail
     *
     */
    public function actionDetail(){
        $logic = new UserLogic();
        $result = $logic->detail($this->get());
        return Json::encode($result);
    }

    public function actionCreate(){
        $logic = new UserLogic();
        $result = $logic->create($this->get());
        if($result['code']==200){
            $message = new MobileSmsLogic();
            $message->create(['mobile'=>$this->get('mobile'),'contents'=>'恭喜你注册成功！']);
        }
        return Json::encode($result);
    }

    public function actionUpdate(){
        $logic = new UserLogic();
        $result = $logic->update($this->get());
        return Json::encode($result);
    }

    public function actionDelete(){
        $logic = new UserLogic();
        $result = $logic->delete($this->get());
        return Json::encode($result);
    }

    public function actionSignin(){
        $logic = new UserLogic();
        $result = $logic->signin($this->get());
        return Json::encode($result);
    }

    public function actionSignout(){
        $logic = new UserLogic();
        $result = $logic->signout($this->get());
        return Json::encode($result);
    }

}
