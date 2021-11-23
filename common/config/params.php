<?php
return [
    'project_id' => 'ice_world',//项目id !!******!!!
    'project_name' => 'IceWorld',//项目名称用于各类提示信息title !!******!!!
    'md5_tmp_key' => 'ice_world',//md5临时加密秘钥用于图片路径,jwtToken生成等场景 可变更不影响使用 !!******!!!
    'md5_forever_key' => 'ice_world',//md5 永久加密key 项目初始化时修改后将不能再修改避免影响用户密码登录 !!******!!!
    'admin_auto_login_key'=>'ien5l3Kjrb',//后台加密串 初始化时修改 修改后将导致后台自动登录需要重新登录一次 !!******!!!
    'api_debug_access_cookie' => 'ice_world0547861135577646fjwli2671kjbbqwbufye24lxlkejfe342', // api_debug 允许访问的key默认值，务必修改防止他人使用  至少64位长度不含url get无法传递的特殊字符 !!******!!!
    'backend_debug_access_role' => [2], //允许访问后台debug的后台管理role权限 2为超级管理员
    'jwt'=>[
        'jwt_strict_verification' => false,//jwt严格验证,默认false不开启,true将验证token，除特殊项目外不建议开启,对性能有一定影响,每次将查询user_login_device table token是否存在 类似传统登录模式,注意：游客没有token验证模式
        'jwt_md5_key' => 'ice_world',//jwt Token生成key注意保护,可变更但会导致已经签发的jwt登录失效 !!******!!!
        'jwt_expire_verify' => true,//开启jwt过期验证,需要前端配合进行过期续签切jwt_out_time不为0 主要判断o_t过期时间
        'jwt_out_time' => 2592000,//用户token过期时间，单位秒 2592000是30天(建议值),0为永不过期(可通过修改jwt_md5_key强制过期),当值较小时加入自动续签功能(例如过期时间2小时),建议值为30天，过期时间较短需自行考虑每个接口续签重试问题。
        'jwt_refresh_min_time'=>172800,//jwt刷新小于值，防止无意义刷新 单位秒 默认大于48小时不刷新,随jwt_out_time变化
        'jwt_refresh_max_time'=>2592000,//jwt允许刷新最大值，防止jwt过期超长时间任然可以刷新 单位秒,默认超过30天无法刷新必须重新登陆
        'jwt_device_visitor_verification' => true,//jwt设备访客验证模式,默认开启,除特例接口外均需要进行游客token有效性验证,user_token验证例外也将进行游客有效性验证,建议开启并记录访问便于分析,跟随业务后续可以选择配合前端一起关闭,开启需要前端进行配合调用续签，注意：开启后除特定排除接口外无游客身份均无法访问.
    ],
    'save_access_log' => true, //保存接口访问日志,根据情况缓存日志 可自行扩展模式 **
    'save_admin_action_log' => false, //是否保存管理员操作日志 可持续自行扩展 默认不保存 true|false
    'uploadMode' => 'local',//图片上传地址 local 上传本地 oss 上传阿里云oss服务器(需配置下发oss参数) **
    'local_static_link' => 'http://static.yii.com',//本地静态保存文件域名参数 **
    'oss' => [ //不适用OSS目前也暂时保留OSS配置避免错误 **
        'accessKeyId' => 'xxxxxxxx',
        'accessKeySecret' => 'xxxxxxxxxxxxxxx',
        'bucket' => 'staticpath',
        'endPoint' => 'oss-cn-beijing.aliyuncs.com',
        'oss_base_link' => 'http://www.test.com/',//阿里云静态文件自定义前缀 设置空则使用bucket阿里云地址 **
    ],
    'api_root_url' => 'http://api.localhost.com/',//api 域名生产地址 local中覆盖测试 **
    'admin_root_url' => 'http://admin.localhost.com/',//admin后台域名生产地址 local中覆盖测试 **
    'dingding_log_robot_token' => '123456',//钉钉日志机器人token ,如果生产与测试不同在local中自行覆盖 **
    'admin_site_show_name'=>'后台管理系统',//显示名称-可在local里覆盖标明测试环境 ***
    'send_sms' =>true,//短信消息是否真实发送,可在-local中false覆盖用于测试不真是发送
    'call_limit'=>[ //调用限速控制，如果没有使用可以直接屏蔽掉
        'md5_keys'=>[
            // md5md5限制的密钥，全项目相同公用
            'key0'=>'kwi_2oi4=23iuyi2y-2fwr2', // 限速加密 !!******!!! 初始化时修改
            'key1'=>'ljl=2332_l3jfg4=k3lj-l3',// 限速加密 !!******!!!初始化时修改
            'key2'=>'fjwllmseei_wf234=3li_34565',// 限速加密 !!******!!! 初始化时修改
        ],
        'sms'=>[
            // 短信控制的相关参数 keywords_pre_name，statistics_pre_name 主要为防止不同项目不同地方调用重名所以进行配置
            'keywords_pre_name' => 'ice_sms_calllimit_img_captcha_', // 缓存关键字前缀配置名字 !!******!!! 建议替换ice为项目名称防止多项目公用redis导致意外问题
            'statistics_pre_name' => 'ice_sms_calllimit_statistics_', // 缓存统计前缀配置名字 !!******!!! 建议替换ice为项目名称防止多项目公用redis导致意外问题
            'img_code_timeout' => 300, //图片验证码过期时间

            'level_1_day_max'=>500,// 每日最大值出现验证码 根据实际发送量调整,影响用户体验，建议值稍大一点
            'level_1_hour_max'=>100,// 每小时最大值出现验证码 根据实际发送量调整,影响用户体验，建议值稍大一点

            'level_2_day_max'=>2000,// 每日最大值二级验证 根据实际发送量不影响用户体验可以稍小
            'level_2_hour_max'=>500,// 每小时最大值二级验证 根据实际发送量不影响用户体验可以稍小
        ]
        //如需要其他与短信分开的限制功能可以扩充，在new CallLimit(构造值),CaptchasController(类似imagecodecaptcha)时传入或直接写值即可
    ], // 调用限制 md5 key列表注意一定要修改****
];
