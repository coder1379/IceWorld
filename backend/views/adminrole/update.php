<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\services\adminrole\AdminRoleModel */

$this->title = '修改管理员角色：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '管理员角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="createeditcont admin-role-model-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'authlist'=>$authlist,
        'menulist'=>$menulist,
    ]) ?>

</div>
