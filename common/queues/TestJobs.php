<?php
/**
 * 测试命队列任务
 */

namespace common\queues;

use yii;
use yii\base\BaseObject;

class TestJobs extends BaseObject implements \yii\queue\JobInterface
{
    public $params_1 = '';
    public $params_2 = 0;
    public function execute($queue)
    {

        $exTime = time();
       $set = Yii::$app->db->createCommand('update m_testjob set extime='.$exTime.',uptime=uptime+1,p1=:p1,p2='.$this->params_2.' where id='.$this->params_2,[':p1' => $this->params_1."xxx"])->execute();
        #Yii::info('queue id:'.$this->params_1.'_'.$this->params_2.'_'.$set);
        #$a = rand(1, 10);
        //throw new \Exception("任务发生异常:" . $a); //当需要手动结束此次任务时直接抛出异常，未发送异常都视为处理成功
        /*if($a<4){
            throw new \Exception("任务发生异常:" . $a);
            return false;
        }*/

        #return true;
    }
}