<?php
namespace common\lib;

use Yii;

class RememberLogin
{

	/*
	 * 设置登录加密信息到cookie中 待修改和完善
	 * $cookieprefix 前缀标识
	 * $cookiemd5key 加密字符串
	 * $loginflag登录标识-手机号，userid等
	 * $cookietimesec 保存时间 默认30天
	 * */
	public function setToCookie($cookieprefix='',$cookiemd5key='',$loginflag='',$cookietimesec=2592000)
	{
		if (empty($cookieprefix)!=true && empty($loginflag) != true && empty($cookiemd5key) != true) {
			$cookies = Yii::$app->response->cookies;
			$timetemp = time();
			$cookies->add(new \yii\web\Cookie([
				'name' => $cookieprefix.'loginflag',
				'value' => $loginflag,
				'expire' => $timetemp + $cookietimesec,
			]));
			$cookies->add(new \yii\web\Cookie([
				'name' => $cookieprefix.'logintime',
				'value' => $timetemp,
				'expire' => $timetemp + $cookietimesec,
			]));
			$aesstr=md5(md5($timetemp.'iet'.$loginflag.$cookiemd5key).'942');
			$one=substr($aesstr, 0, 6);
			$two=substr($aesstr, 6);
			$cookies->add(new \yii\web\Cookie([
				'name' => $cookieprefix.'loginaes',
				'value' => "17f" .$one.'c53c4'.$two. "5",
				'expire' => $timetemp + $cookietimesec,
			]));
		}
	}

	/*
	 * 登录COOKIE验证并设置登录加密信息到cookie中
	 * $cookieprefix 前缀标识
	 * $cookiemd5key 加密字符串
	 * $loginflag登录标识-手机号，userid等
	 * $cookietimesec 保存时间 默认30天
	 * */
	public function checkCookie($cookieprefix='',$cookiemd5key='',$loginflag='',$cookietimesec=2592000)
	{
		if (empty($cookieprefix)!=true && empty($loginflag) != true && empty($cookiemd5key) != true) {
			$cookies = Yii::$app->response->cookies;
			$loginflagstr = $cookies->getValue($loginflag.'loginflag');
			$logintimestr = $cookies->getValue($loginflag.'logintime');
			$loginaesstr = $cookies->getValue($loginflag.'loginaes');

			if(empty($loginflagstr)!=true && empty($logintimestr)!=true && empty($loginaesstr)!=true){
				$aesstr=md5(md5($logintimestr.'iet'.$loginflagstr.$cookiemd5key).'942');
				$one=substr($aesstr, 0, 6);
				$two=substr($aesstr, 6);
				$aesnewstr="17f" .$one.'c53c4'.$two. "5";

				if($aesnewstr==$loginaesstr){
					$this->setToCookie($cookieprefix,$cookiemd5key,$loginflag,$cookietimesec);
					return true;
				}

			}

		}
		return false;
	}

}
