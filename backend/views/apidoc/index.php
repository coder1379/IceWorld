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

                code = 返回状态码，200 成功，大于200即为失败，弹出错误提示，401为权限验证失败需要进行重新登录<br/>
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
                                <a style="margin-left: 20px;" target="_blank"
                                   href="/apitest/index.html?c=<?php echo $cname . '&a=' . $key; ?>"
                                   class="label label-warning radius">点击测试</a>
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