<?php
namespace api\controllers;

use common\controllers\ApiCommonContoller;
use common\lib\queue\SendSms;
use common\queues\TestJobs;
use Yii;
use yii\helpers\Json;
/**
 * api controller
 */
class IndexController extends ApiCommonContoller
{
	public $enableCsrfValidation = false;

    public function actionIndex(){
        return Json::encode($this->getJsonArray([],200,''));
    }

    public function action404(){
        return Json::encode($this->getJsonArray([],404,'error'));
    }

    public function actionSend(){

        exit();

        for($i=0;$i<100;$i++){
            $arr = [
                'addtime' => time(),
            ];
            Yii::$app->db->createCommand()->insert('m_testjob', $arr)->execute();
            $tid = Yii::$app->db->getLastInsertID();
            $id = Yii::$app->queue->push(new TestJobs(['params_1'=>'insert time:'.time(),'params_2'=>$tid]));
            print_r($id.'_'.$tid);
        }

        exit();

        $sendTxt = "发送短信测试".rand(900,999);

        $rtime = rand(2,5);
        $newRtime = rand(1000,9999);
        Yii::$app->db->createCommand("insert into {{%test_run}} (add_time,rand_number,type) values ('".date("Y-m-d H:i:s")."',".$newRtime.",1)")->execute();
        $array = [];
        for ($j=0;$j<$newRtime;$j++){
            $array[] = $j;
        }
        Yii::$app->db->createCommand("insert into {{%test_queue}} (add_time,queue_txt,queue_time) values ('".date("Y-m-d H:i:s")."','".$sendTxt."','".date("Y-m-d H:i:s")."')")->execute();

        $id = Yii::$app->db->getLastInsertID();
        Yii::$app->queue->push(new SendSms(['id'=>$id,'message'=>$sendTxt]));
        return Json::encode($this->getJsonArray([],200,''));
    }

    public function actionTestrun(){
        $rtime = rand(5,30);
        $newRtime = rand(10,20);
        $array = [];
        for ($j=0;$j<$newRtime;$j++){
            $array[] = $j;
        }
        Yii::$app->db->createCommand("insert into {{%test_run2}} (add_time,rand_number) values ('".date("Y-m-d H:i:s")."',".$newRtime.")")->execute();
        return Json::encode($this->getJsonArray([],200,'test ok'.$newRtime));
    }


}
