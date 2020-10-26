<?php
use common\base\BackendCommon;
$common=new BackendCommon();
?>
<style>
    table{
        width: 800px;
        margin-top: 100px;
    }
</style>
<div align="center">

    <table class="table table-border table-bordered table-bg mt-20">
        <thead>
        <tr>
            <th colspan="2" scope="col">管理员信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th width="30%">登录账号</th>
            <td><span id="lbServerName"><?php echo $adminInfo['login_username']; ?></span></td>
        </tr>
        <tr>
            <td>管理员姓名</td>
            <td><?php echo $adminInfo['nickname']; ?></td>
        </tr>
        <tr>
            <td>手机号</td>
            <td><?php echo $adminInfo['mobile']; ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?php echo $adminInfo['email']; ?></td>
        </tr>
        <tr>
            <td>角色</td>
            <td><?php echo $adminInfo['role_name']; ?></td>
        </tr>
        <tr>
            <td>创建时间</td>
            <td><?php echo $adminInfo['add_time']; ?></td>
        </tr>
        </tbody>
    </table>

</div>