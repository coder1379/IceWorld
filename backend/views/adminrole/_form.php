<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\services\adminrole\AdminRoleModel */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .form-group-role{
        margin: 10px;
        display: block;
    }
    .form-group-role-left .control-label{
        width: 25%;
        display: inline-block;
        text-align: right;
    }

    .form-group-role-right{
        width:60%;
        display: inline-block;
        text-align: left;
        margin-left:5px;
    }
    .form-group-role-right .actionbox{
        <?php
         if(Yii::$app->params['authLevel']==1){
         ?>
        display:none;
         <?php
         }
         ?>
    }
    .permission-list > dd > dl{
    <?php
       if(Yii::$app->params['authLevel']==1){
       ?>
        display: inline-block;
    <?php
    }
    ?>

    }
    .form-group-role-right input{
        margin-left:5px;
        margin-right:2px;
    }

    .authbox{
        margin-top:10px;
    }
</style>
<div class="admin-role-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div >
        <?php
        $returnstr='<div class="form-group-role form-group-role-left"><label class="control-label">权限列表:</label><div class="form-group-role-right">';
        ?>
        <div class="form-group-role form-group-role-left"><label class="control-label">权限列表:</label><div class="form-group-role-right">
                <?php

                if(empty($menulist)!=true && empty($authlist)!=true){
                foreach($menulist as $m){
                if(!empty($m['children_name'])){
                ?>
                <dl class="permission-list">
                    <dt>
                        <label>
                            <input type="checkbox" value="" name="menu-auth-<?php echo $m['id']; ?>" id="menu-auth-<?php echo $m['id']; ?>">
                            <?php echo $m['name']; ?></label>
                    </dt>
                    <dd>
                        <?php
                        foreach($authlist as $a){
                        $authflagtemp=$a['own']['auth_flag'];
                        if(in_array($authflagtemp,$m['children_name'])){
                        $checkbox='';
                        $rolelistobj=false;
                        if(empty($model->auth_list)!=true){
                            $rolelistobj=json_decode($model->auth_list);
                        }
                        if($model->isNewRecord!=true && $rolelistobj!=false && empty($rolelistobj->$authflagtemp)!=true){
                            $checkbox=' checked ';
                        }
                        ?>
                        <dl class="cl permission-list2">
                            <dt>
                                <label class="">
                                    <input  type="checkbox" <?php echo $checkbox; ?> id="ctl_<?php  echo $authflagtemp; ?>_c" name="ctl_<?php  echo $authflagtemp; ?>_c" value="1" />
                                    <?php echo empty($m['children_list'][$a['own']['auth_flag']]['name'])!=true?$m['children_list'][$a['own']['auth_flag']]['name']:$a['own']['name']; ?></label>
                            </dt>
                            <dd class="actionbox">
                                <?php
                                if(empty($a['action'])!=true){
                                    foreach($a['action'] as $act){
                                        $actionflagstr=$act['own']['auth_flag'];
                                        $actioncheck='';
                                        if($model->isNewRecord!=true && $rolelistobj!=false && empty($rolelistobj->$authflagtemp->$actionflagstr)!=true){
                                            $actioncheck=' checked ';
                                        }
                                        ?>
                                        <label class="">
                                            <input type="checkbox" <?php echo $actioncheck; ?> id="ctl_<?php echo $authflagtemp; ?>_<?php echo $actionflagstr; ?>" name="ctl_<?php echo $authflagtemp; ?>[]" value="<?php echo $actionflagstr; ?>" />
                                            <?php echo $act['own']['name']; ?></label>
                                        <?php
                                    }
                                }
                                ?>
                            </dd>
                         </dl>
                                <!--<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>-->


                            <?php
                            }
                            }

                            ?>
                            </dd>
                        </dl>
                        <?php
                        }
                        }
                        }
                        ?>
            </div>
        </div>
    </div>

<!--
    <?= $form->field($model, 'status')->label('状态')->dropDownList($model->statusPredefine,['prompt' => '请选择状态','options'=>[$model->status=>['Selected'=>true]]]) ?>

    <?= $form->field($model, 'show_sort')->textInput() ?>-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function(){
        // 防止重复提交
        $('form').on('beforeValidate', function (e) {
            $(':submit').attr('disabled', true).addClass('disabled');
        });
        $('form').on('afterValidate', function (e) {
            if (cheched = $(this).data('yiiActiveForm').validated == false) {
                $(':submit').removeAttr('disabled').removeClass('disabled');
            }
        });
        $('form').on('beforeSubmit', function (e) {
            $(':submit').attr('disabled', true).addClass('disabled');
        });


        $(".permission-list dt input:checkbox").click(function(){
            $(this).closest("dl").find("dd input:checkbox").prop("checked",$(this).prop("checked"));
        });
        $(".permission-list2 dd input:checkbox").click(function(){
            var l =$(this).parent().parent().find("input:checked").length;
            var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
            if($(this).prop("checked")){
                $(this).closest("dl").find("dt input:checkbox").prop("checked",true);
                $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",true);
            }
            else{
                if(l==0){
                    $(this).closest("dl").find("dt input:checkbox").prop("checked",false);
                }
                if(l2==0){
                    $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",false);
                }
            }
        });


    });

</script>