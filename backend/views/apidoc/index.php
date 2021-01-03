<style>
    .flow-title {
        position: fixed;
        right: 20px;
        top: 30px;
        z-index: 999;
    }

    .method_block {
        margin-bottom: 20px;
    }

    .dropDown-menu .current {
        background-color: #cccccc;
    }

    .showNav div {
        margin-bottom: 10px;
        font-size: 14px;
    }
</style>
<div class="page-container" id="showAllCentent">
    <div class="col-xs-3">
        <h2>接口模块导航</h2>
        <ul class="dropDown-menu menu radius box-shadow">
            <li><a class="<?php if ($cname == '') {
                    echo 'current';
                } ?>" href="/apidoc/index.html?cname=">接口通用描述</a></li>
            <?php if (!empty($docList)) {
                foreach ($docList as $key => $item) {
                    ?>
                    <li><a class="<?php if ($key == $cname) {
                            echo 'current';
                        } ?>"
                           href="/apidoc/index.html?cname=<?php echo $key; ?>"><?php echo $key . '=' . $item['description']; ?></a>
                    </li>
                    <?php
                }
            } ?>
        </ul>
    </div>
    <div class="col-xs-7">
        <?php if ($cname == '') {
            ?>
            <h3>接口通用描述</h3>
            <div>
                测试接口地址：<?php echo $apiRootUrl; ?><br/><br/>
                生产接口地址：https://XXXXXREPLACEXXXXX.com/<br/><br/>
                传参方式：POST<br/><br/>
                URL地址：接口地址+模块名称+接口名称 例如：<?php echo $apiRootUrl; ?>controler/action
                <br/><br/>
                返回类型：<br/>

                code = 返回状态码，200 成功，大于200即为失败，弹出错误提示
                401为权限验证失败需要进行重新登录,
                402为token过期调用续签接口获取新的token,
                422为获取游客token并重试上一个业务接口<br/>

                <br/>
                msg = 返回文本描述，code=200 为成功描述 例如：操作成功，code>200 失败描述例如：登陆失败<br/>
                <br/>
                data = 返回数据对象<br/>
                <br/>
                data 无数据:{"code":200,"msg":"success","data":{}}<br/>
                <br/>
                data 单数据对象:{"code":200,"msg":"success","data":{"a":11,"b":"22"}}<br/>
                <br/>
                data 数组对象:{"code":200,"msg":"success。","data":[{"aa":1,"bb":2},{"cc":3,"dd":4}]}<br/>
                <br/><br/>

                前端接口调用封装示例：
                <pre>
                yesCode = 200;
                function callApi(url,data){
                    $.ajax(){
                       success: return data;
                       fail:alert('服务器连接异常,请稍后重试');
                       fail:alert('网络异常');
                    };
                }

                function saveTokenUserType(token,userType){
                    //保存token和userType到本地,返回的token可能是用户的也可能是游客的所有每次更新token均需要更新usertype
                    return true;
                }

                function clearToken(){
                    return true;
                }


                function getDeviceInfo(){
                    return {device_type:1,device_code:'123123',system:'ios',model:'ipod'};
                }

                function callProcess(apiurl,paramsData,reTry=0){

                    token = local.token
                    if(token==''){ //本地或cookie没有token，调用account/visitortoken 获取游客token
                        data = callApi('account/visitortoken',getDeviceInfo());
                        if(data.code==yesCode){
                            token = data.token
                            saveReturn = saveToken(token); //保存token到本地
                            if(saveReturn!=true){
                                alert("没有保存内容到本地权限，重新获取权限");
                                return false;
                            }
                        }else{
                            alert(data.msg);
                            return;
                        }
                    }

                    if(reTry>0 && token==''){
                        //已经进行重试但token任然为空不在继续防止无效循环，给与提示
                        clearToken();
                        alert("服务器连接异常,请稍后重试");
                        return false;
                    }

                    data = callApi(apiurl,paramsData);
                    if(data.code==yesCode){
                        return data;
                    }else if(data.code==401){//去登录
                        //尚未登录跳转登录页面
                    }else if(data.code==402){//token 过期进行续签
                        if(reTry>0){
                            //重试中不在续签直接抛出异常，防止死循环
                            alert("服务器连接异常，请稍后再试");
                            return false;
                        }
                        renewalData = callApi('account/renewal',getDeviceInfo());
                        if(renewalData.code==yesCode){
                            if(renewalData.same!=1){//防止无意义刷新返回值相同不对本地内容进行处理
                                token = renewalData.token
                                saveReturn = saveToken(token); //保存token到本地
                                if(saveReturn!=true){
                                    alert("没有保存内容到本地权限，重新获取权限");
                                    return false;
                                }
                            }
                            callProcess(url,paramsData,reTry+1);//递归重试
                        }else if(data.code==401){
                            //尚未登录跳转登录页面
                        }else{
                            //其余情况输出错误提示
                            alert(data.msg);
                        }

                    }else if(data.code==422){ //获取游客token重试
                            //本地token无效清空本地token
                            clearToken();
                            callProcess(url,paramsData,reTry+1);//递归重试
                    }else{
                        //其余情况输出错误提示
                        alert(data.msg);
                    }


                }
                </pre>

            </div>
            <?php
        } else {
            ?>
            <div class="showContent">

                <div class="showMethodList" style="margin-bottom: 1000px;">
                    <?php
                    if (!empty($docList[$cname]['methods'])) {
                        $number = 0;
                        foreach ($docList[$cname]['methods'] as $key => $m) {
                            $number++;
                            ?>
                            <div class="method_block" id="id=show_method_<?php echo $key; ?>">
                                <h4 style="color:#000000;"><span
                                            class="label label-secondary radius"><?php echo $number; ?></span>.<?php echo $m['tags']['description']; ?>
                                </h4>
                                <span style="margin-right: 20px;">URL地址:</span><?php echo $apiRootUrl . $cname . '/' . $key; ?>
                                <a style="margin-left: 10px;" target="_blank"
                                   href="/apitest/index.html?c=<?php echo $cname . '&a=' . $key; ?>"
                                   class="label label-warning radius">点击测试</a>

                                <a style="margin-left: 10px;"
                                   href="javascript:void(0);"
                                   onclick="copyToClipboard('<?php echo $cname . '/' . $key ?>')"
                                   class="label label-success radius">点击复制C/A</a>

                                <h4><?php if (!empty($m['tags']['notes'])) {
                                        echo $m['tags']['notes'];
                                    } ?></h4>
                                <div class="show_params">
                                    <?php
                                    if (!empty($m['tags']['param'])) {
                                        ?>
                                        <h4>参数列表:</h4>
                                        <?php
                                        if (is_array($m['tags']['param']) === false) {
                                            echo $m['tags']['param'];
                                        } else {
                                            ?>
                                            <div>
                                                <table class="table table-border table-bg table-bordered radius table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>参数名</th>
                                                        <th>类型</th>
                                                        <th>描述</th>
                                                        <th>是否必填</th>
                                                        <th>默认值</th>
                                                    </tr>
                                                    </thead>
                                                    <?php
                                                    foreach ($m['tags']['param'] as $p) {
                                                        ?>
                                                        <tr>
                                                            <td><?php if (!empty($p['name'])) {
                                                                    echo $p['name'];
                                                                } ?></td>
                                                            <td><?php if (!empty($p['type'])) {
                                                                    echo $p['type'];
                                                                } ?></td>
                                                            <td><?php if (!empty($p['desc'])) {
                                                                    echo $p['desc'];
                                                                } ?></td>
                                                            <td><?php if (!empty($p['require'])) {
                                                                    echo '是';
                                                                } else {
                                                                    echo '否';
                                                                } ?></td>
                                                            <td><?php if (!empty($p['default'])) {
                                                                    echo $p['default'];
                                                                } else {
                                                                    echo '无';
                                                                } ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                            <?php
                                        }

                                    }
                                    ?>

                                </div>

                                <div class="show_return">
                                    <?php
                                    if (!empty($m['tags']['return']['yes'])) {
                                        ?>
                                        <h4>返回成功参数:</h4>
                                        <div><?php if (is_array($m['tags']['return']['yes'])) {
                                                foreach ($m['tags']['return']['yes'] as $re) {
                                                    echo '<pre class="json-show-p">' . json_encode($re) . '</pre>';
                                                }
                                            } else {
                                                echo '<pre>' . $m['tags']['return']['yes'] . '</pre>';
                                            } ?></div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if (!empty($m['tags']['return']['no'])) {
                                        ?>
                                        <h4>返回失败参数:</h4>
                                        <div><?php if (is_array($m['tags']['return']['no'])) {
                                                foreach ($m['tags']['return']['no'] as $re) {
                                                    echo '<pre class="json-show-p">' . json_encode($re) . '</pre>';
                                                }
                                            } else {
                                                echo '<pre>' . $m['tags']['return']['no'] . '</pre>';
                                            } ?></div>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>


                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php

        } ?>

    </div>
    <div class="col-xs-2 flow-title" <?php if ($cname == '') {
        echo 'style = "display:none;"';
    } ?>>
        <h4 style="color: #0d71bb;"><?php echo $cname; ?>接口列表</h4>
        <div class="showNav">
            <?php
            if (!empty($docList[$cname]['methods'])) {
                $number = 0;
                foreach ($docList[$cname]['methods'] as $key => $m) {
                    $number++;
                    ?>
                    <div><?php echo $number; ?>.<a
                                href="#id=show_method_<?php echo $key; ?>"><?php echo $m['tags']['description'] ?>
                            - (<?php echo $key; ?>)</a></div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".json-show-p").each(function () {
            var result = JSON.stringify(JSON.parse($(this).text()), null, 2);
            $(this).text(result);
        });
    });


</script>