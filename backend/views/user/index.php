<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\base\BackendCommon;
/* @var $this yii\web\View */
/* @var $searchModel common\services\user\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
$common=new BackendCommon();
$controllerId=Yii::$app->controller->id;
$buttonList='';
if($common->checkButtonAuth($mainAuthJson,$controllerId,'view',null)==true){ $buttonList.= '{view}'; }
if($common->checkButtonAuth($mainAuthJson,$controllerId,'update',null)==true){ $buttonList.= '{update}'; }
if($common->checkButtonAuth($mainAuthJson,$controllerId,'delete',null)==true){ $buttonList.= '{delete}'; }
?>
<nav class="breadcrumb">
    <i class="Hui-iconfont">&#xe67f;</i> 首页
    <span class="c-gray en">&gt;</span> 用户管理
    <span class="c-gray en">&gt;</span> 用户列表
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<div class="page-container">

                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    
<?php if($common->checkButtonAuth($mainAuthJson,$controllerId,'create',null)==true){ ?>    <div class="cl pd-5 mt-20">
        <span class="l">
            <a href="javascript:;" onclick="backend_create_data('添加','<?= Yii::$app->urlManager->createUrl(''.$controllerId.'/create') ?>',layerOpenWindowWidth,layerOpenWindowHeight)" class="btn btn-primary radius">
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

['class'=>'yii\grid\DataColumn','value'=>'id','label' => 'ID'],
['class'=>'yii\grid\DataColumn','value'=>'name','label' => '昵称'],
['class'=>'yii\grid\DataColumn','value'=>'login_password','label' => '密码'],
['class'=>'yii\grid\DataColumn','value'=>'mobile','label' => '手机号'],
['class'=>'yii\grid\DataColumn','value'=>'qq','label' => 'QQ'],
['class'=>'yii\grid\DataColumn','value'=>'truename','label' => '真名'],
['class'=>'yii\grid\DataColumn','value'=>'account','label' => '用户名'],
['class'=>'yii\grid\DataColumn','value'=>'email','label' => '邮箱'],
['class'=>'yii\grid\DataColumn','value'=>'wx_openid','label' => '微信OPENID'],
['class'=>'yii\grid\DataColumn','value'=>'wx_unionid','label' => '微信unionid'],
['class'=>'yii\grid\DataColumn','value'=>'add_time','label' => '注册时间'],
['class'=>'yii\grid\DataColumn','value'=>'reg_ip','label' => '注册IP'],
['class'=>'yii\grid\DataColumn','value'=>'last_login_ip','label' => '最后登录IP'],
['class'=>'yii\grid\DataColumn','value'=>'token','label' => 'token'],
['class'=>'yii\grid\DataColumn','value'=>'token_out_time','label' => 'token_过期时间'],
['class'=>'yii\grid\DataColumn','value'=>'last_login_time','label' => '最后登录时间'],
['class'=>'yii\grid\DataColumn','value'=>'head_portrait','label' => '头像'],
['class'=>'yii\grid\DataColumn','value'=>'birthday','label' => '生日'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return empty($data->sexPredefine[$data['sex']])!=true?$data->sexPredefine[$data['sex']]:'';},'label' => '性别'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return empty($data->inviterUserRecord->name)!=true?$data->inviterUserRecord->name:'';},'label' => '邀请人'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return empty($data->addAdminRecord->nickname)!=true?$data->addAdminRecord->nickname:'';},'label' => '添加人'],
['class'=>'yii\grid\DataColumn','value'=>'introduce','label' => '自我介绍'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return empty($data->statusPredefine[$data['status']])!=true?$data->statusPredefine[$data['status']]:'';},'label' => '状态'],
['class'=>'yii\grid\DataColumn','value'=>function($data){   return empty($data->typePredefine[$data['type']])!=true?$data->typePredefine[$data['type']]:'';},'label' => '类型'],
            ['class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => $buttonList,
                'buttons' => [
    'view' => function ($url, $model, $key) {
    return '<a title="详情" href="javascript:;" onclick="backend_view_data(\'查看详情\',\''.$url.'\',this,\''.$key.'\',layerOpenWindowWidth,layerOpenWindowHeight)" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe665;</i></a>'; },
                'update' => function ($url, $model, $key) {
    return '<a title="编辑" href="javascript:;" onclick="backend_update_data(\'编辑\',\''.$url.'\',this,\''.$key.'\',layerOpenWindowWidth,layerOpenWindowHeight)" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>'; },
    'delete' => function ($url, $model, $key) {
    return '<a title="删除" href="javascript:;" clickDelete="0" onclick="backend_delete_data(this,\''.$key.'\',\''.$model->name.'\',\''.$url.'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>'; },
                ],
    ],
        ],
    ]); ?>

</div>
<script type="text/javascript">
 //自定义JS内容
</script>
