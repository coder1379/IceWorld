ICE WORLD
===============================

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
