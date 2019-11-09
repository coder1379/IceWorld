<?php
namespace common\lib;

use Yii;

class SmsCode
{
	//待修改和完善

	public $outTimeMessage='验证码已过期，请重新获取！';
	public $noCodeMessage='无效验证码！';
	public $errorMessage='验证码错误！';
	public $nextTimeMessage='您获取验证码次数过多，请稍候再试！';
	/*
	sessionName 被保存session name
	mobile 接收手机号
	code 验证码
	outtime 过期时间
	nexttime 下次发送时间
	时间单位为秒

	返回：true 设置成功
	error 错误代码
	*/
	public function setMobileCode($sessionName='mobile',$mobile,$code,$outTime=1800,$nextTime=60)
	{
		$codeStr='SmsCode.';
		if(empty(Yii::$app->session[$codeStr.$sessionName.'.nexttime'])!=true && Yii::$app->session[$codeStr.$sessionName.'.nexttime']>time()){
			return array('error'=>1,'message'=>$this->nextTimeMessage);
		}else{
			
			Yii::$app->session[$codeStr.$sessionName.'.mobile']=$mobile;
			Yii::$app->session[$codeStr.$sessionName.'.code']=$code;
			Yii::$app->session[$codeStr.$sessionName.'.nexttime']=time()+$nextTime;
			Yii::$app->session[$codeStr.$sessionName.'.outtime']=time()+$outTime;
			return true;
		}
	}


	/*
	sessionName 被保存session name
	mobile 接收手机号
	code 验证码

	返回：true 检查通过为相同手机，相同验证码
	error 错误代码 1=普通错误，2=验证码已过期，9=验证码错误
	*/
	public function checkMobileCode($sessionName='mobile',$mobile,$code){
		$codeStr='SmsCode.';
		if(empty(Yii::$app->session[$codeStr.$sessionName.'.mobile'])==true || empty(Yii::$app->session[$codeStr.$sessionName.'.code'])==true || empty(Yii::$app->session[$codeStr.$sessionName.'.outtime'])==true){
			return array('error'=>1,'message'=>$this->noCodeMessage);
		}else{
			if(Yii::$app->session[$codeStr.$sessionName.'.outtime']<time()){
				return array('error'=>2,'message'=>$this->outTimeMessage);
			}else if(Yii::$app->session[$codeStr.$sessionName.'.mobile']==$mobile && Yii::$app->session[$codeStr.$sessionName.'.code']==$code){
				return true;
			}else{
				return array('error'=>9,'message'=>$this->errorMessage);
			}

		}
	
	}

}
