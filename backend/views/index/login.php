<div class="header" style="display: none;"></div>
<div class="loginWraper" style="background-size: 100%;">
    <div class="loginBox">
        <form class="form form-horizontal" id="loginform" action="<?php echo Yii::$app->urlManager->createUrl('index/login'); ?>" method="post">
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
                <div class="formControls col-xs-8">
                    <input id="backendloginadminname" name="backendloginadminname" value="<?php echo Yii::$app->request->post("backendloginadminname"); ?>" type="text" placeholder="用户名" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                <div class="formControls col-xs-8">
                    <input id="backendloginadminpassword" value="<?php echo Yii::$app->request->post("backendloginadminpassword"); ?>" name="backendloginadminpassword" type="password" placeholder="密码" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input id="backendcaptchacode" value="<?php echo Yii::$app->request->post("backendcaptchacode"); ?>" name="backendcaptchacode" class="input-text size-L" type="text" placeholder="验证码"  style="width:150px;">
                    <?php echo yii\captcha\Captcha::widget(['name'=>'captchaimg','captchaAction'=>'index/captcha','imageOptions'=>['id'=>'captchaimg','name'=>'captchaimg', 'title'=>'点击更换', 'alt'=>'点击更换', 'style'=>'cursor:pointer;'],'template'=>'{image}']); ?>
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <label for="backendonline">
                        <input type="checkbox" name="backendonline" id="backendonline" value="1">
                        自动登录</label>
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input id="loginbtn" type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;" />
                    <input id="clearall" type="button" value="&nbsp;清&nbsp;&nbsp;&nbsp;&nbsp;空&nbsp;" class="btn btn-default radius size-L" />
                </div>
            </div>
        </form>
    </div>
</div>
<div class="footer" style="font-size:20px;"><?php echo Yii::$app->params['admin_site_show_name']; ?></div>
<script>
    var errorshow='<?php if(empty($error)!=true){ echo $error; } ?>';
    $(document).ready(function(){
        if(errorshow!=''){
            layer.alert(errorshow);
        }

        $("#clearall").click(function () {
            $("#backendloginadminname").val("");
            $("#backendloginadminpassword").val("");
            $("#backendcaptchacode").val("");
        });

        $("#loginbtn").click(function(){
            if($.trim($("#backendloginadminname").val())==''){
                layer.tips('请输入用户名！','#backendloginadminname',{tips: [1, '#dd514c']});
                $("#backendloginadminname").focus();
                return false;
            }

            if($.trim($("#backendloginadminpassword").val())==''){
                layer.tips('请输入密码！','#backendloginadminpassword',{tips: [1, '#dd514c']});
                $("#backendloginadminpassword").focus();
                return false;
            }

            if($.trim($("#backendcaptchacode").val())==''){
                layer.tips('请输入验证码！','#backendcaptchacode',{tips: [1, '#dd514c']});
                $("#backendcaptchacode").focus();
                return false;
            }
        });

    });
    
</script>