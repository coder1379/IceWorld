/* -----------H-ui前端框架-------------
后台公用
*
*/
var systemalert="程序错误，请重试！";
var layerOpenWindowWidth = '80%';
var layerOpenWindowHeight = '90%';

var ueditorTools = [
    [
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
        'superscript', //上标
        'subscript', //下标
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
        'imagenone', //默认
        'imageleft', //左浮动
        'imageright', //右浮动
        'imagecenter', //居中
        'lineheight', //行间距
        'edittip ', //编辑提示
        'customstyle', //自定义标题
        'undo', //撤销
        'redo', //重做
        'inserttable', //插入表格
        'insertrow', //前插入行
        'insertcol', //前插入列
        'mergeright', //右合并单元格
        'mergedown', //下合并单元格
        'deleterow', //删除行
        'deletecol', //删除列
        'splittorows', //拆分成行
        'splittocols', //拆分成列
        'splittocells', //完全拆分单元格
        'deletecaption', //删除表格标题
        'inserttitle', //插入标题
        'mergecells', //合并多个单元格
        'edittable', //表格属性
        'edittd', //单元格属性
        'deletetable', //删除表格
        'cleardoc', //清空文档
        'insertparagraphbeforetable', //"表格前插入行"
        //'map', //Baidu地图
        'source', //源代码
        'preview', //预览
    ]
];

function layer_show_common(title,url,w,h,flash){
    if (title == null || title == '') {
        title=false;
    };
    if (url == null || url == '') {
        url="404.html";
    };
    if (w == null || w == '') {
        w=800;
    };
    if (h == null || h == '') {
        h=($(window).height() - 50);
    };
    layer.open({
        type: 2,
        area: [w,h],
        maxmin: true,
        shade:0.4,
        fixed:false,
        closeBtn:2,
        title: title,
        content: url,
        scrollbar:false,
        end: function () {
            if(flash==1){
                location.reload();
            }

        }
    });
}

//创建
function backend_create_data(title,url,w,h){
    layer_show_common(title,url,w,h,1);
}

//删除
function backend_delete_data(obj,id,clickName,dltlink){
    if(clickName==''){
        clickName='此数据';
    }else{
        clickName='‘'+clickName+'’';
    }
    layer.confirm('确认要删除'+clickName+'吗？',function(index){
        if($(this).attr('clickDelete')==1){
            layer.msg('删除处理中...',{icon:1,time:2000});
            return;
        }
        $(this).attr('clickDelete',1);
        $.ajax({
            type:"POST",
            url:dltlink,
            data:{id:id},
            datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
            beforeSend:function(){},
            success:function(ajaxData){
               var data = $.parseJSON(ajaxData);
                $(this).attr('clickDelete',0);
                if(200==data.code){
                    layer.msg(data.msg,{icon:1,time:2000});
                    $(obj).parents("tr").remove();
                }else{
                    layer.msg(data.msg,{icon:2,time:5000});
                }
            },
            complete: function(XMLHttpRequest, textStatus){

            },
            error: function(){
                $(this).attr('clickDelete',0);
                layer.msg(systemalert,{icon:2,time:5000});
            }
        });
    },function(){
        $(this).attr('clickDelete',0);
    });
}

//修改
function backend_update_data(title,url,cobj,id,w,h){
    layer_show_common(title,url,w,h,1);
}

//显示
function backend_view_data(title,url,cobj,id,w,h){
    layer_show_common(title,url,w,h);
}

//复制到剪切板
function copyToClipboard (text) {
    if(text.indexOf('-') !== -1) {
        var arr = text.split('-');
        text = arr[0] + arr[1];
    }
    var textArea = document.createElement("textarea");
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.width = '2em';
    textArea.style.height = '2em';
    textArea.style.padding = '0';
    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';
    textArea.style.background = 'transparent';
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();

    try {
        var successful = document.execCommand('copy');
        var msg = successful ? '成功复制到剪贴板' : '该浏览器不支持点击复制到剪贴板';
        alert(msg);
    } catch (err) {
        alert('该浏览器不支持点击复制到剪贴板请手动复制：'+text);
    }

    document.body.removeChild(textArea);
}

/**
 * 移除图片
 * @param removeImgId
 */
function reUpLoadImg(removeImgId){
    $("#uploadimgpre_"+removeImgId).html("");
    $("#filename-"+removeImgId).val("");
}
