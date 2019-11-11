<?php
return [
    'md5Key' => 'ice_world',//md5加密秘钥
    'adminEmail' => '',
    'supportEmail' => '',
    'user.passwordResetTokenExpire' => 3600,
    'sendsms' =>true,//是否确认发送短信,true真实发送，可在-local中false覆盖用于测试不真是发送
    'uploadMode' => 'local',//图片上传地址 local 上传本地 oss 上传阿里云oss服务器(需配置下发oss参数)
    'local_static_link' => 'http://static.yii.com',//本地静态保存文件域名参数
    'oss' => [ //不适用OSS目前也暂时保留OSS配置避免错误
        'accessKeyId' => 'xxxxxxxx',
        'accessKeySecret' => 'xxxxxxxxxxxxxxx',
        'bucket' => 'staticpath',
        'endPoint' => 'oss-cn-beijing.aliyuncs.com',
    ],
    'oss_base_link' => 'http://www.test.com/',//阿里云静态文件自定义前缀

];
