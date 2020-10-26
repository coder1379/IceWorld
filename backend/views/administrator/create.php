<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\admin\AdministratorModel */

$this->title = '添加 管理员';
$this->params['breadcrumbs'][] = ['label' => '管理员', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont administrator-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
