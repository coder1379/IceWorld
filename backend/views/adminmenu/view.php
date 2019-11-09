<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\adminmenu\AdminMenuModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '管理员权限菜单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['label'=>'等级','value'=>$model->levelPredefine[$model->m_level]],
            ['label'=>'上级菜单','value'=>@$model->parentMenuRecord->name],
            'name',
            'controller',
            'c_action',
//['label'=>'类型','value'=>$model->typePredefine[$model->type]],
//['label'=>'状态','value'=>$model->statusPredefine[$model->status]],
            'icon',
['label'=>'添加人','value'=>@$model->addAdminRecord->nickname],
            'add_time',
            'show_sort',
        ],
    ]) ?>

</div>
