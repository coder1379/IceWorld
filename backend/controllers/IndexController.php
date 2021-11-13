<?php
namespace backend\controllers;

use common\services\admin\AdminLoginLogModel;
use Yii;
use common\base\BackendCommon;
class IndexController extends AuthController
{
    /**
     * @inheritdoc
     */
    public $layout = 'main-iframe';
    public $enableCsrfValidation = false;
    public $noLoginAccess=['login','captcha','logout','index','welcome','404','admininfo','updatepassword'];///不在auth中进行登录与权限验证的访问
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 40,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }

    public function actionIndex()
    {
        $backendCommon = new BackendCommon();
        if($backendCommon->checkLogin()===true){
            $this->layout="main";
            //检查adminMainRoleJson是否为空,为空重新获取一次解决auth先验证session在写入导致首页全新列表为显示问题 start
            $authList  = $backendCommon->getAuthList();
            $authJson = json_decode(empty($authList['auth_list'])==true?'':$authList['auth_list']);//获取主权限json
            $this->adminMainRoleJson = $authJson; //设置controler全局权限变量
            ///end
            return $this->render('index',['menuList'=>$this->getMenuList()]);
        }else{
            return $this->redirect(array('index/login'));
        }
    }

    private function getMenuList(){
        $params = [':is_delete' => 0, ':status' => 1];

        $adminMenuRecord = Yii::$app->db->createCommand("select * from {{%admin_menu}} where is_delete=:is_delete and status=:status order by m_level asc,show_sort asc")->bindValues($params)->queryAll();
        $adminMenuList = [];
        if(empty($adminMenuRecord)!=true){
            foreach ($adminMenuRecord as $m){
                if($m['m_level'] == 1){ // 1级菜单
                    $adminMenuList[$m['id']] = $m;
                }else if($m['m_level'] == 2){ // 二级菜单
                    if(empty($adminMenuList[$m['parent_id']])!=true){
                        if(empty($m['controller'])==true || empty($m['c_action'])==true){
                            continue;
                        }
                        $authLevel = Yii::$app->params['authLevel'];
                        $controllerId = $m['controller'];
                        $actionId = $m['c_action'];
                        if($authLevel == 1){
                            if(empty($this->adminMainRoleJson->$controllerId)!=true){
                                $tempM=$adminMenuList[$m['parent_id']];
                                $tempM['nextMenu'][] = $m;
                                $adminMenuList[$m['parent_id']] = $tempM;
                            }
                        }else if($authLevel == 2){
                            if(empty($this->adminMainRoleJson->$controllerId->$actionId)!=true){
                                $tempM=$adminMenuList[$m['parent_id']];
                                $tempM['nextMenu'][] = $m;
                                $adminMenuList[$m['parent_id']] = $tempM;
                            }
                        }

                    }
                }
                //目前不在等级范围内或没有上级的非1级菜单直接忽略
            }
        }
        return $adminMenuList;
    }

    public function actionWelcome()
    {
        $backendCommon = new BackendCommon();
        if($backendCommon->checkLogin()===true){
            return $this->render('welcome');
        }else{
            return $this->redirect(array('index/login'));
        }
    }

    public function action404(){
        if(Yii::$app->request->isAjax==true){
            $this->echoJson([],404,'您访问的页面没有找到！');
        }else{
            return $this->render('404');
        }
    }

    public function actionAdmininfo(){

        $backendCommon = new BackendCommon();
        if($backendCommon->checkLogin()===true){
            $adminInfo = Yii::$app->db->createCommand("select a.*,b.name as role_name from {{%administrator}} a inner join {{%admin_role}} b on a.role_id=b.id where a.status=1 and a.id=:id ")->bindValues([':id'=>Yii::$app->session["admin.adminid"]])->queryOne();

            return $this->render('admininfo',['adminInfo'=>$adminInfo]);
        }else{
            echo '登录已过期，请重新登录!';
            exit();
        }
    }

    public function actionUpdatepassword(){

        $backendCommon = new BackendCommon();
        if($backendCommon->checkLogin()===true){
            $post = Yii::$app->request->post();
            if(!empty($post)){
                $oldPassword = $post['old_password']??'';
                $oldPassword=trim($oldPassword);
                $newPassword = $post['new_password']??'';
                $newPassword=trim($newPassword);
                $confirmPassword = $post['confirm_password']??'';
                $confirmPassword=trim($confirmPassword);
                if(empty($oldPassword)){
                    $this->echoJson([],100001,'旧密码不能为空!');
                }
                if(empty($newPassword)){
                    $this->echoJson([],100001,'新密码不能为空!');
                }

                if(strlen($newPassword)<5 || strlen($newPassword)>20){
                    $this->echoJson([],100001,'新密码长度错误!');
                }

                if($newPassword!=$confirmPassword){
                    $this->echoJson([],100001,'新密码不匹配!');
                }

                $adminInfo = Yii::$app->db->createCommand("select * from {{%administrator}} where status>-1 and id=:id ")->bindValues([':id'=>Yii::$app->session["admin.adminid"]])->queryOne();

                if(empty($adminInfo)){
                    $this->echoJson([],100001,'管理用户不存在!');
                }

                if($adminInfo['status']!=1){
                    $this->echoJson([],100001,'管理用户已被冻结!');
                }

                if($adminInfo['login_password'] !=  $backendCommon->getSaveDBPassword($oldPassword) ){
                    $this->echoJson([],100001,'旧密码错误!');
                }

                if($adminInfo['login_password'] ==  $backendCommon->getSaveDBPassword($newPassword)){
                    $this->echoJson([],100001,'新密码与旧密码相同!');
                }

                $updateFlag = Yii::$app->db->createCommand("update {{%administrator}} set login_password=:login_password where status>-1 and id=:id ")->bindValues([':id'=>Yii::$app->session["admin.adminid"],':login_password'=>$backendCommon->getSaveDBPassword($newPassword)])->execute();
                if(!empty($updateFlag)){
                    $this->echoJson([],200,'密码已修改!');
                }else{
                    $this->echoJson([],100001,'操作错误!');
                }


            }else{
                return $this->render('updatepassword');
            }

        }else{
            echo '登录已过期，请重新登录!';
            exit();
        }
    }

    public function actionLogin()
    {
        $this->layout="main-login";
        $backendCommon = new BackendCommon();
        if($backendCommon->checkLogin()===true){
            return $this->redirect(array('index/index'));
        }else{
            $adminName=trim($this->post('backendloginadminname',''));
            $adminPassword=trim($this->post('backendloginadminpassword',''));
            $captchaImg=trim($this->post('backendcaptchacode',''));
            $errorStr='';
            if(empty($adminName)==true || empty($adminPassword)==true || empty($captchaImg)==true){
                //$errorStr='无效参数';
            }else{
                if($captchaImg!=Yii::$app->session['__captcha/index/captcha']){
                    $errorStr='验证码错误';
                    Yii::$app->session->remove('__captcha/index/captcha');
                    Yii::$app->session->remove('__captcha/index/captchacount');
                }else{
                    $loginStartus= $backendCommon->setAdminLoginSession($adminName,$adminPassword,$this->post('backendonline',0));
                    if($loginStartus!=true){
                        $errorStr='用户名或密码错误！';
                    }else{
                        Yii::$app->session->remove('__captcha/index/captcha');
                        Yii::$app->session->remove('__captcha/index/captchacount');
                        //登录成功写入登录日志
                        $loginLogModel = new AdminLoginLogModel();
                        $loginLogModel->admin_id = $this->getAdminId();
                        $loginLogModel->type = 1;
                        $loginLogModel->add_time = time();
                        $loginLogModel->ip = Yii::$app->request->getRemoteIP();
                        $loginLogModel->status = 1;
                        $loginLogModel->device_desc = Yii::$app->request->getUserAgent();
                        if(mb_strlen($loginLogModel->device_desc)>250){
                            $loginLogModel->device_desc = mb_substr($loginLogModel->device_desc, 0, 250);
                        }
                        $loginLogModel->scenario = 'create';
                        $bool = $loginLogModel->save();
                        if(!$bool){
                            $messageArr = $loginLogModel->getErrors();
                            Yii::error('admin登录日志写入错误:'.json_encode($messageArr));
                        }
                        return $this->redirect(array('index/index'));
                    }

                }
            }
            return $this->render('login',['error'=>$errorStr]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->session->removeAll();
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('adminloginname');
        $cookies->remove('adminlogintime');
        $cookies->remove('adminloginkey');
        return $this->redirect(array('index/login'));
    }
}
