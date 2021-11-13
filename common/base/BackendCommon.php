<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：后台公共类
 */

namespace common\base;

use Yii;
class BackendCommon extends BaseCommon
{
    public function setLoginRedirect()
    {
        Yii::$app->session->set('login.redirect.url', "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }

    private function setLoginInfoToSession($adminRecord){
        Yii::$app->session->set("admin.status",true);
        Yii::$app->session->set("admin.adminid",$adminRecord['id']);
        Yii::$app->session->set("admin.adminname",$adminRecord['login_username']);
        Yii::$app->session->set("admin.nickname", $adminRecord['nickname']);
        Yii::$app->session->set("admin.realname", $adminRecord['realname']);
        Yii::$app->session->set("admin.roleid", $adminRecord['role_id']);
        return true;
    }

    /**
     * 获取管理员ID
     * @return int
     */
    public function getAdminId(){
        $adminId = Yii::$app->session["admin.adminid"];
        if(empty($adminId)!=true){
            $adminId = intval($adminId);
        }else{
            $adminId = 0;
        }
        return $adminId;
    }

    /**
     * 获取登陆状态
     * @return mixed
     */
    public function getLoginStatus(){
        if(empty(Yii::$app->session['admin.status'])!=true && Yii::$app->session['admin.status']===true ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取权限list，可扩展至redis
     * @return array
     */
    public function getAuthList(){

        //测试后进行优化，直接使用sql语句查询

        $list = [];
        $adminId = $this->getAdminId();
        if($adminId==0){
            return false;
        }
        $params=[':id'=>$adminId];
        $roleRecord = Yii::$app->db->createCommand("select r.auth_list,r.other_auth_list,m.is_admin from {{%administrator}} m inner join {{%admin_role}} r on m.role_id=r.id where m.status=1 and m.id=:id and r.is_delete=0 and r.status=1")->bindValues($params)->queryOne();
        if(empty($roleRecord)!=true){
            $list['auth_list'] = $roleRecord['auth_list'];
            $list['other_auth_list'] = $roleRecord['other_auth_list'];
            $list['is_admin'] = $roleRecord['is_admin'];
        }
        return $list;
    }


    /**
     * 自动获取cookie数据进行登陆
     * @return bool
     */
    public function autoLogin(){
        $cookies = Yii::$app->request->cookies;
        $adminLoginName = trim($cookies->getValue('adminloginname'));
        $adminLoginTime = trim($cookies->getValue('adminlogintime'));
        $adminLoginKey = trim($cookies->getValue('adminloginkey'));
        if(empty($adminLoginName)!=true && empty($adminLoginTime)!=true && empty($adminLoginKey)!=true){
            $md5LoginString=$this->getLoginMd5Aes($adminLoginTime,$adminLoginName,Yii::$app->params["admin_auto_login_key"]);
            if($md5LoginString===$adminLoginKey){
                return $this->setAdminCookieLoginSession($adminLoginName,1);
            }else{
                $this->clearLoginCookie();
            }
        }
        return false;
    }

    /**
     * 检查是否登陆，未登录自动进行cookie验证登陆
     * @return bool
     */
    public function checkLogin()
    {
        if($this->getLoginStatus()===true ){
            return true;
        }else{
           return $this->autoLogin();
        }
    }

    /**
     * 清除登陆cookie
     */
    public function clearLoginCookie(){
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('adminloginname');
        $cookies->remove('adminlogintime');
        $cookies->remove('adminloginkey');
    }

    /**
     * 前端COOKIE Key加密
     * @param $adminLoginTime
     * @param $adminLoginName
     * @param $adminAutoLoginKey
     * @return string
     */
    public function getLoginMd5Aes($adminLoginTime,$adminLoginName,$adminAutoLoginKey){
        if(empty($adminLoginTime)!=true && empty($adminLoginName)!=true && empty($adminAutoLoginKey)!=true){
            return "60f".md5('2a287be'.md5($adminLoginTime.$adminLoginName.$adminAutoLoginKey).'30a3')."ce6";
        }else{
            return '';
        }
    }


    /**
     * 生成存入数据库的密码
     * @param $password
     * @return string
     */
    public function getSaveDBPassword($password){
        return md5(md5($password));
    }

    /**
     * 设置cookie缓存 登录
     * @param $adminRecord
     */
    public function setLoginOnline($adminRecord){
        if(!empty($adminRecord)){
            $loginTime = time();
            $timetemp=$loginTime+Yii::$app->params["adminLoginExpireTime"];
            $cookies = Yii::$app->response->getCookies();
            $this->setCookie($cookies,'adminloginname',$adminRecord['login_username'],$timetemp);
            $this->setCookie($cookies,'adminlogintime',$loginTime,$timetemp);
            $this->setCookie($cookies,'adminloginkey',$this->getLoginMd5Aes($loginTime,$adminRecord['login_username'],Yii::$app->params["admin_auto_login_key"]),$timetemp);
            return true;
        }
        return false;
    }

    /**
     * 登陆并写入session
     * @param $adminName
     * @param string $password
     * @param bool $online
     * @return bool
     */
    public function setAdminLoginSession($adminName,$password,$online=0){
        $password = strval($password);
        $params = [':login_username'=>$adminName,':status'=>1];
        $adminRecord=Yii::$app->db->createCommand('select * from {{%administrator}} where login_username=:login_username and status=:status')->bindValues($params)->queryOne();
        if(!empty($adminRecord) && !empty($password)){
            if($this->getSaveDBPassword($password)===$adminRecord['login_password']){
                $this->setLoginInfoToSession($adminRecord);

                if($online == 1){
                    $this->setLoginOnline($adminRecord);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 通过cookie登录的处理
     * @param $adminName
     * @param bool $online
     * @return bool
     */
    public function setAdminCookieLoginSession($adminName,$online=0){
        $params = [':login_username'=>$adminName,':status'=>1];
        $adminRecord=Yii::$app->db->createCommand('select * from {{%administrator}} where login_username=:login_username and status=:status')->bindValues($params)->queryOne();
        if(empty($adminRecord)){
            return false;
        }

        $this->setLoginInfoToSession($adminRecord);
        
        if($online == 1){
            $this->setLoginOnline($adminRecord);
        }
        return true;
    }

    /**
     * 判断当前用户是否具备某项权限，可优化至redis
     * @param null $controllerid
     * @param null $actionid
     * @param null $nodeid
     * @return bool
     */
    public function checkButtonAuth($mainAuthJson=null,$controllerid=null,$actionid=null,$nodeid=null){
        if(empty($controllerid)!=true && empty($actionid)!=true){
            if(empty($mainAuthJson)!=true){
                $authLevel = Yii::$app->params['authLevel'];
                if($authLevel==1){
                    if(empty($mainAuthJson->$controllerid)!=true){
                        return true;
                    }
                }else if($authLevel==2){
                    if( empty($mainAuthJson->$controllerid)!=true && empty($mainAuthJson->$controllerid->$actionid)!=true){
                        return true;
                    }
                }
            }
        }
        return false;
    }
}