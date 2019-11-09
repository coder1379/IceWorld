<style>
    .method_block{
        margin-bottom: 20px;
    }
    
    .dropDown-menu .current{
        background-color: #cccccc;
    }

    .showNav div{
        margin-bottom: 10px;
        font-size: 18px;
    }
</style>
<div class="page-container" id="showAllCentent">
    <a style="position: fixed;right: 20px;bottom: 30px;z-index:999;" href="javascript:void(0);" onclick="window.scrollTo(0,0);">点击回到顶部</a>
    <div class="col-xs-3">
        <h2>接口模块导航</h2>
       <ul class="dropDown-menu menu radius box-shadow">
           <li><a class="<?php if($cname==''){ echo 'current'; } ?>" href="/apidoc/index.html?cname=">接口通用描述</a></li>
                <?php if(!empty($docList)){
                    foreach ($docList as $key=>$item) {
                        ?>
                      <li><a class="<?php if($key==$cname){ echo 'current'; } ?>" href="/apidoc/index.html?cname=<?php echo $key; ?>"><?php echo $key.'='.$item['description']; ?></a></li>
                        <?php
                    }
                } ?>
                </ul>
    </div>
    <div class="col-xs-9">
       <?php if($cname==''){
           ?>
           <h3>接口通用描述</h3>
           <div>
            接口地址：****/<br/>
            传参方式：POST<br/>
            接口完整地址：接口地址+模块名称+接口名称 例如：http://***.com/baseapi/network
               <br/><br/>
               返回类型：<br/>
               code = 返回状态码，200 成功，非200即为失败，弹出错误提示，401为权限验证失败需要进行重新登录<br/>
               msg = 返回文本描述，code=200 为成功描述 例如：操作成功，code!=200 失败描述例如：登陆失败<br/>
               data = 返回数据对象<br/>
               &nbsp;&nbsp;data 无数据:{"code":200,"msg":"","data":{}}<br/>
               &nbsp;&nbsp;data 单数据对象:{"code":10001,"msg":"密码格式错误。","data":{"a":11,"b":"22"}}<br/>
               &nbsp;&nbsp;data 数组对象:{"code":10001,"msg":"密码格式错误。","data":[{"aa":1,"bb":2},{"cc":3,"dd":4}]}<br/>

               <br/><br/>
               说明：*****


           </div>
        <?php
       }else{
           ?>
        <div class="showContent">
            <h2 style="color: #0d71bb;">模块内接口列表 模块名称:<?php echo $cname; ?></h2>
            <div class="showNav">
        <?php
            if(!empty($docList[$cname]['methods'])){
                $number = 0;
                foreach ($docList[$cname]['methods'] as $key=>$m){
                    $number++;
                    ?>
                    <div><?php echo $number; ?>.<a href="#id=show_method_<?php echo $key; ?>"><?php echo $m['tags']['description'] ?>-(<?php echo $key; ?>)</a></div>
                    <?php
                }
            }
           ?>
            </div>
            <div class="showMethodList" style="margin-bottom: 1000px;">
                <?php
                if(!empty($docList[$cname]['methods'])){
                    $number = 0;
                    foreach ($docList[$cname]['methods'] as $key=>$m){
                        $number++;
                        ?>
                        <div class="method_block" id="id=show_method_<?php echo $key; ?>">
                            <h3 style="color: #0f9ae0;"><?php echo $number; ?>.<?php echo $m['tags']['description']; ?> 接口名称:<?php echo $key; ?></h3>
                            <div class="show_params">
                                <?php
                                if(!empty($m['tags']['param'])){
                                    ?>
                                    <h4>参数列表:</h4>
                                    <?php
                                    if(is_array($m['tags']['param'])===false){
                                        echo $m['tags']['param'];
                                    }else{
                                    foreach ($m['tags']['param'] as $p){
                                        ?>
                                            <div><?php echo $p; ?></div>
                                        <?php
                                    }
                                    }

                                }
                                ?>

                            </div>

                            <div class="show_return">
                                <?php
                                if(!empty($m['tags']['return'])){
                                    ?>
                                    <h4>返回参数:</h4>
                                    <div><?php echo $m['tags']['return']; ?></div>
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
</div>
