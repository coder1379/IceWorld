<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\services\admin\AdminRoleModel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '管理员角色', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .menu-list{
margin: 3px;
        font-size:16px;
    }

    .controller-list{
        font-size:14px;
        margin-left:20px;
        margin-top:5px;
        margin-bottom:5px;
        <?php
         if(Yii::$app->params['authLevel']==1){
         ?>
        display: inline-block;
         <?php
         }
         ?>
    }
    .controller-list div{

    }

    .action-list{
        margin-left:15px;
        font-size: 12px;
    }

</style>
<div class="admin-role-model-view">

    <!--<h1>测试校色123</h1>-->

    <table id="w0" class="table table-striped table-bordered detail-view"><tbody><tr><th>ID</th><td><?php echo $model->id; ?></td></tr>
        <tr><th>角色名字</th><td><?php echo $model->name; ?></td></tr>
        <tr><th>权限列表</th><td><?php if(!empty($authNameList)){
                    foreach ($authNameList as $menu){
                        if(!empty($menu['controllers'])){
                            ?>
                            <div class="menu-list"><?php echo $menu['name']; ?></div>
                            <?php
                            foreach ($menu['controllers'] as $c){
                                ?>
                                <div class="controller-list">
                                <div><?php echo $c['name']; ?></div>
                                    <?php
                                    if(!empty($c['actions'])){
                                        foreach ($c['actions'] as $a){
                                            ?>
                                            <span class="action-list"><?php echo $a; ?></span>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <?php
                            }
                        }
                    }
                } ?></td></tr>
        <tr><th>添加人</th><td><?php echo @$model->addAdminRecord->nickname; ?></td></tr>
        <tr><th>添加时间</th><td><?php echo $model->add_time; ?></td></tr></tbody></table>

</div>
