<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\systemconfig\SystemConfigModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '系统配置文件', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-config-model-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'c_val',
            'desc',
['label'=>'添加时间','value'=>(($model->add_time)>0?date('Y-m-d H:i:s',$model->add_time):'')],
['label'=>'更新时间','value'=>(($model->update_time)>0?date('Y-m-d H:i:s',$model->update_time):'')],
        ],
    ]) ?>

</div>
