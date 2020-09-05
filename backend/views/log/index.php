<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\BackendCommon;
/* @var $this yii\web\View */
/* @var $searchModel common\services\log\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统日志';
$this->params['breadcrumbs'][] = $this->title;
$common=new BackendCommon();
$controllerId=Yii::$app->controller->id;
$buttonList='';
if($common->checkButtonAuth($mainAuthJson,$controllerId,'view',null)==true){ $buttonList.= '{view}'; }
//if($common->checkButtonAuth($mainAuthJson,$controllerId,'update',null)==true){ $buttonList.= '{update}'; }
//if($common->checkButtonAuth($mainAuthJson,$controllerId,'delete',null)==true){ $buttonList.= '{delete}'; }
?>
<nav class="breadcrumb index-nav-list">
    <i class="Hui-iconfont">&#xe67f;</i> 首页
    <span class="c-gray en">&gt;</span> 系统日志管理
    <span class="c-gray en">&gt;</span> 系统日志列表
    <a class="btn btn-success radius r operation-reload-icon" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<style>
    .system-log{
        width: 95%;
        max-height: 60px;
        overflow: auto;
    }
    .dataTables_wrapper table tr th,td:nth-of-type(6){
        max-width: 500px;
    }
</style>
<div class="page-container">

                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    
<?php if($common->checkButtonAuth($mainAuthJson,$controllerId,'create',null)==true){ ?>    <div class="cl pd-5 mt-20">
        <!--<span class="l">
            <a href="javascript:;" onclick="backend_create_data('添加','<?/*= Yii::$app->urlManager->createUrl(''.$controllerId.'/create') */?>',layerOpenWindowWidth,layerOpenWindowHeight)" class="btn btn-primary radius operation-add-icon">
                <i class="Hui-iconfont">&#xe600;</i> 添加
            </a>
        </span>-->
    </div>
<?php } ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
    'layout'=>'<div>{items}</div><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">{summary}</div><div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">{pager}</div></div>',
    'options'=>['class' => 'dataTables_wrapper','id'=>'DataTables_Table_0_wrapper'],
    'tableOptions'=>['class'=>'table table-border  table-bordered table-striped table-bg ','id'=>'DataTables_Table_0','role'=>'grid','aria-describedby'=>"DataTables_Table_0_info"
    ],
        'pager'=>[
            //'options'=>['class'=>'hidden'],//关闭分页
            'firstPageLabel'=>"首页",
            'prevPageLabel'=>'上一页',
            'nextPageLabel'=>'下一页',
            'lastPageLabel'=>'尾页',
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn',
            //    'header'=>'序号',
            //],

['class'=>'yii\grid\DataColumn','value'=>'id','label' => 'ID'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->levelPredefine[$data['level']]??'';},'label' => '等级'],
            ['class'=>'yii\grid\DataColumn','value'=>'category','label' => '分类标记'],
            ['class'=>'yii\grid\DataColumn','value'=>function($data){ return date('Y-m-d H:i:s',intval($data->log_time)); },'label' => '记录时间'],
            ['class'=>'yii\grid\DataColumn','value'=>'prefix','label' => '前缀'],
            ['class'=>'yii\grid\DataColumn','value'=>function($data){ return '<pre class="system-log">'.$data->message.'</pre>'; },'format' =>'html','label' => '内容'],
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => $buttonList,
                'buttons' => [
    'view' => function ($url, $model, $key) {
    return '<a title="详情" href="javascript:;" onclick="backend_view_data(\'查看详情\',\''.$url.'\',this,\''.$key.'\',layerOpenWindowWidth,layerOpenWindowHeight)" class="ml-5 operation-icon operation-view-icon" style="text-decoration:none"><i class="Hui-iconfont">&#xe665;</i></a>'; },
                'update' => function ($url, $model, $key) {
    return '<a title="编辑" href="javascript:;" onclick="backend_update_data(\'编辑\',\''.$url.'\',this,\''.$key.'\',layerOpenWindowWidth,layerOpenWindowHeight)" class="ml-5 operation-icon operation-update-icon" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>'; },
    'delete' => function ($url, $model, $key) {
    return '<a title="删除" href="javascript:;" clickDelete="0" onclick="backend_delete_data(this,\''.$key.'\',\''.$model->id.'\',\''.$url.'\')" class="ml-5 operation-icon operation-del-icon" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>'; },
                ],
    ],
        ],
    ]); ?>

</div>
<script type="text/javascript">
 //自定义JS内容
</script>
