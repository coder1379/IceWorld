ICE WORLD 
项目基础模板
PHP>=7.2 建议至少>=7.2.5
常用后台(crud,富文本,图,关联,下拉,多选,导出(_search,nameSearch自行打开)),api接口(crud)logic,apid文档,接口测试,全量测试(自行实现业务),sms短信记录(多app)(海外手机号),发送短信倒计时缓存处理,account(jwt+token+续签+多app),多渠道管理(需自行完成后端代码),多app管理(需自行完成后端代码),运营后台操作日志,admin管理接口debug,错误信息自动推送钉钉,消息队列,短信风控（仅后端逻辑代码，待前端同步测试）,backend点击title排序(默认关闭，自行修改nameSearch ActiveDataProvider->sort 具体如下方排序)

#####待实现功能:第三方登录集成,用户转换率处理,token刷新与安全问题的合适方案，目前没有刷新token方案不是太适合端token与更大的适用范围。


===============================
##新项目复制流程
1.获取代码,
2.创建db,
3.导入db,
4.配置api,admin域名,static(选择性配置)
5.修改environments dev,befprod,prod 下 common/main-local.php common/params-local.php对应参数, 执行init
6.修改commmon/params.php对应参数
7.添加systemd queue守护进程 详见：systemd 常见配置
8.添加crontab 钉钉错误提醒机器人 crontab代码如下,注意配置钉钉机器人token，并设置消息关键字 异常日志
```
#yii2 每分钟进行一次系统检测发现异常钉钉推送
* * * * * /usr/local/php74/bin/php /data/wwwroot/yii2/yii index/systemtour
```
9.根据需求修改模板框架以提高开发速度,**注意user表仅可生成覆盖后台相关的UserModel,UserQuery,UserSearch,UserApiModel,userLogic与backend/userController,api/userController,注意生成后输出的token及pwd相关字段UserApiModel中屏蔽。不能覆盖UserCommon,account相关部分，已有特定代码

10.发布与测试,根据是否有redis选择 CaptchaLogic 缓存方式，默认是文件，如果使用redis注意修改cacheType=2,这里不全部统一使用cache配置redis方式是为了适应更多的场景，例如分布式时缓存与redis问题。
11.根据是否有短信实现短信发送逻辑

12.账号体系说明：
分为用户与游客，当启用游客时所有接口均至少需要游客身份（除排除游客验证的除外），主要用于在想要统计用户访问量，转换率等。
关闭游客则仅有用户，原游客可访问的接口将直接可访问。
前后端配合工作流程：
一般流程：后端接口验证权限并返回正确对应状态，前端判断成功，重试，登录，失败 并跳转到相应页面。 进入前端页面时需要同步判断token有效过期时间或是否有token等信息，注意：一般长token过期时间进入页面时判断是否还有1-2天过期，时间段自动续签，避免调用接口时出现token过期。例如 beforeEach中加入token判断

短token验证：当某些场景需要严格权限校验可配置相应action进行短token严格校验。如所有接口均需要短token校验需自行修改AuthApiControle

jwt过期时间很短：当某些应用需要较短的jwt过期时间则需要在接口调用封装中处理token续签后自动重新执行请求。

游客token通过device_code,app_id 判断是否登录过，可 每次生成不同，或使用设备指纹（优先采用）等。

用户登录逻辑：验证登录方式是否绑定，绑定验证账号密码，登录成功将短token写入用户登录设备，userid+device_code+appid唯一

13.游客Model 如有扩展注意getName为拼接，sourcechannel可重新生成并扩展

==================================


程序见数据交换以数据形式传递，object形式均为model实例

Nginx 配置中加入

最外层加入
```nginx
index index.php index.html index.htm;
if (!-e $request_filename){
    rewrite ^/(.*) /index.php last;
}
```

数据库连接字符串顺序

'dsn' => 'mysql:host=127.0.0.1;dbname=ice_world_db;port=3069',

composer 生产性能优化：
composer dumpautoload -o
根据情况使用：
composer dump-autoload -a

composer dump-autoload --apcu //需要安装apcu扩展

字段包含is_delete 将自动加上is_delete 条件过滤

字段包含user_id 将自动加上user_id 条件过滤

API DOC 自动生成接口文档规则
===============================
指向某个logic备注作为当前接口文档说明
/**
** targetDoc->common\services\user\UserLogic->detail 
**
**/

备注显示为接口文档示例

第一行为标题

@notes 备注

@param type name desc require default

@param @model common\services\xxx\XxxApiModel create //备注中 @model表示引入model com..为model路径,create 为场景标识

@return type(json或者string,int等) yes|no {"data":{"@model":"common\services\site\SiteApiModel","userRecord":{"@model":"common\services\site\SiteApiModel","@fields":"list","inviterUserRecord":{"@model":"common\services\site\SiteApiModel","names":"xxx"}}}}
code,msg等如果不写默认为yes|no对应的值yes操作成功,no参数错误，@model为引用的对象,@fields为model对应的显示字段场景,注意继承关系

如果内容太多不方便拼接或者写到备注里可以直接引入文件：
     
 @return file no site/getListJson.txt //文件统一存放在api/document下，引用时不包含document

注意：
当使用$include获取列表或详情下关联数据时由于会导致多次查询数据库，可以在query时配合->with('xxxRecor') 进行直接加载一次查询对应关联数据，详细内容查看yii2 及时加载与延迟加载

接口全量自动测试：
AllapitestController.php 中手动写入需要全量测试的接口

##队列 yii2-queue
无报错即执行完成，如需重试直接throw异常

##基本流程
创建数据库表格->生成Model->生成curd

约定：
接口参数必填与文档问题：
mysql表尽量使用status = -1表示删除， 过滤有效数据时直接使用 status>-1
controller action 命名规则同yii 大小访问自动转-
尽量使用yii2的rules必填字段进行字段属性控制
在模型中控制哪些字段需要输入
对于有默认值的字段如果不需要调用方输入则不写入场景中控制文档显示与输入
时间戳采用 int unsigned 

遗留问题：-------------------
接口参数自定义较为困难，需要优化
测试文档使用不方便
目前提供处理步骤思路：
1.ApiReflection 类中加入 全局参数数组
2.在控制器加入全局参数指明使用那些全局参数
3.在备注参数中预定义可以进行移除的全局参数定义

制作一套类似postman的系统，可以预定义参数，参数保存在后台用户里面

基本安全如xss，csrf，文件上传，cookie等问题规范处理
xss问题处理：
对诸如<script>、<img>、<a>等标签进行过滤
像一些常见的符号，如<>在输入的时候要对其进行转换编码，这样做浏览器是不会对该标签进行解释执行的，同时也不影响显示效果
xss攻击要能达成往往需要较长的字符串，因此对于一些可以预期的输入可以通过限制长度强制截断来进行防御
例如：htmlspecialchars($string,ENT_QUOTES)
参考：https://blog.csdn.net/qq_33862644/article/details/79344684
https://zhuanlan.zhihu.com/p/52437131
https://segmentfault.com/q/1010000004067521
https://blog.csdn.net/levones/article/details/80654233
待测试更多细节与封装


```
composer 常用流程
1 初始化项目:
创建 composer.json，并添加依赖到的扩展包；
运行 composer install，安装扩展包并生成 composer.lock；
提交 composer.lock 到代码版本控制器中，如：git;

2.项目协作者安装现有项目 (如生产环境)
克隆项目后，根目录下直接运行 composer install 从 composer.lock 中安装 指定版本 的扩展包以及其依赖

3.为项目添加新扩展包 避免对全部包进行更新导致系统问题
使用 composer require vendor/package 添加扩展包；
提交更新后的 composer.json 和 composer.lock 到代码版本控制器中，如：git;
例如：composer require "phpspec/php-diff:^1.1.0"
例如：composer update "phpspec/php-diff"

优化composer 
composer dump-autoload -o（-o 等同于 --optimize,生产优先使用此优化）
composer dump-autoload --optimize  //生产优先使用此优化
或
composer dump-autoload -o --apcu 

```

```
console mysql 长连接配置
'persistent'            => true,    //长连接
或
 // 'attributes'        => array(PDO::ATTR_PERSISTENT => true),    //长连接
```

###自定义ide无提示时在ide_phpstorm_help_1231.php中加入对应对象

####or  $searchDataQuery->andWhere(['or',['like', 'name', $keyWord],['like', 'content', $keyWord]]);

#### hasOne hasMany 额外过滤条件andOnCondition()

####open cache最佳配置
zend_extension=opcache.so
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256 ;内存大小
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=100000 ;缓存文件数量尽可能大 yii2通常在几千内
opcache.max_wasted_percentage=5
opcache.use_cwd=1
opcache.validate_timestamps=1 ;是否开启时间检查 测试环境或非docker内建议开启大概会有1-2毫秒的性能损失，建议生产设置为0不进行检查
opcache.revalidate_freq=3 ;检查时间 测试尽可能短
;opcache.save_comments=0
opcache.consistency_checks=0
;opcache.optimization_level=0

####du -h --max-depth=1 /data/wwwroot 查看服务器文件大小

#### systemd 常见配置配置
yii-queue服务端任务队列 systemd
cd /etc/systemd/system
vim yii-queue@.service
``` 内容
[Unit]
Description=Yii Queue Worker %I
After=network.target
#After=mysql.service
#Requires=mysql.service

[Service]
User=root
Group=root
ExecStart=/usr/local/php74/bin/php /data/wwwroot/yii2/yii queue/listen --verbose
Restart=always
RestartSec=5
StartLimitInterval=0

[Install]
WantedBy=multi-user.target
```
systemctl daemon-reload

//设置3个自启动任务进程 每个tps 10左右
systemctl enable yii-queue@1 yii-queue@2 yii-queue@3

systemctl start yii-queue@1 yii-queue@2 yii-queue@3
systemctl status yii-queue@1 yii-queue@2 yii-queue@3
systemctl restart yii-queue@1 yii-queue@2 yii-queue@3

#systemctl 常用命令
systemd 命令
重新加载daemon 修改配置后均需要重进加载配置
systemctl daemon-reload

开机自启动
systemctl enable opus.service

关闭开机自启动
systemctl disable opus.service

开始 start,restart
systemctl restart opus.service

停止并不在处罚自动重启功能
systemctl stop opus.service

查看启动状态
systemctl status opus.service

# 显示尾部的最新10行日志
$ sudo journalctl -n

# 显示尾部指定行数的日志
$ sudo journalctl -n 20

# 实时滚动显示最新日志
$ sudo journalctl -f

# 查看指定服务的日志
$ sudo journalctl /usr/lib/systemd/systemd


######yii2-debug 环境配置 environments/dev params-local.php中配置 相关参数

##### 通用的跨平台跨语言加解密方案： AES/CBC/PKCS5Padding 按照此标准加解密基本可以通用所有语言

####jwt 通用方案及包
php jwt ：https://github.com/firebase/php-jwt
go jwt ：github.com/dgrijalva/jwt-go
python:pip install PyJWT

#### office excel 改用 phpoffice/phpspreadsheet包，PHPExcel已经停止维护

####浏览器唯一指纹预选方案：https://github.com/fingerprintjs/fingerprintjs

####强制使用索引 select *from table force index(user_id)...

####抛出异常并写入参数  $allArgs = func_get_args(); throw new \Exception('msg:'.json_encode($allArgs));

##@需要完善@ 搜索查找需要完善的地方

## mongodb 可选包： "yiisoft/yii2-mongodb": "^2.1"(yii2支持mongo但不支持objectID转换string,优先使用), "mongodb/mongodb": "1.6.0"(配合MongoLib原生使用,主要能对objectid作为string时使用), 可两个同时使用

###excel 导出 在search中

###短信相关内容在 SmsCommom 中指定对应参数 
### 真实环境发送短信逻辑代码需要自行维护 在SendMobileSmsJobs中
###开启国际海外手机号支持流程 需修改 getMobileAreaCode 获取area_code规则，AccountCommon::getMobileFormatReturnError 完善海外手机号验证
###海外手机号模式 通过area_code判断是否为0或者86限制后续保存或缓存key是否携带区号和-
```
$areaCode = SmsCommon::getMobileAreaCode(ComBase::getStrVal('area_code', $params));
$checkRes = AccountCommon::getMobileFormatReturnError($mobile,$areaCode);
$saveMobile = AccountCommon::getSaveMobile($mobile, $areaCode);
```


###提示文本的逗号统一采用中文模式下的逗号

###开启多app账号模式：AppCommon打开获取_app_id控制并完善鉴权

###使用 cache文件保存时注意 一定时间后使用： $cache = new FileCache(); $cache->gc(true); //回收过期缓存文件

###成功登录前验证扩展：AccountCommon::getBeforeLoginErrorCheck 续签采用相同逻辑，如果需要限制指定类型或状态不允许登录 扩展此方法,注意如果修改了用户status 正常判断方式，续签时可能也需要相应status 判断

### user 字段 status 表示用户目前状态 -1删除或注销，1正常，2冻结，只有1表示正常，其余值都表示无法正常使用

###游客表仅用作与访问表进行转化统计不做其他使用


#######表头sort排序 注意 GridView::widget 'class'=>'yii\grid\DataColumn' 需要带有 attribute
```
nameSearch 文件修改

  $dataProvider->setSort(false); // 默认关闭排序 使用设置为具体如下对应参数配合sort 及移除 $query->addOrderBy('<?php echo $idKey; ?> desc');

open：
        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'attributes' => [
                'id' => [
                    'asc' => [
                        'id' => SORT_ASC,
                    ],
                    'desc' => [
                        'id' => SORT_DESC,
                    ],
                    'default' => SORT_ASC,
                ],
                'name' => [
                    'asc' => [
                        'name' => SORT_ASC
                    ],
                    'desc' => [
                        'name' => SORT_DESC,
                    ],
                    'default' => SORT_ASC,
                ]
            ]
        ]); // 开启排序并设置排序属性及内容
```

#########依赖内容版本更新
先当前termina设置代理,代理为本地实际值
set http_proxy=http://127.0.0.1:10809
set https_proxy=http://127.0.0.1:10809

对指定包执行更新 并显示细节 -vvv

composer update yiisoft/yii2 -vvv 或 composer update -vvv //执行全部更新

[感谢Yii](https://www.yiiframework.com/)

[感谢jetbrains为此项目提供的License](https://www.jetbrains.com/?from=IceWorld)