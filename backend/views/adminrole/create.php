<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminRoleModel */

$this->title = '添加 管理员角色';
$this->params['breadcrumbs'][] = ['label' => '管理员角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="createeditcont admin-role-model-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'authlist'=>$authlist,
        'menulist'=>$menulist,
    ]) ?>

</div>
