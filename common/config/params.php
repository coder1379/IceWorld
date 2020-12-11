<?php
return [
    'project_name' => 'ICE',//项目名称用于各类提示信息title
    'md5_tmp_key' => 'ice_world',//md5临时加密秘钥用于图片路径等场景 可变更不影响使用
    'md5_forever_key' => 'ice_world',//md5 永久加密key 项目初始化时修改后将不能再修改避免影响用户密码登录
    'save_access_log' => true, //保存访问日志,根据情况缓存日志
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
    'oss_base_link' => 'http://www.test.com/',//阿里云静态文件自定义前缀 设置空则使用bucket阿里云地址
    'api_root_url' => 'http://api.localhost.com/',//api 域名生产地址 local中覆盖测试
    'admin_root_url' => 'http://admin.localhost.com/',//admin后台域名生产地址 local中覆盖测试
    'dingding_log_robot_token' => '123456',//钉钉日志机器人token ,如果生产与测试不同在local中自行覆盖
];
