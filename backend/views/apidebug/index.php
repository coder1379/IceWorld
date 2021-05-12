<nav class="breadcrumb">
    <i class="Hui-iconfont">&#xe67f;</i> 首页
    <span class="c-gray en">&gt;</span> ApiDebug记录
    <span class="c-gray en">&gt;</span> ApiDebug记录列表
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<div class="page-container" style="position:absolute;width: 100%;height: 92%;padding: 0;margin: 0;top: 30px;">
    <div style="margin-top: 10px;width: 100%;height: 100%;">
        <iframe id="debugIframe" src="<?php echo Yii::$app->params['api_root_url'] ?>debug/?tempapidebugseaveid=<?php echo Yii::$app->params['api_debug_access_cookie']; ?>" style="border: 0;padding: 0;margin: 0;width: 100%;height: 100%;"></iframe>
    </div>

</div>
<script>
</script>