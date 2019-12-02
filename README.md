ICE WORLD
===============================

程序见数据交换以数据形式传递，object形式均为model实例

Nginx 配置中加入

index index.php index.html index.htm;

if (!-e $request_filename){

   		rewrite ^/(.*) /index.php last;
   		
}


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

@return type(json或者string,int等) yes|no {"data":{"@model":"common\services\site\SiteApiModel","userRecord":{"@model":"common\services\site\SiteApiModel","@fields":"list","inviterUserRecord":{"@model":"common\services\site\SiteApiModel","names":"xxx"}}}}
code,msg等如果不写默认为yes|no对应的值yes操作成功,no参数错误，@model为引用的对象,@fields为model对应的显示字段场景,注意继承关系

如果内容太多不方便拼接或者写到备注里可以直接引入文件：
     
 @return file no site/getListJson.txt //文件统一存放在api/document下，引用时不包含document


    /**
     * 获取详情 第一行标题
     * @param string $user 手机号 true 1=描述,2=描述
     * @param string $pwd 密码  与验证码有一个必填
     * @param string $push_plist 所在客户端类型 1,2,3,4,5,6 必填
     * @param array $data 数据
     * @return json 成功 {"code":200,"msg":"success","data":{}}，失败 {"code":1001,"msg":"手机已经注册存在","data":{}}
     * @description 一些描述解释的描述信息
     */



注意：
当使用$include获取列表或详情下关联数据时由于会导致多次查询数据库，可以在query时配合->with('xxxRecor') 进行直接加载一次查询对应关联数据，详细内容查看yii2 及时加载与延迟加载

接口全量自动测试：
AllapitestController.php 中手动写入需要全量测试的接口


