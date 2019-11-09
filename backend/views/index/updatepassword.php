<?php
use common\BackendCommon;
$common=new BackendCommon();
?>
<style>
    .password-top{
        padding-top: 100px;
    }
    #loginform{
        width: 80%;
        margin: auto;
    }
</style>
<div class="password-top">

    <form class="form form-horizontal" id="loginform"  method="post">
        <div class="row cl">
            <label class="form-label col-xs-3">旧密码</label>
            <div class="formControls col-xs-8">
                <input name="old_password" id="old_password" value="" type="password" placeholder="旧密码" class="input-text size-L">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3">新密码</label>
            <div class="formControls col-xs-8">
                <input name="new_password" id="new_password" value="" type="password" placeholder="新密码" class="input-text size-L">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3">确认新密码</label>
            <div class="formControls col-xs-8">
                <input name="confirm_password" id="confirm_password" value="" type="password" placeholder="确认新密码" class="input-text size-L">
            </div>
        </div>

        <div class="row cl">
            <div class="formControls col-xs-8 col-xs-offset-3">
                <input id="loginbtn" clickDelete=0 type="button" class="btn btn-success radius size-L" value="修改" />
            </div>
        </div>
    </form>

</div>
<script>
    $(document).ready(function(){


        $("#loginbtn").click(function(){
            if($.trim($("#old_password").val())==''){
                layer.tips('请输入旧密码！','#old_password',{tips: [1, '#dd514c']});
                $("#old_password").focus();
                return false;
            }

            if($.trim($("#new_password").val())==''){
                layer.tips('请输入新密码！','#new_password',{tips: [1, '#dd514c']});
                $("#new_password").focus();
                return false;
            }

            if($.trim($("#confirm_password").val())==''){
                layer.tips('请输入确认新密码！','#confirm_password',{tips: [1, '#dd514c']});
                $("#confirm_password").focus();
                return false;
            }


            $(this).attr('clickDelete',1);
            $.ajax({
                type:"POST",
                url:'<?php echo Yii::$app->urlManager->createUrl("index/updatepassword"); ?>',
                data:$("#loginform").serialize(),
                datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
                beforeSend:function(){},
                success:function(data){
                    var dataObj = eval('(' + data + ')');
                    $(this).attr('clickDelete',0);
                    if(200==dataObj.code){
                        layer.alert(dataObj.msg);
                    }else{
                        layer.msg(dataObj.msg,{icon:2,time:5000});
                    }
                },
                complete: function(XMLHttpRequest, textStatus){

                },
                error: function(){
                    $(this).attr('clickDelete',0);
                    layer.msg(systemalert,{icon:2,time:5000});
                }
            });

        });

    });

</script>