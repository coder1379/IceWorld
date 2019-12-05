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

    .api-title{
        margin-top: 10px;
    }

    .pre-show-apitest{
        margin: 20px;
    }

</style>
<div class="page-container" id="showAllCentent">
    <a style="position: fixed;right: 20px;bottom: 30px;z-index:999;" href="javascript:void(0);" onclick="window.scrollTo(0,0);">点击回到顶部</a>
    <div class="col-xs-6">
        <h2 style="text-align: center;"><?php echo $apiData['actondesc']??''; ?></h2>
        <form class="form form-horizontal" id="form-api-test-1" style="">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>url：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $apiData['url']??''; ?>" placeholder="接口API" id="api_request_url_001">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><?php if($token==1){ echo '<span class="c-red">*</span>'; } ?>token：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="token" name="token">
                </div>
            </div>
            <?php

            if(!empty($apiData['params'])){
                foreach ($apiData['params'] as $p){
            ?>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><?php if(!empty($p['require']) && $p['require'] == '是'){ echo '<span class="c-red">*</span>'; } ?><?php echo $p['desc']??''; ?>(<?php echo $p['type']??''; ?>)：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input type="text" class="input-text" value="<?php echo $p['default']??''; ?>" placeholder="<?php echo $p['desc']??''; ?>" name="<?php echo $p['name']??''; ?>">
                        </div>
                    </div>
            <?php
                }

            } ?>

        </form>
        <div style="text-align: center;margin: 10px;">
            <button onclick="queryApi1(1);" class="btn btn-primary radius">执行</button>
        </div>
        <div style="text-align: center;">
            <pre class="pre-show-apitest" style="text-align: left;" id="show-code-01"></pre>
        </div>



    </div>
    <div class="col-xs-6">
        <h2 style="text-align: center;"><?php echo $apiData['actondesc']??''; ?></h2>
        <form class="form form-horizontal" id="form-api-test-2" style="">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>url：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $apiData['url']??''; ?>" placeholder="接口API" id="api_request_url_002">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><?php if($token==1){ echo '<span class="c-red">*</span>'; } ?>token：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="" placeholder="token" name="token">
                </div>
            </div>
            <?php

            if(!empty($apiData['params'])){
                foreach ($apiData['params'] as $p){
                    ?>
                    <div class="row cl">
                        <label class="form-label col-xs-4 col-sm-2"><?php if(!empty($p['require']) && $p['require'] == '是'){ echo '<span class="c-red">*</span>'; } ?><?php echo $p['desc']??''; ?>(<?php echo $p['type']??''; ?>)：</label>
                        <div class="formControls col-xs-8 col-sm-9">
                            <input type="text" class="input-text" value="<?php echo $p['default']??''; ?>" placeholder="<?php echo $p['desc']??''; ?>" name="<?php echo $p['name']??''; ?>">
                        </div>
                    </div>
                    <?php
                }

            } ?>

        </form>
        <div style="text-align: center;margin: 10px;">
            <button onclick="queryApi1(2);" class="btn btn-primary radius">执行</button>
        </div>
        <div style="text-align: center;">
            <pre class="pre-show-apitest" style="text-align: left;" id="show-code-02"></pre>
        </div>

    </div>
</div>
<script>
    function queryApi1(number) {
        if($("#api_request_url_00"+number).val().trim()==''){
            layer.alert('接口url不能为空.');
            return;
        }

        $(this).attr('clickDelete',1);
        $('#show-code-0'+number).text('执行中...');
        $.ajax({
            type:"POST",
            url:$("#api_request_url_00"+number).val().trim(),
            data:$("#form-api-test-"+number).serialize(),
            datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
            beforeSend:function(){},
            success:function(ajaxData){
                var result = JSON.stringify(JSON.parse(ajaxData), null, 2);
                $('#show-code-0'+number).text(result);
                $(this).attr('clickDelete',0);

            },
            complete: function(XMLHttpRequest, textStatus){

            },
            error: function(){
                $(this).attr('clickDelete',0);
                $('#show-code-0'+number).html(ajaxData);
            }
        });
    }
</script>