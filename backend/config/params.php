<?php
return [
    'authLevel'=>1,//权限认证级别,1只认证控制器，2细化到action
    'adminEmail' => 'admin@example.com',
    'staticFilePath'=>'',//前端静态文件目录，静态文件分离时配置
    'adminAutoLoginKey'=>'ienr79iuYte4Kjrb',//后台加密串 初始化时修改 修改后将导致后台自动登录需要重新登录一次
    'adminLoginExpireTime'=>2592000,//后台登录cookie保存时间 30天
    'adminSiteShowName'=>'后台管理系统',//显示名称-可在local里覆盖标明测试环境
];
