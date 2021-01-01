<?php
/**
 * 发送邮箱验证码任务
 */

namespace common\queues;

use yii;
use yii\base\BaseObject;

/**
 * 发送邮箱验证码任务
 * @package common\queues
 */
class SendEmailSmsJobs extends BaseObject implements \yii\queue\JobInterface
{
    public $id = null;
    public function execute($queue)
    {

        $exTime = time();
        $maxSendTime = $exTime - 640800; //控制超过7天未发送的邮件将不在发送,防止过期短信重复发
        if(empty($this->id)){
            Yii::error('SendEmailSmsJobs id 出现为空');
            return true;
        }
        //需扩展
        return true;
    }
}