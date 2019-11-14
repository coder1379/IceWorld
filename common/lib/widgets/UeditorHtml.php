<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/28
 * Time: 17:43
 */

namespace common\lib\widgets;

use Yii;
use ReflectionClass;

class UeditorHtml
{
    public function getLinkScript(){
        echo '<script type="text/javascript" src="/lib/ueditor/ueditor.config.js"></script><script type="text/javascript" src="/lib/ueditor/ueditor.all.min.js"> </script>';
    }

    public function getUeObjectName(){
        return 'ueObj_';
    }

    public function createUeditor($model,$attrName,$showName='',$otherArr=[]){
        $reflector = new ReflectionClass($model);
        $modelName = $reflector->getShortName();
        $oldValue = '';
        $width = $otherArr['width']??'60%';
        $height = $otherArr['height']??600;
        $autoHeightEnabled = $otherArr['autoHeightEnabled']??true;
        $autoFloatEnabled = $otherArr['autoFloatEnabled']??true;
        if(empty($model->$attrName)!=true){
            $oldValue = $model->$attrName;
        }
        $html = '<div class="form-group field-'.$attrName.'"><label class="control-label" for="ueditor-label-'.$attrName.'">'.$showName.'</label><div style="width:'.$width.';display:inline-block;"><script id="ueditor-id-'.$attrName.'" type="text/plain" >'.$oldValue.'</script></div><div class="help-block"></div></div>';
        $html=$html."<script>\$(document).ready(function(){  ".$this->getUeObjectName().$attrName." = UE.getEditor('ueditor-id-".$attrName."', {
            toolbars: ueditorTools,
            textarea:'".$modelName."[".$attrName."]',
            autoHeightEnabled: ".$autoHeightEnabled.",
            initialFrameWidth:'100%',
            initialFrameHeight:".$height.",
            autoFloatEnabled: ".$autoFloatEnabled."
        });});</script>";
        return $html;
    }

    public function getPreImage($width,$height,$url){
        return '<img style="max-width:'.$width.'px;max-height:'.$height.'px;" src="'.$url.'" />';
    }


}