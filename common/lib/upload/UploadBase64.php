<?php


namespace common\lib\upload;

/**
 *
 * 上传通过base64
 * @package common\lib\upload
 */

use common\ComBase;
use Yii;

class UploadBase64
{
    public function uploadImageResource($imagedata,$prefix='')
    {
        //获取宽高
        $imageinfo = null;
        try{
            $imageinfo = getimagesizefromstring($imagedata);
        }catch (\Exception $exc){
            return ComBase::getReturnArray([], ComBase::CODE_PARAM_FORMAT_ERROR, '上传文件格式错误');
        }

        $ext = "";
        $mime = $imageinfo['mime']??'';
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $ext = 'jpg';
                break;
            case 'image/png':
                $ext = 'png';
                break;
            case 'image/gif':
                $ext = 'gif';
                break;
            case 'image/bmp':
                $ext = 'bmp';
                break;
            default:
                return ComBase::getReturnArray([], ComBase::CODE_PARAM_FORMAT_ERROR, '上传文件格式错误');
                break;
        }

        $filemd5 = md5($imagedata);
        $path = $filemd5.rand(100,999). "." . $ext;
        if(!empty($prefix)) {
            $path = $prefix.'/'.date('ymd'). "/" . $path;
        } else {
            $path = date('ymd').'/' . $path;
        }


        $oss = new \OSS\OssClient(Yii::$app->params['oss']['accessKeyId'], Yii::$app->params['oss']['accessKeySecret'], Yii::$app->params['oss']['endPoint']);
        $oss->putObject(Yii::$app->params['oss']['bucket'], $path, $imagedata);

        return ComBase::getReturnArray(['short_url'=>$path,'url' => $this->getOssUrl($path)]);
    }

    public function getOssUrl($path)
    {
        $url = '';
        if(empty(Yii::$app->params['oss']['oss_base_link'])){
            $url = 'https://' . Yii::$app->params['oss']['bucket'] . '.' . Yii::$app->params['oss']['endPoint'] . '/' . $path;
        }else{
            $url = Yii::$app->params['oss']['oss_base_link'] . $path;
        }
        return $url;
    }

}