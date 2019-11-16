<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

//获取表备注
$dbNameString = Yii::$app->db->dsn;
$dbName1=explode(';',$dbNameString);
$dbNameArr=explode('=',$dbName1[1]);
$dbName=$dbNameArr[1];
$tableNmae = $generator->getTableSchema()->fullName;

$tableCommentObj = Yii::$app->db->createCommand("select table_name,table_comment from information_schema.tables where table_schema = '".$dbName."' and table_name ='".$tableNmae."'")->queryOne();
$tableComment = $tableCommentObj['table_comment'];
if(empty($tableComment)){
    $tableComment=$tableCommentObj['table_name'];
}
//获取表备注

//下面语句为原来的$this->title
//$generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))))

echo "<?php\n";
?>
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\BackendCommon;
/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '<?= $tableComment ?>';
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
    <span class="c-gray en">&gt;</span> <?php echo $tableComment; ?>管理
    <span class="c-gray en">&gt;</span> <?php echo $tableComment; ?>列表
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
        <i class="Hui-iconfont">&#xe68f;</i>
    </a>
</nav>
<div class="page-container">

    <?php if(!empty($generator->searchModelClass)): ?>
        <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? " " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php endif; ?>

<?php
echo "<?php if(\$common->checkButtonAuth(\$mainAuthJson,\$controllerId,'create',null)==true){ ?>";
?>
    <div class="cl pd-5 mt-20">
        <span class="l">
            <a href="javascript:;" onclick="backend_create_data('添加','<?= "<?= Yii::\$app->urlManager->createUrl(''.\$controllerId.'/create') ?>" ?>',layerOpenWindowWidth,layerOpenWindowHeight)" class="btn btn-primary radius">
                <i class="Hui-iconfont">&#xe600;</i> 添加
            </a>
        </span>
    </div>
<?php echo "<?php } ?>"; ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
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

<?php
$count = 0;
$tablecolumn=$generator->getTableSchema();

    foreach ($generator->getColumnNames() as $name) {
        $commntstr=empty($tablecolumn->columns[$name]->comment)!=true?$tablecolumn->columns[$name]->comment:$name;
        $comarr=explode('=+=',$commntstr);
        $commntstr=$comarr[0];

        $jsonV=false;
        if(count($comarr)>1){
            $jsonV=json_decode($comarr[1],true);
        }

if(empty($jsonV)!=true){
    $hideStr = '';
    if(!empty($jsonV["indexHide"]) && $jsonV["indexHide"]==1){
        $hideStr = '//';
    }
    if($jsonV["type"]=="text"){
         echo $hideStr."['class'=>'yii\\grid\\DataColumn','value'=>function(\$data){   return \$data->".$jsonV["name"]."[\$data['".$name."']]??'';},'label' => '".$commntstr."'],\n";
        }else if($jsonV["type"]=="db"){
        echo $hideStr."['class'=>'yii\\grid\\DataColumn','value'=>function(\$data){   return \$data->".$jsonV["name"]."->".$jsonV["showName"]."??'';},'label' => '".$commntstr."'],\n";
    }else if($jsonV["type"]=="upload_image"){
        echo $hideStr."['value'=>function(\$data){ return Html::a(Html::img(\$data->".$name.",['class' => 'backend-index-img']),\$data->".$name.",['target' => '_blank']);},'label'=>'".$commntstr."','format'=>'raw'],\n";
    }else if($jsonV["type"]=="val"){
        echo $hideStr."['class'=>'yii\\grid\\DataColumn','value'=>'".$name."','label' => '".$commntstr."'],\n";
    }
}else{
    if($name=='is_delete'){}else{
         echo "['class'=>'yii\\grid\\DataColumn','value'=>'".$name."','label' => '".$commntstr."'],\n";
    }
}
    }
?>
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
<?php endif; ?>

</div>
<script type="text/javascript">
 //自定义JS内容
</script>
