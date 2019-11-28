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

    /**
     * 获取详情
     * @param string $user 手机号 必填
     * @param string $pwd 密码  与验证码有一个必填
     * @param string $push_plist 所在客户端类型 1,2,3,4,5,6 必填
     * @param array $data 数据
     * @return json 成功 {"code":200,"msg":"success","data":{}}，失败 {"code":1001,"msg":"手机已经注册存在","data":{}}
     */



注意：
当使用$include获取列表或详情下关联数据时由于会导致多次查询数据库，可以在query时配合->with('xxxRecor') 进行直接加载一次查询对应关联数据，详细内容查看yii2 及时加载与延迟加载



