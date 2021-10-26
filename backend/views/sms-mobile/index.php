<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\base\BackendCommon;
/* @var $this yii\web\View */
/* @var $searchModel common\services\sms\SmsMobileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '短信记录';
$this->params['breadcrumbs'][] = $this->title;
$common=new BackendCommon();
$controllerId=Yii::$app->controller->id;
$buttonList='';
if($common->checkButtonAuth($mainAuthJson,$controllerId,'view',null)==true){ $buttonList.= '{view}'; }
if($common->checkButtonAuth($mainAuthJson,$controllerId,'update',null)==true){ $buttonList.= '{update}'; }
if($common->checkButtonAuth($mainAuthJson,$controllerId,'delete',null)==true){ $buttonList.= '{delete}'; }
?>
<nav class="breadcrumb index-nav-list">
    <i class="Hui-iconfont">&#xe67f;</i> 首页
    <span class="c-gray en">&gt;</span> 短信记录管理
    <span class="c-gray en">&gt;</span> 短信记录列表
    <a class="btn btn-success radius r operation-reload-icon" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<div class="page-container">

                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    
<?php if($common->checkButtonAuth($mainAuthJson,$controllerId,'create',null)==true){ ?>    <div class="cl pd-5 mt-20">
        <span class="l">
            <a href="javascript:;" onclick="backend_create_data('添加','<?= Yii::$app->urlManager->createUrl(''.$controllerId.'/create') ?>',layerOpenWindowWidth,layerOpenWindowHeight)" class="btn btn-primary radius operation-add-icon">
                <i class="Hui-iconfont">&#xe600;</i> 添加
            </a>
        </span>
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

['class'=>'yii\grid\DataColumn','attribute' => 'id'],
['class'=>'yii\grid\DataColumn','attribute' => 'name'],
['class'=>'yii\grid\DataColumn','attribute' => 'object_id'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->objectTypePredefine[$data['object_type']]??'';},'attribute' => 'object_type'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->userRecord->name??'';},'attribute' => 'user_id'],
['class'=>'yii\grid\DataColumn','attribute' => 'mobile'],
//['class'=>'yii\grid\DataColumn','attribute' => 'other_mobiles'],
//['class'=>'yii\grid\DataColumn','attribute' => 'content'],
//['class'=>'yii\grid\DataColumn','attribute' => 'params_json'],
['class'=>'yii\grid\DataColumn','value'=>function($data){ if($data->send_time>0){ return date('Y-m-d H:i:s',$data->send_time); }else{ return ''; } },'attribute' => 'send_time'],
['class'=>'yii\grid\DataColumn','attribute' => 'send_num'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->typePredefine[$data['type']]??'';},'attribute' => 'type'],
//['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->sendTypePredefine[$data['send_type']]??'';},'attribute' => 'send_type'],
//['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->smsTypePredefine[$data['sms_type']]??'';},'attribute' => 'sms_type'],
['class'=>'yii\grid\DataColumn','attribute' => 'template'],
//['class'=>'yii\grid\DataColumn','attribute' => 'feedback'],
//['class'=>'yii\grid\DataColumn','attribute' => 'remark'],
['class'=>'yii\grid\DataColumn','value'=>function($data){ if($data->add_time>0){ return date('Y-m-d H:i:s',$data->add_time); }else{ return ''; } },'attribute' => 'add_time'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return $data->statusPredefine[$data['status']]??'';},'attribute' => 'status'],
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => $buttonList,
                'buttons' => [
    'view' => function ($url, $model, $key) {
    return '<a title="详情" href="javascript:;" onclick="backend_view_data(\'查看详情\',\''.$url.'\',this,\''.$key.'\',layerOpenWindowWidth,layerOpenWindowHeight)" class="ml-5 operation-icon operation-view-icon" style="text-decoration:none"><i class="Hui-iconfont">&#xe665;</i></a>'; },
                'update' => function ($url, $model, $key) {
    return '<a title="编辑" href="javascript:;" onclick="backend_update_data(\'编辑\',\''.$url.'\',this,\''.$key.'\',layerOpenWindowWidth,layerOpenWindowHeight)" class="ml-5 operation-icon operation-update-icon" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>'; },
    'delete' => function ($url, $model, $key) {
    return '<a title="删除" href="javascript:;" clickDelete="0" onclick="backend_delete_data(this,\''.$key.'\',\''.$model->name.'\',\''.$url.'\')" class="ml-5 operation-icon operation-del-icon" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>'; },
                ],
    ],
        ],
    ]); ?>

</div>
<script type="text/javascript">
 //自定义JS内容
</script>
