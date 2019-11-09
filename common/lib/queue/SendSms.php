<?php

namespace common\lib\queue;

use Yii;
use yii\base\Exception;
use yii\base\Object;
/**
 * 添加发送短信任务到消息队列
 * Class SendSms
 * @package common\lib\queue
 * Author: majie
 */
class SendSms extends Object implements \yii\queue\Job
{
    public $message = '';
    public $id = '';

    public function execute($queue){
        try{
            $rtime = rand(1,3);
            $newRtime = rand(1000,9999);
            Yii::$app->db->createCommand("insert into {{%test_run}} (add_time,rand_number,type) values ('".date("Y-m-d H:i:s")."',".$newRtime.",9)")->execute();
            $array = [];
            for ($j=0;$j<$newRtime;$j++){
                $array[] = $j;
            }
        Yii::$app->db->createCommand("update {{%test_queue}} set queue_time='".date("Y-m-d H:i:s")."',run_time=run_time+1 where id=".$this->id)->execute();
        }catch (\Exception $ex){
            echo $ex->getMessage();
        }
    }

}