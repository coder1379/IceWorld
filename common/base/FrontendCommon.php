<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：前台公共类
 */

namespace common\base;

use Yii;
class FrontendCommon extends BaseCommon
{
    public function setLoginRedirect()
    {
        Yii::$app->session->set('login.redirect.url',"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    }

    /**
     * 获取用户ID
     * @return int
     */
    public function getUserId(){
        $userId = Yii::$app->session["user.user_id"];
        if(empty($userId)!=true){
            $userId = intval($userId);
        }else{
            $userId = 0;
        }
        return $userId;
    }

    /**
     * 获取登陆状态
     * @return mixed
     */
    public function getLoginStatus(){
        if(empty(Yii::$app->session['user.status'])!=true && Yii::$app->session['user.status']===true ){
            return true;
        }else{
            return false;
        }
    }

    public function getOrderNo(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    public function getLanguageId(){
        if(Yii::$app->language=='zh-CN'){
            return 1;
        }else{
            return 2;
        }
    }

    public function getRecordSql($oldSql,$pageSize,$currentPage){
        $newSql = $oldSql.' limit '.($currentPage-1)*$pageSize . ','.$pageSize;
        return $newSql;
    }


}