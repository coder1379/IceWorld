var ueditorToolBars=[
    'fullscreen', //全屏
    //'template', //模板
    'simpleupload', //单图上传
    'link', //超链接
    'unlink', //取消链接
    'forecolor', //字体颜色
    'backcolor', //背景色
    'bold', //加粗
    'indent', //首行缩进
    'italic', //斜体
    'underline', //下划线
    'strikethrough', //删除线
    'fontborder', //字符边框
    'horizontal', //分隔线
    'removeformat', //清除格式
    //'superscript', //上标
    //'subscript', //下标
    'fontfamily', //字体
    'fontsize', //字号
    'paragraph', //段落格式
    'spechars', //特殊字符
    'justifyleft', //居左对齐
    'justifyright', //居右对齐
    'justifycenter', //居中对齐
    'justifyjustify', //两端对齐
    'insertorderedlist', //有序列表
    'insertunorderedlist', //无序列表
    'rowspacingtop', //段前距
    'rowspacingbottom', //段后距
    //'imagenone', //默认
    //'imageleft', //左浮动
    //'imageright', //右浮动
    'imagecenter', //居中
    'lineheight', //行间距
    'edittip ', //编辑提示
    'customstyle', //自定义标题
    'undo', //撤销
    'redo', //重做
    'inserttable', //插入表格
    'insertrow', //前插入行
    'insertcol', //前插入列
    //'mergeright', //右合并单元格
    //'mergedown', //下合并单元格
    'deleterow', //删除行
    'deletecol', //删除列
    //'splittorows', //拆分成行
    //'splittocols', //拆分成列
    //'splittocells', //完全拆分单元格
    'deletecaption', //删除表格标题
    'inserttitle', //插入标题
    'mergecells', //合并多个单元格
    //'edittable', //表格属性
    'edittd', //单元格属性
    //'deletetable', //删除表格
    //'cleardoc', //清空文档
    'insertparagraphbeforetable', //"表格前插入行"
    'source', //源代码
    'preview', //预览
    'wordimage', //图片转存
    'pasteplain', //纯文本粘贴模式
];

//只能输入金额
function entryDecimal(cobj) {
    var regex = /^\d+\.?\d{0,2}$/;
    if (!regex.test(cobj.value))
    {
        cobj.value = "";
        cobj.focus();
    }
}

//只能输入整数
function entryInt(cobj){
    var regex = /^\d+\.?\d{0}$/;
    if (!regex.test(cobj.value))
    {
        cobj.value = "";
        cobj.focus();
    }
}

function globalAlert(msg,callback) {
    if(callback!=null && callback!=undefined){
        layer.alert(msg,{btn:[alertMessageButtonConfirm],title:alertMessageTitle},function(){
            if(callback){
                callback();
            }
        });
    }else{
        layer.alert(msg,{btn:[alertMessageButtonConfirm],title:alertMessageTitle});
    }
}

function globalConfirm(msg,callback) {
    if(callback!=null && callback!=undefined){
        layer.confirm(msg,{btn:[alertMessageButtonYes,alertMessageButtonNo],title:alertMessageTitle},function(){
            if(callback){
                callback();
            }
        });
    }else{
        layer.confirm(msg,{btn:[alertMessageButtonYes,alertMessageButtonNo],title:alertMessageTitle});
    }
}

function globalPrompt(obj,callback) {
    if(obj['btn']!=null || obj['btn']==undefined){
        obj['btn']=[alertMessageButtonConfirm,alertMessageButtonCancel];
    }

    if(callback!=null && callback!=undefined){
        layer.prompt(obj,function(){
            if(callback){
                callback();
            }
        });
    }else{
        layer.prompt(obj);
    }


}