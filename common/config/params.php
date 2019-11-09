<?php
return [
    'adminEmail' => '',
    'supportEmail' => '',
    'user.passwordResetTokenExpire' => 3600,
    'sendsms' =>true,//是否确认发送短信,true真实发送，可在-local中false覆盖用于测试不真是发送
    'oss' => [
        'accessKeyId' => 'xxxxxxxx',
        'accessKeySecret' => 'xxxxxxxxxxxxxxx',
        'bucket' => 'staticpath',
        'endPoint' => 'oss-cn-beijing.aliyuncs.com',
    ],//阿里云OSS配置文件
    'images_base_link' => 'http://www.test.com/',
    'sendsms' =>true,//是否发送短信
];
