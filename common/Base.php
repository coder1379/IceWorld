<?php
/**
 * create by: majie
 * datetime: 2018-03-01 10:30
 * desc：基础静态公共类
 */

namespace common;

use Yii;
class Base
{
    public static $successMessage='操作成功。';
    public static $createMessage='创建成功。';
    public static $updateMessage='修改成功。';
    public static $deleteMessage='删除成功。';
    /**
     * 返回JSON数据格式
     * @param array $data 数组数据
     * @param int $code 状态码 200成功,400 请求格式错误，401未授权，403权限不足，408请求超时，429请求次数过多，503服务不可用，10000自定义错误代码开始值
     * @param string $msg 消息
     * @return array 格式数组
     */
    public static function getJsonArray($data=[],$code=200, $msg='success'){
        if(empty($data)==true){
            $data=new \StdClass();//将空数组赋值空对象便于前端兼容处理.
        }
        return ['code'=>$code,'msg'=>$msg,'data'=>$data];
    }

    public static function getJsonString($data=[],$code=200, $msg='success'){
        return json_encode(self::getJsonArray($data,$code,$msg));
    }

    /**
     * 获取模型的错误数组模式，并将第一个错误格式化到返回错误中
     * @param $errors
     * @return array
     */
    public static function getModelErrorsToArray($errors){
        $returnErrors = ['all'=>[],'first'=>['k'=>'','v'=>self::getUnknowErrorMassage(false)]];
        if(!empty($errors)){
            $firstFlag = true;
           foreach ($errors as $k=>$v){
               $returnErrors['all'][] = [$k=>$v[0]];
               if($firstFlag==true){
                   $returnErrors['first']['k']=$k;
                   $returnErrors['first']['v']=$v[0];
                   $firstFlag=false;
               }
           }
        }
        return $returnErrors;
    }

    /**
     * 将 getModelErrorsToArray 格式化后的数据再次格式化为直接能返回前端的数据
     * @param $errors
     * @return array
     */
    public static function getFormatErrorsArray($errors){
        $returnData = ['firstKey'=>$errors['first']['k']];
        if(Yii::$app->params['returnAllErrors']==true){
            $returnData['allErrors']=$errors['all'];
        }
        return self::getJsonArray($returnData,10111,$errors['first']['v']);
    }

    /**
     * 获取参数错误的统一提示
     * @return array
     */
    public static function getParameterErrorMassage($returnFormatArray=false){
        $returnStr = '参数错误。';
        if($returnFormatArray == true){
            return self::getJsonArray([],10001,$returnStr);
        }else{
            return $returnStr;
        }
    }

    /**
     * 获取操作失败的统一提示
     * @return array
     */
    public static function getOperationFailedMassage($returnFormatArray=false){
        $returnStr = '操作失败,请重试。';
        if($returnFormatArray == true){
            return self::getJsonArray([],10001,$returnStr);
        }else{
            return $returnStr;
        }
    }

    /**
     * 获取未知错误的统一提示
     * @return array
     */
    public static function getUnknowErrorMassage($returnFormatArray=false){
        $returnStr = '未知的错误。';
        if($returnFormatArray == true){
            return self::getJsonArray([],10001,$returnStr);
        }else{
            return $returnStr;
        }
    }


    /**
     * 封装YII2 设置cookie
     * @param $cookies
     * @param string $name
     * @param string $value
     * @param int $time
     */
    public static function setCookie($cookies,$name='',$value='',$time=0){
        if(empty($name)!=true){
            $cookies->add(new \yii\web\Cookie([
                'name' => $name,
                'value' => $value,
                'expire'=>$time,
            ]));
        }
    }

    /**
     * 获取系统上传文件路径
     */
    public static function getUploadRootPath(){
        return Yii::getAlias('@static');
    }

    public static function getMd5Key(){
        return Yii::$app->params['md5Key'];
    }

}