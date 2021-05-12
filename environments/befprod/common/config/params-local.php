<?php
return [
    'oss' => [ //测试环境覆盖生产oss参数
        'accessKeyId' => '11111',
        'accessKeySecret' => '11111',
        'bucket' => 'staticpath',
        'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        'oss_base_link' => 'http://static.localice.com/',//阿里云静态文件自定义前缀覆盖生产
    ],
    'api_root_url' => 'http://befapi.localhost.com/',//api 域名预发布地址
    'admin_root_url' => 'http://befadmin.localhost.com/',//admin后台域名发布地址
    'admin_site_show_name'=>'预发布-后台管理系统',//显示名称-可在local里覆盖标明测试环境
];
