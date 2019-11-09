var uploadImgAddress="/upload/ajaxupload.html";
function noSetParameter(param){
	if(param=='' || param==null || typeof(param) == "undefined" || param=='undefined' || param==undefined){
		return true;
	}
	return false;
}

function reUpLoadImg(removeImgId){
	$("#"+removeImgId+"_img").hide();
	$("#"+removeImgId+"_demo_img").show();
	$("#"+removeImgId).val("");
	$("#uploadifive-"+removeImgId+"_upload_btn").show();

}


function createUploadFileObjectAuto(uploadContainer,uploadId,uploadName,uploadNameValue,cssNumber,uploadMessage,demoImg,callback){
    /*
    统一创建上传文件对象
    uploadContainer 容器 id 名 不含#
    uploadId 上传图片ID
    uploadName 名称 上传到后台 name
    uploadMessage 说明
    uploadNameValue 默认值
    demoImg 示例图
    callback 上传完成回调
    cssNumber 样式模板
    */
    if(noSetParameter(uploadMessage)==true){
        //uploadMessage="请上传图片";
        uploadMessage='';
    }

    if(noSetParameter(uploadNameValue)==true){
        uploadNameValue="";
    }

    if(noSetParameter(cssNumber)==true){
        cssNumber="";
    }


    var displayimgstr="";
    var displaydemoimgstr="display:none;";
    if(uploadNameValue==""){
        displayimgstr="display:none;";
        displaydemoimgstr="";
    }
    var uploadhtmlstr='<ul class="upload5_message_button'+cssNumber+'">'+
        '<li style="height: 0px;">'+
        '<input name="'+uploadName+'" type="hidden" class="'+uploadName+'_cls'+cssNumber+'" id="'+uploadId+'" value="'+uploadNameValue+'" />'+
        '<a href="javascript:void(0);" id="'+uploadId+'_upload_btn" class="uploadbtn_5'+cssNumber+'">拍照</a></li>'+
        '<li><div id="queue_upload_one"></div>';

    uploadhtmlstr+='<div id="'+uploadId+'_img" style="'+displayimgstr+'" class="upl5_load'+cssNumber+'">';
    if(uploadNameValue!=""){
        //var templupaststr="reUpLoadImg('"+uploadName+"');";
        uploadhtmlstr+='<a class="clearuploadimg" onclick="reUpLoadImg(\''+uploadId+'\');" ></a><img class="upd5_oderImg'+cssNumber+'" src="'+uploadNameValue+'" />';
    }
    uploadhtmlstr+='</div><div id="'+uploadId+'_demo_img" style="'+displaydemoimgstr+'" class="upl5_demoimage'+cssNumber+'">';
    if(noSetParameter(demoImg)==false){
        uploadhtmlstr+='<img class="upd5_oderImg'+cssNumber+'" src="'+demoImg+'" />';
    }
    var hideStr='';
    if(uploadMessage==''){
        hideStr= ' style="display:none;" ';
	}
    uploadhtmlstr+='</div></li>'+
        '<li '+hideStr+' class="upl5_message'+cssNumber+'">'+uploadMessage+'</li></ul>';
    $("#"+uploadContainer).append(uploadhtmlstr);

    $('#'+uploadId+'_upload_btn').uploadifive({
        'buttonText': '<a class="uploadBtnImg"></a>',
        'buttonClass':'uploadbtn_5'+cssNumber+'',
        'auto'             : true,
        'multi'        : true,
        'fileSizeLimit' : 10240,
        'queueID'      : false,
        'formData'         : {
            'ThumbType' : '7',
            'ThumbInfo' : '1'
        },
        'uploadScript'     : uploadImgAddress,
        'onUploadComplete' : function(file, data) {
            $('#uploadifive-'+uploadId+'_upload_btn-queue').html("");
            var dataObj=eval("("+data+")");
            if(dataObj.code==200)
            {
                //alert("上传成功");
                $('#'+uploadId+'').val(dataObj.data.url);
                $('#'+uploadId+'_img').html('<a class="clearuploadimg" onclick="reUpLoadImg(\''+uploadId+'\');" ></a><img class="upd5_oderImg'+cssNumber+'" src="'+dataObj.data.url+'" />');
                $("#uploadifive-"+uploadId+"_upload_btn").hide();
                $("#"+uploadId+"_img").show();
                $("#"+uploadId+"_demo_img").hide();
                try{
                    callback();
                }catch(e){}
            }
            else
            {
            	alert("上传失败");
            }
        }
    });
    if(uploadNameValue==""){
        $("#uploadifive-"+uploadId+"_upload_btn").attr("style","");
    }else{
        $("#uploadifive-"+uploadId+"_upload_btn").attr("style","display:none;");
    }




}
