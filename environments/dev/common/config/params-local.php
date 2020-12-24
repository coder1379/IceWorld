<?php
return [
    'send_sms' =>false,//覆盖params里面的短信发送，测试地址短信不发送
    'oss' => [ //测试环境覆盖生产oss参数
        'accessKeyId' => '11111',
        'accessKeySecret' => '11111',
        'bucket' => 'staticpath',
        'endPoint' => 'oss-cn-beijing.aliyuncs.com',
    ],
    'oss_base_link' => 'http://static.localice.com/',//阿里云静态文件自定义前缀覆盖生产  设置空则使用bucket阿里云地址
    'api_root_url' => 'http://api.localice.com/',//api 域名生产地址 local中覆盖测试
    'admin_root_url' => 'http://admin.localice.com/',//admin后台域名生产地址 local中覆盖测试
    'debug_access_ip' => ['127.0.0.1',], //测试环境允许访问api debug的ip地址数组
    'debug_access_host' => ['api.localice.com'], //测试环境允许访问api debug的本地host地址，一般为开发者的本地环境
    'debug_access_role' => [2], //测试环境允许访问后台debug的role权限
];
