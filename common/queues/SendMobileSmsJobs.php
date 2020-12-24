<?php
/**
 * 发送手机验证码任务
 */

namespace common\queues;

use common\services\sms\SmsMobileModel;
use yii;
use yii\base\BaseObject;

/**
 * 发送短信验证码任务
 * @package common\queues
 */
class SendMobileSmsJobs extends BaseObject implements \yii\queue\JobInterface
{
    public $id = null;
    public function execute($queue)
    {

        $exTime = time();
        $maxSendTime = $exTime - 640800; //控制超过7天未发送的短信将不在发送方式过期短信重复发
        if(empty($this->id)){
            Yii::error('SendMobileSmsJobs id 出现为空');
            return true;
        }
        $selectArr = ['id', 'name', 'object_id', 'object_type', 'user_id', 'area_num', 'mobile', 'other_mobiles', 'content', 'params_json', 'send_num', 'type', 'send_type', 'sms_type', 'template', 'add_time', 'status'];
        $smsData = SmsMobileModel::find()->select($selectArr)->where(['id' => $this->id, 'status' => 2])->one();
        if(!empty($smsData)){
            $addTime = $smsData->add_time;
            if($addTime>$maxSendTime){
                $mobile = $smsData->mobile;
                $sendStatus = false;
                if(empty(Yii::$app->params['send_sms'])){
                    //环境不发送短信
                    $sendStatus = true;
                }else{
                    //真实环境根据需要维护发送短信验证码逻辑 **********************

                    $sendStatus = false;
                }
                if($sendStatus==true){
                    //短信发送成功
                   $updateNum = Yii::$app->db->createCommand('update {{%sms_mobile}} set status=1,send_time=' . $exTime . ',send_num=send_num+1 where id=' . $this->id . ' and status=2')->execute();
                   if($updateNum==0){
                       Yii::error('短信发送任务出现重复发送忽略 SendMobileSmsJobs id:'.$this->id);//仅做记录便于查看任务稳定性
                   }
                }else{
                    //短信发送失败目前不做其他重发处理，根据业务自行扩展
                    $updateNum = Yii::$app->db->createCommand('update {{%sms_mobile}} set status=3,send_time=' . $exTime . ',send_num=send_num+1 where id=' . $this->id . ' and status=2')->execute();
                    if($updateNum==0){
                        Yii::error('短信发送任务失败并出现重复发送忽略 SendMobileSmsJobs id:'.$this->id);//仅做记录便于查看任务稳定性
                    }
                }
            }

        }

        return true;
    }
}