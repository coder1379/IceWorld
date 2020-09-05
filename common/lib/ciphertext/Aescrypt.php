<?php
namespace common\lib\ciphertext;


/**
 * Prpcrypt class
 *
 * 提供消息的加解密接口.
 */
class Aescrypt
{
	public $key;

	public function __construct($k){
		$this->key = $k;
	}

	/**
	 * 对明文进行加密
	 * @param string $text 需要加密的明文
	 * @return string 加密后的密文
	 */
	//public function encrypt($text, $appid)
	public function encrypt($text)
	{
		if(trim($text)==""){
			return "";
		}
		try {

			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$iv = substr($this->key, 0, 16);
			//使用自定义的填充方式对明文进行补位填充
			$pkc_encoder = new PKCS7EncoderAes;
			$text = $pkc_encoder->encode($text);
			mcrypt_generic_init($module, $this->key, $iv);
			//加密
			$encrypted = mcrypt_generic($module, $text);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);

			//print(base64_encode($encrypted));
			//使用BASE64对加密后的字符串进行编码
			//return array(ErrorCode::$OK, base64_encode($encrypted));
			return base64_encode($encrypted);
			//return $encrypted;
		} catch (Exception $e) {
			//print $e;
			return array(ErrorCode::$EncryptAESError, null);
		}
	}

	/**
	 * 对密文进行解密
	 * @param string $encrypted 需要解密的密文
	 * @return string 解密得到的明文
	 */
	//public function decrypt($encrypted, $appid)
	public function decrypt($encrypted)
	{
		if(trim($encrypted)==""){ return ""; }

		try {
			//使用BASE64对需要解密的字符串进行解码
			$ciphertext_dec = base64_decode($encrypted);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			//$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
			$iv = substr($this->key, 0, 16);
			mcrypt_generic_init($module, $this->key, $iv);

			//解密
			$decrypted = mdecrypt_generic($module, $ciphertext_dec);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
		} catch (Exception $e) {
			return array(ErrorCode::$DecryptAESError, null);
		}


		try {
			//去除补位字符
			$pkc_encoder = new PKCS7EncoderAes;
			$result = $pkc_encoder->decode($decrypted);
			return $result;
		} catch (Exception $e) {
			//print $e;
			return array(ErrorCode::$IllegalBuffer, null);
		}
	}

	/////////////////加密字符串url自定义加密 url get传递参数时常有
	public function baourlencode($needcodestr){
		$needcodestr=trim($needcodestr);
		if($needcodestr!=''){
			$needcodestr=str_replace("+","-",str_replace("/","_",str_replace("=","*",$needcodestr)));
		}

		return $needcodestr;

	}

	/////////////////加密字符串url自定义解密 url get传递参数时常有
	public function baourldecode($needcodestr){
		$needcodestr=trim($needcodestr);
		if($needcodestr!=''){
			$needcodestr=str_replace("-","+",str_replace("_","/",str_replace("*","=",$needcodestr)));
		}

		return $needcodestr;

	}


}
//示例
/*
		$aescrypto =new Aescrypt("bao_@8*5sm01mj11");//初始化
		$encrytempstr=$aescrypto->encrypt("wefwefbaosm");  //加密
		echo $encrytempstr."_".$aescrypto->decrypt($encrytempstr); //解密
		exit();

*/

