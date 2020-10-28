ICE WORLD
===============================

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


接口参数必填与文档问题：
1.尽量使用yii2的rules必填字段进行字段属性控制
2.在模型中控制哪些字段需要输入
3.对于有默认值的字段如果不需要调用方输入则不写入场景中控制文档显示与输入

遗留问题：-------------------
接口参数自定义较为困难，需要优化
测试文档使用不方便
目前提供处理步骤思路：
1.ApiReflection 类中加入 全局参数数组
2.在控制器加入全局参数指明使用那些全局参数
3.在备注参数中预定义可以进行移除的全局参数定义

制作一套类似postman的系统，可以预定义参数，参数保存在后台用户里面

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
