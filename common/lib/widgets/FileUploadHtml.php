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

class FileUploadHtml
{
    public function getLinkScript(){
        echo '<link rel="stylesheet" type="text/css" href="/static/uploadify5/uploadifive.css?253"><script type="text/javascript" src="/static/uploadify5/jquery.uploadifive.min.js"></script>';
    }

    public function createFileUpload($model,$attrName,$showName='',$otherArr=[]){

        $reflector = new ReflectionClass($model);
        $modelName = $reflector->getShortName();
        $oldValue = '';
        $width = $otherArr['width']??"200px";
        $height = $otherArr['height']??"200px";
        $value = '';
        $required = empty($otherArr['required'])!=true?"required":"";
        $hide_input = $otherArr['hide_input']??0; //控制是否在当前目录下显示input,主要用户控制字段的错误提示 上传图片为必填时使用此字段
        $showInputStr = ''; //显示input

        if($hide_input!=1){
            $showInputStr = '<input type="hidden" name="'.$modelName.'['.$attrName.']" value="'.$value.'" id="filename-'.$attrName.'">';
        }

        if(empty($model->$attrName)!=true){
            $oldValue = '<a class="clearuploadimg" onclick="reUpLoadImg(\''.$attrName.'\');" ></a><img style="max-width:'.$width.';max-height:'.$height.';" src="'.$model->$attrName.'" />';
            $value = $model->$attrName;
        }


        $html = '<div class="form-group form-group-upload field-'.$attrName.' has-success '.$required.'"><label class="control-label" for="model-'.$attrName.'">'.$showName.'</label><div class="form-group-role-right" style="display:inline-block;"><div id="queue_'.$attrName.'"></div><div class="upload-image-pre" style="display: inline-block;" id="uploadimgpre_'.$attrName.'">'.$oldValue.'</div>'.$showInputStr.'<a id="file_upload_'.$attrName.'"></a><div class="help-block"></div></div></div>';
        $html=$html."<script>\$(document).ready(function(){\$('#file_upload_".$attrName."').uploadifive({
            'buttonText': '选择图片',
            'buttonClass':'btn btn-primary-outline radius uploadfive-btn',
            'auto'             : true,
            'formData'         : {
                'timestamp' : '',
                'token'     : ''
            },
            'queueID'          : 'queue_".$attrName."',
            'multi':false,
            'removeCompleted':true,
            'fileTypeExts' : '*.gif; *.jpg; *.png',
            'uploadScript'     : '/upload/ajaxupload.html',
            'onUploadComplete' : function(file, data) {
                $(\"#article-".$attrName."\").val('');
                var dataobj = eval('(' + data + ')');
                if(dataobj.code==200){
                    $(\"#uploadimgpre_".$attrName."\").html('<a class=\"clearuploadimg\" onclick=\"reUpLoadImg(\'".$attrName."\');\" ></a><img style=\"max-width:{$width};max-height:{$height};\" src=\"'+dataobj.data.url+'\" />');
                    $(\"#filename-".$attrName."\").val(dataobj.data.url);
                }else{
                    layer.msg(dataobj.msg,{icon:2,time:5000});
                }
            }
        });});</script>";
        return $html;
    }

    public function getHideInputId($idStr){
        return 'filename-'.$idStr;
    }

}