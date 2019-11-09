<?php
return [
    'authLevel'=>1,//权限认证级别,1只认证控制器，2细化到action
    'adminEmail' => 'majie@example.com',
    'staticFilePath'=>'',//前端静态文件目录，静态文件分离时配置
    'adminAutoLoginKey'=>'ienr79iuYte4Kjrb',//后台加密串
    'adminLoginExpireTime'=>864000,//后台登录cookie保存时间
];
