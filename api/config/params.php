<?php
return [
    'adminEmail' => '',
    'returnAllErrors' => false,//控制api是否返回全部错误字段提示,默认false
    'user_token_out_time' => 3,//用户token过期时间，单位秒 2592000是30天,0为永不过期(可通过修改jwt_md5_key强制过期),当值较小时加入自动续签功能
    'open_jwt_expire_verify' => true,//开启jwt过期验证,需要前端配合进行过去续签
];
