<?php
/**
 * Created by PhpStorm.
 * User: majie
 * Date: 2018/4/9
 * Time: 15:31
 */

namespace common\lib;

use Yii;

class SMS
{

    /**
     * 发送短信并记录入数据库
     * @param $address 收件人地址
     * @param string $nickname 收件人昵称
     * @param $title 邮件标题
     * @param $content 邮件内容
     * @param array $otherParams 其他参数 ,例如 user_id,order_id,is_html,template = 阿里云模板
     * @return bool 发送状态
     */
    public function sendMobleMessage($address,$content,$otherParams=[]){
        $smsLogic = new SmsLogic();
        $smsData = [];
        $userId = $otherParams['user_id']??0;
        $orderId = $otherParams['order_id']??0;
        $smsData['user_id']=$userId;
        $smsData['order_id']=$orderId;
        $smsData['user_ip']=Yii::$app->request->getUserIP();
        $smsData['address']=$address;
        $smsData['contents']=$content;
        $smsData['sms_type']=1;
        $template = $otherParams['template']??'';
        $templateParam = $otherParams['templateParam']??'';

        $smsData['template']=$template;
        $smsId = $smsLogic->insert($smsData);

        $smsConfig = Yii::$app->params['sms'];
        $config = [
            'app_key' => $smsConfig['app_key'],
            'app_secret' => $smsConfig['app_secret'],
            'PhoneNumbers' => $address,
            'SignName' => $smsConfig['sign_name'],
            'TemplateCode' => $template,
            'TemplateParam' => $templateParam,
        ];

        $client = new AliSms();
        $result = $client->sendSms($config);

        if($result){
            $smsLogic->update($smsId,['send_time'=>date('Y-m-d H:i:s',time()),'send_number'=>1,'status'=>2,]);
        }else{
            $smsLogic->update($smsId,['send_time'=>date('Y-m-d H:i:s',time()),'send_number'=>1,'status'=>4,'feedback'=>'发送失败']);
            return false;
        }

        return true;
    }

    /**
     * 发送邮件并记录如数据库
     * @param $address 收件人地址
     * @param string $nickname 收件人昵称
     * @param $title 邮件标题
     * @param $content 邮件内容
     * @param array $otherParams 其他参数 ,例如 user_id,order_id,is_html,template = 阿里云模板
     * @return bool 发送状态
     */
    public function sendMail($address,$title,$content,$otherParams=[]){

        //$mail = new PHPMailer(true);

        $nickname=$otherParams['nickname']??'';

        $smsLogic = new SmsLogic();
        $smsData = [];
        $userId = $otherParams['user_id']??0;
        $orderId = $otherParams['order_id']??0;
        $smsData['user_id']=$userId;
        $smsData['order_id']=$orderId;
        $smsData['user_ip']=Yii::$app->request->getUserIP();
        $smsData['address']=$address;
        $smsData['contents']=$content;
        $smsData['sms_type']=2;
        $template = $otherParams['template']??'';
        $smsData['template']=$template;
        $smsId = $smsLogic->insert($smsData);
        try {
        $mail = new PHPMailer();
        $mailConfig = Yii::$app->db->createCommand('select id,email_service,email,username,password,send_port,smtp_secure,send_sign,is_delete from {{%email_config}} where is_delete=0 and id=7')->queryOne();
        if(empty($mailConfig)){
            Yii::error('邮件发送错误:邮件配置错误！email config error');
            return false;
        }
            //$mail->SMTPDebug = 2;
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $mailConfig['email_service'];  // Specify main and backup SMTP servers
            $mail->CharSet = 'UTF-8';
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $mailConfig['username'];                 // SMTP username
            $mail->Password = $mailConfig['password'];                           // SMTP password
            $mail->SMTPSecure =$mailConfig['smtp_secure'];                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port =$mailConfig['send_port'];                                    // TCP port to connect to

            //Recipients
            $userName = $mailConfig['username'];
            $sendSign = $mailConfig['send_sign'];
            $mail->setFrom($userName, $sendSign);
            //$mail->setFrom('tb@transblock.store', 'Tb');
            //$mail->setFrom(''.$mailConfig['username'].'', ''.$mailConfig['send_sign'].'');
            //$mail->addAddress('278352950@qq.com', 'majie');
            $mail->addAddress($address, $nickname);     // Add a recipient

            //Content
            $isHtml = empty($otherParams['is_html'])==true?false:true;
            $mail->isHTML($isHtml);                                  // Set email format to HTML
            $mail->Subject = $title;
            $mail->Body    = $content;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $sendFlag=$mail->send();

            if($sendFlag){
                $smsLogic->update($smsId,['send_time'=>date('Y-m-d H:i:s',time()),'send_number'=>1,'status'=>2,]);
            }else{
                $smsLogic->update($smsId,['send_time'=>date('Y-m-d H:i:s',time()),'send_number'=>1,'status'=>4,'feedback'=>'发送失败']);
                return false;
            }
            return true;
        } catch (Exception $e) {
            $smsLogic->update($smsId,['send_time'=>date('Y-m-d H:i:s',time()),'send_number'=>1,'status'=>4,'feedback'=>'Eception code:'.$e->getCode().',Eception msg:'.$e->getMessage()]);
           Yii::error('邮件发送错误:code='.$e->getCode().',msg='.$e->getMessage());
           return false;
        }
    }

}