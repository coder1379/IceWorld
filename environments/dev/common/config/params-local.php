<?php
return [
    'sendsms' =>false,//覆盖params里面的短信发送，测试地址短信不发送
    'api_root_url' => 'http://api.localice.com/',//api 域名生产地址 local中覆盖测试
    'admin_root_url' => 'http://admin.localice.com/',//admin后台域名生产地址 local中覆盖测试
    'debug_access_ip' => ['127.0.0.1',], //测试环境允许访问api debug的ip地址数组
    'debug_access_host' => ['api.localice.com'], //测试环境允许访问api debug的host地址
    'debug_access_role' => [2], //测试环境允许访问后台debug的role权限
];
