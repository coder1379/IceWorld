<?php

namespace common\lib;

use Yii;
use OSS\OssClient;

class UploadOSS
{
    public static $oss;

    public function __construct()
    {
        $accessKeyId = Yii::$app->params['oss']['accessKeyId'];                 //获取阿里云oss的accessKeyId
        $accessKeySecret = Yii::$app->params['oss']['accessKeySecret'];         //获取阿里云oss的accessKeySecret
        $endpoint = Yii::$app->params['oss']['endPoint'];                       //获取阿里云oss的endPoint
        self::$oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);  //实例化OssClient对象
    }

    /**
     * 使用阿里云oss上传文件
     * @param $object   保存到阿里云oss的文件名
     * @param $filepath 文件在本地的绝对路径
     * @return bool     上传是否成功
     * @throws \OSS\Core\OssException
     */
    public function upload($object, $filepath)
    {
        $res = false;
        $bucket = Yii::$app->params['oss']['bucket'];               //获取阿里云oss的bucket
        if (self::$oss->uploadFile($bucket, $object, $filepath)) {  //调用uploadFile方法把服务器文件上传到阿里云oss
            $res = true;
        }

        return $res;
    }

    public function getOssName($ext = '.jpg',$block='')
    {
        if(!empty($block)){
            $block=$block.'/';
        }
        return $block.date('Ymd') . '/' . time() . rand(1000000, 9999999) . $ext;
    }


    public function getOssUrl($ossName)
    {
        return 'https://' . Yii::$app->params['oss']['bucket'] . '.' . Yii::$app->params['oss']['endPoint'] . '/' . $ossName;
    }

    /**
     * 删除指定文件
     * @param $object 被删除的文件名
     * @return bool   删除是否成功
     */
    public function delete($object)
    {
        $res = false;
        $bucket = Yii::$app->params['oss']['bucket'];    //获取阿里云oss的bucket
        if (self::$oss->deleteObject($bucket, $object)) { //调用deleteObject方法把服务器文件上传到阿里云oss
            $res = true;
        }
        return $res;
    }
}
