<?php
return [
    'oss' => [ //测试环境覆盖生产oss参数
        'accessKeyId' => '11111',
        'accessKeySecret' => '11111',
        'bucket' => 'staticpath',
        'endPoint' => 'oss-cn-beijing.aliyuncs.com',
    ],
    'oss_base_link' => 'http://static.localice.com/',//阿里云静态文件自定义前缀覆盖生产
    'api_root_url' => 'http://befapi.localhost.com/',//api 域名预发布地址
    'admin_root_url' => 'http://befadmin.localhost.com/',//admin后台域名发布地址
    'debug_access_ip' => ['127.0.0.1',], //允许查看预发布环境的debug ip列表
    'debug_access_role' => [2], //允许查看预发布环境的debug 权限组列表
];
